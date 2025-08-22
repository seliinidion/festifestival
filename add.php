<?php
require 'db.php';

// Käyttäjäkohtainen tunniste cookiella
if(!isset($_COOKIE['user_id'])){
    $user_id = bin2hex(random_bytes(8));
    setcookie('user_id', $user_id, time()+60*60*24*365);
} else {
    $user_id = $_COOKIE['user_id'];
}

$id = intval($_GET['id']); // artist ID

if ($id) {
    // Tarkista onko artisti jo listalla tälle käyttäjälle
    $check = $conn->prepare("SELECT id FROM watchlist WHERE user_id = ? AND artist_id = ?");
    $check->bind_param("si", $user_id, $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $check->close();

        // Tarkista onko artisti jo katsotuissa tälle käyttäjälle
        $check_watched = $conn->prepare("SELECT id FROM watched WHERE user_id = ? AND artist_id = ?");
        $check_watched->bind_param("si", $user_id, $id);
        $check_watched->execute();
        $check_watched->store_result();

        if ($check_watched->num_rows === 0) {
            // Lisää watchlistille
            $stmt = $conn->prepare("INSERT INTO watchlist (user_id, artist_id) VALUES (?, ?)");
            $stmt->bind_param("si", $user_id, $id);
            $stmt->execute();
            $stmt->close();
        }
        $check_watched->close();
    } else {
        $check->close();
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode(['success' => true]);
exit;
?>
