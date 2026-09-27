<!DOCTYPE html>
<html>
<head>
    <title>Synthetic Data Generator</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 60px auto; padding: 0 20px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input { display: block; margin-top: 5px; padding: 8px; width: 100%; box-sizing: border-box; }
        button { margin-top: 20px; padding: 10px 20px; background: #333; color: white; border: none; cursor: pointer; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>Synthetic Data Privacy Platform</h1>
    <p>Upload a CSV file to generate a privacy-preserving synthetic version using CTGAN.</p>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/generate" method="POST" enctype="multipart/form-data">
        @csrf

        <label for="file">CSV File</label>
        <input type="file" name="file" id="file" accept=".csv" required>

        <label for="num_rows">Number of synthetic rows to generate</label>
        <input type="number" name="num_rows" id="num_rows" value="100" min="1" required>

        <label for="epochs">Training epochs</label>
        <input type="number" name="epochs" id="epochs" value="50" min="1" required>

        <button type="submit">Generate Synthetic Data</button>
    </form>
</body>
</html>
