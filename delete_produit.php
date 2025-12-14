<?php
include "conn.php";
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM produits WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: gerer_pro.php?delete=success");
        exit();
    } else {
        echo "Erreur lors de la suppression du produit.";
    }
} else {
    echo "ID de produit non spécifié.";
}
?>