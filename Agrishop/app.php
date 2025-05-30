<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crop Recommendation</title>
  <link rel="stylesheet" href="app.css">
</head>
<body>
    <?php include 'header.php'; ?>
<div class="img"></div>
  <div class="container">
    <h1>Crop Recommendation System</h1>
    <form id="crop-form">
      <label for="nitrogen">Nitrogen (N):</label>
      <input type="number" id="nitrogen" name="nitrogen" required><br>

      <label for="phosphorus">Phosphorus (P):</label>
      <input type="number" id="phosphorus" name="phosphorus" required><br>

      <label for="potassium">Potassium (K):</label>
      <input type="number" id="potassium" name="potassium" required><br>

      <label for="temperature">Temperature (°C):</label>
      <input type="number" step="0.1" id="temperature" name="temperature" required><br>

      <label for="humidity">Humidity (%):</label>
      <input type="number" step="0.1" id="humidity" name="humidity" required><br>

      <label for="ph">pH:</label>
      <input type="number" step="0.1" id="ph" name="ph" required><br>

      <label for="rainfall">Rainfall (mm):</label>
      <input type="number" step="0.1" id="rainfall" name="rainfall" required><br>

      <button type="submit" id="submit-btn">Get Recommended Crop</button>
    </form>
    <div id="result"></div>
  </div>
      <?php include 'footer.php'; ?>

  <script src="app.js"></script>
</body>
</html>