<?php
$mysqli = new mysqli("localhost", "root", "", "festari_test");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    $stmt = $mysqli->prepare("DELETE FROM watched WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: watched.php");
exit();
?>
