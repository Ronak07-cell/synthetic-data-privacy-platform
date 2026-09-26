from sklearn.datasets import fetch_openml
import pandas as pd

print("Downloading dataset (first run may take a moment)...")
adult = fetch_openml(name="adult", version=2, as_frame=True)
df = adult.frame

df = df.sample(n=1000, random_state=42)
df.to_csv("data/sample/sample_data.csv", index=False)

print(f"Saved {len(df)} rows")
print(df.head())