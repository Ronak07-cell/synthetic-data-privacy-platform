from sdv.evaluation.single_table import evaluate_quality
from sdv.metadata import Metadata


def compute_utility_metrics(real_data, synthetic_data):
    metadata = Metadata.detect_from_dataframe(data=real_data, table_name="data")

    quality_report = evaluate_quality(
        real_data=real_data,
        synthetic_data=synthetic_data,
        metadata=metadata
    )

    overall_score = quality_report.get_score()
    column_shapes = quality_report.get_details(property_name="Column Shapes")

    column_shapes_dict = column_shapes.to_dict(orient="records")

    for row in column_shapes_dict:
        if isinstance(row.get("Score"), float) and (row["Score"] != row["Score"]):
            row["Score"] = None

    overall_score_clean = overall_score if overall_score == overall_score else None

    return {
        "overall_score": overall_score_clean,
        "column_shapes": column_shapes_dict
    }


def compute_privacy_metrics(real_data, synthetic_data):
    real_rows = set(real_data.astype(str).apply(lambda row: "|".join(row), axis=1))
    synthetic_rows = synthetic_data.astype(str).apply(lambda row: "|".join(row), axis=1)

    exact_matches = synthetic_rows.isin(real_rows).sum()
    exact_match_rate = exact_matches / len(synthetic_data)

    return {
        "exact_match_count": int(exact_matches),
        "exact_match_rate": float(exact_match_rate),
        "total_synthetic_rows": len(synthetic_data)
    }
