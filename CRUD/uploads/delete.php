<?php
include 'config/database.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT cover_image FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

// Delete the image file
unlink("uploads/" . $book['cover_image']);

// Delete the record
$stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit();
?>