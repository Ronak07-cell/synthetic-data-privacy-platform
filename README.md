# Synthetic Data Privacy Platform

A full-stack platform that generates GAN-based synthetic tabular data and quantifies both its **utility** (how statistically useful it is) and **privacy** (how safe it is from leaking real records). Built as a polyglot system: a Python ML service for the actual generation/evaluation, and a Laravel (PHP) web app as the user-facing frontend.

## Why this exists

Organizations often can't share sensitive datasets (health records, financial data, census-style demographic data) due to privacy regulations, even when the *statistical patterns* in that data would be genuinely useful for research, testing, or model development. Synthetic data generation solves this: train a generative model on the real data, then generate new, artificial records that preserve the same statistical relationships without corresponding to any real individual.

This project demonstrates that full pipeline end-to-end, along with the privacy verification step that's often skipped in simpler synthetic-data demos.

## Architecture

- **`ml_service/`** — Python/FastAPI service. Trains a CTGAN model on an uploaded CSV, generates synthetic rows, and computes:
  - **Utility metrics**: column-shape and column-pair-trend scores (via SDV's evaluation framework) — how well the synthetic data preserves the real data's statistical structure
  - **Privacy metrics**: exact-match rate — what fraction of synthetic rows are identical to real rows (should be ~0% for a well-trained model; a high rate indicates the model is memorizing rather than generalizing)
- **`laravel_app/`** — PHP/Laravel frontend. Provides an upload form, forwards the file and parameters to the Python service over HTTP, and renders the results (scores + a data preview table).

## Setup

### Python ML service
```bash
cd ml_service
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
uvicorn main:app --reload --port 8001
```

### Laravel app
```bash
cd laravel_app
composer install
cp .env.example .env
php artisan key:generate
# Configure DB_* variables in .env for your local MySQL instance
php artisan migrate
php artisan serve
```

Visit `http://127.0.0.1:8000/generate` to use the upload form.

## Sample data

```bash
python3 data/sample/download_sample.py
```

Fetches a 1,000-row sample of the UCI Adult Income dataset via scikit-learn's `fetch_openml`, useful for testing the pipeline with realistic mixed-type (numeric + categorical) data.

## Tech stack

- **Python**: FastAPI, SDV (CTGAN/TVAE), pandas, scikit-learn
- **PHP**: Laravel 13, Blade templates
- **MySQL**: Laravel's backing database
- **Architecture pattern**: microservice-style separation between ML logic and web presentation, communicating over HTTP

## Known limitations / next steps

- Training runs synchronously — a real production version would use a background job queue (Laravel Queues + a webhook, or polling) so large datasets don't block the HTTP request
- No job history persistence yet — results aren't saved to the database, only displayed once
- Privacy metric is a simple exact-match check; a more rigorous approach would include membership inference attack simulation
- Single-table datasets only; SDV also supports relational (multi-table) synthesis

## What I learned

- End-to-end integration between two independently-running services in different languages, communicating over a well-defined HTTP contract
- Real GAN training mechanics (CTGAN) and the practical tradeoffs in synthetic data quality vs. training time
- A genuine data science edge case: NaN values produced by statistical evaluation on small samples can silently break JSON serialization — fixed with a recursive sanitization pass over the entire response payload before returning it
- Laravel's HTTP client for service-to-service communication, file upload handling, and Blade templating
- Diagnosing issues by reading actual server logs and verifying files/processes directly, rather than trusting assumptions about what "should" be running
