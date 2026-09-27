import os
import shutil
import math
import pandas as pd
from fastapi import FastAPI, UploadFile, File, Form
from generator import train_and_generate
from metrics import compute_utility_metrics, compute_privacy_metrics


def clean_for_json(obj):
    if isinstance(obj, dict):
        return {k: clean_for_json(v) for k, v in obj.items()}
    elif isinstance(obj, list):
        return [clean_for_json(v) for v in obj]
    elif isinstance(obj, float):
        if math.isnan(obj) or math.isinf(obj):
            return None
        return obj
    else:
        return obj


app = FastAPI(title="Synthetic Data Privacy Platform")

UPLOAD_DIR = "uploads"
os.makedirs(UPLOAD_DIR, exist_ok=True)


@app.get("/health")
def health_check():
    return {"status": "ok"}


@app.post("/generate")
async def generate_synthetic_data(
    file: UploadFile = File(...),
    num_rows: int = Form(500),
    epochs: int = Form(100)
):
    file_path = os.path.join(UPLOAD_DIR, file.filename)
    with open(file_path, "wb") as buffer:
        shutil.copyfileobj(file.file, buffer)

    real_data, synthetic_data = train_and_generate(file_path, num_rows=num_rows, epochs=epochs)

    utility = compute_utility_metrics(real_data, synthetic_data)
    privacy = compute_privacy_metrics(real_data, synthetic_data)

    result = {
        "rows_generated": len(synthetic_data),
        "utility_metrics": utility,
        "privacy_metrics": privacy,
        "synthetic_preview": synthetic_data.head(10).to_dict(orient="records")
    }

    return clean_for_json(result)
