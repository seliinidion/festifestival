<?php
$mysqli = new mysqli("localhost", "root", "", "festari_test");

// Käyttäjäkohtainen tunniste cookiella
if(!isset($_COOKIE['user_id'])){
    $user_id = bin2hex(random_bytes(8));
    setcookie('user_id', $user_id, time()+60*60*24*365);
} else {
    $user_id = $_COOKIE['user_id'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['artist_id'])) {
    $artist_id = (int)$_POST['artist_id'];

    // Hae artistin tiedot
    $stmt = $mysqli->prepare("SELECT a.id AS artist_id, a.name AS artist_name, t.stage, t.time 
                              FROM artist a
                              JOIN timetable t ON a.id = t.artist_id
                              WHERE a.id = ?");
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $artist = $result->fetch_assoc();

    if ($artist) {
        // Lisää WATCHED-listalle käyttäjäkohtaisesti
        $stmt = $mysqli->prepare("INSERT INTO watched (user_id, artist_id, artist_name, stage, time) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sisss", $user_id, $artist['artist_id'], $artist['artist_name'], $artist['stage'], $artist['time']);
        $stmt->execute();

        // Poista watchlistilta vain tämän käyttäjän lista
        $stmt = $mysqli->prepare("DELETE FROM watchlist WHERE artist_id = ? AND user_id = ?");
        $stmt->bind_param("is", $artist_id, $user_id);
        $stmt->execute();
    }
}

header("Location: watchlist.php");
exit();
?>
