<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Consumption Calculator</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Electricity Consumption Calculator</h2>
        <form method="POST">
            <div class="form-group">
                <label for="voltage">Voltage (V):</label>
                <input type="number" class="form-control" id="voltage" name="voltage" value="<?php echo isset($_POST['voltage']) ? $_POST['voltage'] : ''; ?>" required min="0">
            </div>
            <div class="form-group">
                <label for="current">Current (A):</label>
                <input type="number" class="form-control" id="current" name="current" value="<?php echo isset($_POST['current']) ? $_POST['current'] : ''; ?>" required min="0" step="any">
            </div>
            <div class="form-group">
                <label for="rate">Current Rate (sen/kWh):</label>
                <input type="number" class="form-control" id="rate" name="rate" value="<?php echo isset($_POST['rate']) ? $_POST['rate'] : ''; ?>" required min="0">
            </div>
            <button type="submit" class="btn btn-primary mt-3">Calculate</button>
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Get form inputs and validate
                $voltage = isset($_POST['voltage']) ? $_POST['voltage'] : 0;
                $current = isset($_POST['current']) ? $_POST['current'] : 0;
                $rate = isset($_POST['rate']) ? $_POST['rate'] / 100 : 0;  // Convert to RM (sen to RM)
                
                // Check if the values are non-negative
                if ($voltage < 0 || $current < 0 || $rate < 0) {
                    echo "<div class='alert alert-danger mt-4'>Please enter valid positive values for all fields.</div>";
                } else {
                    // Calculate Power (W)
                    $power = $voltage * $current;  // Power in Watts (W)
                    $power_kw = $power / 1000;  // Convert to kW

                    // Display Results
                    echo "<div class='mt-5'>";
                    echo "<h4>Results:</h4>";
                    echo "<p>Power: " . number_format($power_kw, 4) . " kW</p>";
                    echo "<p>Rate: RM " . number_format($rate, 3) . " per kWh</p>";

                    // Table Header
                    echo "<table class='table table-bordered mt-3'>";
                    echo "<thead><tr><th>Hour</th><th>Energy (kWh)</th><th>Total (RM)</th></tr></thead><tbody>";

                    // Loop through each hour
                    for ($hour = 1; $hour <= 24; $hour++) {
                        $energy = $power_kw * $hour;  // Energy in kWh
                        $total_charge = $energy * $rate;  // Total charge in RM
                        echo "<tr><td>{$hour}</td><td>" . number_format($energy, 5) . "</td><td>RM " . number_format($total_charge, 2) . "</td></tr>";
                    }

                    echo "</tbody></table>";
                    echo "</div>";
                }
            }
        ?>
    </div>
</body>
</html>
