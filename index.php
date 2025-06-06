<?php
// Function to get POST data and ensure it is a valid positive number
function getPostData($key) {
    return isset($_POST[$key]) && $_POST[$key] >= 0 ? $_POST[$key] : 0;
}

// Function to calculate Power in kW
function calculatePower($voltage, $current) {
    return ($voltage * $current) / 1000;  // Convert to kW
}

// Function to calculate the total charge for energy consumption
function calculateCharge($power_kw, $rate, $hour) {
    $energy = $power_kw * $hour;  // Energy in kWh
    return $energy * $rate;  // Total charge in RM
}
?>
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
                // Get form data using PHP function
                $voltage = getPostData('voltage');
                $current = getPostData('current');
                $rate = getPostData('rate') / 100;  // Convert to RM (sen to RM)

                // Ensure all values are valid
                if ($voltage < 0 || $current < 0 || $rate < 0) {
                    echo "<div class='alert alert-danger mt-4'>Please enter valid positive values for all fields.</div>";
                } else {
                    // Calculate power in kW
                    $power_kw = calculatePower($voltage, $current);

                    // Display Results
                    echo "<div class='mt-5'>";
                    echo "<h4>Results:</h4>";
                    echo "<p>Power: " . number_format($power_kw, 4) . " kW</p>";
                    echo "<p>Rate: RM " . number_format($rate, 3) . " per kWh</p>";

                    // Table Header
                    echo "<table class='table table-bordered mt-3'>";
                    echo "<thead><tr><th>Hour</th><th>Energy (kWh)</th><th>Total (RM)</th></tr></thead><tbody>";

                    // Loop through each hour of the day
                    for ($hour = 1; $hour <= 24; $hour++) {
                        $total_charge = calculateCharge($power_kw, $rate, $hour);  // Calculate cost for each hour
                        $energy = $power_kw * $hour;  // Energy consumption for each hour
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
