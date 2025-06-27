<?php
require 'db.php';

$id = intval($_GET['id']); // artist ID
$user_id = 1; // Hardcode user (later u can change $_SESSION['user_id'])

if ($id) {
    // CHECK IF ARTIST ALREADY AT LIST
    $check = $conn->prepare("SELECT id FROM watchlist WHERE user_id = ? AND artist_id = ?");
    $check->bind_param("ii", $user_id, $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        $check->close();

        // CHECK IF ARTISTS ALREADY IN WATCHED
        $check_watched = $conn->prepare("SELECT id FROM watched WHERE artist_id = ?");
        $check_watched->bind_param("i", $id);
        $check_watched->execute();
        $check_watched->store_result();

        if ($check_watched->num_rows === 0) {
            // ADD TO LIST 
            $stmt = $conn->prepare("INSERT INTO watchlist (user_id, artist_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $id);
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
