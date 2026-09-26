from generator import train_and_generate
from metrics import compute_utility_metrics, compute_privacy_metrics

real, synthetic = train_and_generate("../data/sample/sample_data.csv", num_rows=200, epochs=50)

print("\n--- UTILITY METRICS ---")
utility = compute_utility_metrics(real, synthetic)
print(f"Overall quality score: {utility['overall_score']:.4f}")

print("\n--- PRIVACY METRICS ---")
privacy = compute_privacy_metrics(real, synthetic)
print(privacy)
