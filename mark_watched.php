<?php
$mysqli = new mysqli("localhost", "root", "", "festari_test");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['artist_id'])) {
    $artist_id = (int)$_POST['artist_id'];

    // GET ARTIST INFO
    $stmt = $mysqli->prepare("SELECT a.id AS artist_id, a.name AS artist_name, t.stage, t.time 
                              FROM artist a
                              JOIN timetable t ON a.id = t.artist_id
                              WHERE a.id = ?");
    $stmt->bind_param("i", $artist_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $artist = $result->fetch_assoc();

    if ($artist) {
        // ADD TO WATCHED LIST
        $stmt = $mysqli->prepare("INSERT INTO watched (artist_id, artist_name, stage, time) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $artist['artist_id'], $artist['artist_name'], $artist['stage'], $artist['time']);
        $stmt->execute();

        // REMOVE FROM WATCHED LIST
        $stmt = $mysqli->prepare("DELETE FROM watchlist WHERE artist_id = ?");
        $stmt->bind_param("i", $artist_id);
        $stmt->execute();
    }
}

header("Location: watchlist.php");
exit();
?>
