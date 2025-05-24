<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch record to get photo path
    $sql = 'SELECT pic FROM info WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $info = $stmt->fetch();

    if ($info) {
        // Delete photo if it exists
        if ($info['pic'] && file_exists($info['pic'])) {
            unlink($info['pic']);
        }

        // Delete record
        $sql = 'DELETE FROM info WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        try {
            $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            die('Database error: ' . $e->getMessage());
        }
    }
    header('Location: main.php');
    exit;
} 

?>