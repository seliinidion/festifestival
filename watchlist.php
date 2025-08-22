<?php
require 'db.php';

// Käyttäjäkohtainen tunniste cookiella
if(!isset($_COOKIE['user_id'])){
    $user_id = bin2hex(random_bytes(8));
    setcookie('user_id', $user_id, time()+60*60*24*365);
} else {
    $user_id = $_COOKIE['user_id'];
}

// Hae käyttäjän watchlist
$sql = "SELECT a.id AS artist_id, a.name, t.stage, t.time
        FROM watchlist w
        JOIN artist a ON w.artist_id = a.id
        JOIN timetable t ON t.artist_id = a.id
        WHERE w.user_id = ?
          AND a.id NOT IN (SELECT artist_id FROM watched WHERE user_id = ?)
        ORDER BY t.time ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fi">
<head>
  <meta charset="UTF-8" />
  <title>Oma lista</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Michroma&display=swap');
  </style>
</head>
<body>
  <main class="content">

    <h1>TO-WATCH LISTA</h1>

    <nav class="navMenu">
      <a href="schedule.php">Palaa takaisin ohjelmistoon</a>
      <a href="watched.php">Katsotut keikat</a>
      <div class="dot"></div>
    </nav>

    <ul>
      <?php if ($result->num_rows === 0): ?>
        <li>Sinulla ei ole vielä yhtään artistia listalla.</li>
      <?php else: ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <li class="watchlist-artist">
            <?= htmlspecialchars($row['name']) ?> 
            (<?= htmlspecialchars($row['stage']) ?> klo <?= htmlspecialchars($row['time']) ?>)
            <a class="remove-button" href="remove.php?id=<?= $row['artist_id'] ?>">Poista</a>

            <form method="post" action="mark_watched.php" style="display:inline;">
              <input type="hidden" name="artist_id" value="<?= $row['artist_id'] ?>">
              <button type="submit" class="watched-button">🗸</button>
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
