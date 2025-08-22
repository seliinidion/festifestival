<?php
require 'db.php';

// Käyttäjä tunniste cookieltä, tai oletus 1 jos cookie puuttuu
$user_id = $_COOKIE['user_id'] ?? 1;

// Check if ID parametri on annettu
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $artist_id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE FROM watchlist WHERE user_id = ? AND artist_id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $user_id, $artist_id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();

header("Location: watchlist.php");
exit;
?>
