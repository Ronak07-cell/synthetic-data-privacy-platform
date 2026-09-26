import pandas as pd
from sdv.metadata import Metadata
from sdv.single_table import CTGANSynthesizer


def train_and_generate(csv_path, num_rows=1000, epochs=100):
    real_data = pd.read_csv(csv_path)

    metadata = Metadata.detect_from_dataframe(data=real_data, table_name="data")

    synthesizer = CTGANSynthesizer(metadata, epochs=epochs)
    synthesizer.fit(real_data)

    synthetic_data = synthesizer.sample(num_rows=num_rows)

    return real_data, synthetic_data
if __name__ == "__main__":
    real, synthetic = train_and_generate("data/sample/sample_data.csv", num_rows=10, epochs=50)
    print("\n--- REAL DATA SAMPLE ---")
    print(real.head())
    print("\n--- SYNTHETIC DATA SAMPLE ---")
    print(synthetic.head())