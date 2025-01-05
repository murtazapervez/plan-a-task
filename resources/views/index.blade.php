<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carbon Intensity</title>
</head>
<body>
    <h1>Carbon Intensity</h1>
        <p>Carbon Intensity: {{ $intensity ?? 'N/A' }} gCO2eq/kWh</p>
        <div style="width: 100px; height: 100px; background-color: {{ $status }};"></div>
</body>
</html>