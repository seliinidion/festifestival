<?php
require 'db.php'; 
$user_id = 1;      

$sql = "SELECT * FROM watched ORDER BY time ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fi">
<head>
  <meta charset="UTF-8" />
  <title>Katsotut keikat</title>
  <link rel="stylesheet" href="style.css" />

      <style>
@import url('https://fonts.googleapis.com/css2?family=Michroma&display=swap');
</style>

</head>
<body>
  <main class="content">

    <h1>KATSOTUT KEIKAT</h1>

    <nav class="navMenu">
      <a href="watchlist.php">Palaa takaisin</a>
      <div class="dot"></div>
    </nav>

    <ul>
      <?php if ($result->num_rows === 0): ?>
        <li>Et ole vielä katsonut yhtään artistia.</li>
      <?php else: ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <li class="watchlist-artist">
            <?= htmlspecialchars($row['artist_name']) ?> 
            (<?= htmlspecialchars($row['stage']) ?> klo <?= htmlspecialchars($row['time']) ?>)

         
            <form method="post" action="remove_watched.php" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" class="remove-button">Poista</button>
            </form>
          </li>
        <?php endwhile; ?>
      <?php endif; ?>
    </ul>

  <footer class="site-footer">
    <p><strong>FESTIFESTIVAL</strong></p>
    <p>Ota yhteyttä: <a href="mailto:info@festifestival.com">info@festifestival.com</a></p>
    <p>Seuraa meitä: 
      <a href="#">Facebook</a> | 
      <a href="#">Instagram</a> | 
      <a href="#">Twitter</a>
    </p>
    <p class="copyright">&copy; 2025 Festifestival. Kaikki oikeudet pidätetään.</p>
  </footer>

  </main>
</body>
</html>
