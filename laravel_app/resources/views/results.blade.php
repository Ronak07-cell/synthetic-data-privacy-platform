<!DOCTYPE html>
<html>
<head>
    <title>Results - Synthetic Data Generator</title>
    <style>
        body { font-family: sans-serif; max-width: 900px; margin: 40px auto; padding: 0 20px; }
        .metric-box { background: #f4f4f4; padding: 15px; margin: 15px 0; border-radius: 6px; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; font-size: 13px; }
        th, td { border: 1px solid #ddd; padding: 6px 10px; text-align: left; }
        th { background: #333; color: white; }
        a { display: inline-block; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Synthetic Data Results</h1>

    <div class="metric-box">
        <h3>Utility Score</h3>
        <p>Overall quality: <strong>{{ number_format($data['utility_metrics']['overall_score'] * 100, 1) }}%</strong></p>
    </div>

    <div class="metric-box">
        <h3>Privacy Metrics</h3>
        <p>Exact match rate: <strong>{{ number_format($data['privacy_metrics']['exact_match_rate'] * 100, 2) }}%</strong></p>
        <p>({{ $data['privacy_metrics']['exact_match_count'] }} exact matches out of {{ $data['privacy_metrics']['total_synthetic_rows'] }} synthetic rows)</p>
    </div>

    <h3>Synthetic Data Preview (first 10 rows)</h3>
    <table>
        <thead>
            <tr>
                @foreach (array_keys($data['synthetic_preview'][0]) as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($data['synthetic_preview'] as $row)
                <tr>
                    @foreach ($row as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="/generate">← Generate another</a>
</body>
</html>
