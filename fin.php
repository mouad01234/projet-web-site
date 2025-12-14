<?php
session_start();
include "conn.php";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("
INSERT INTO orders (username, produit_id, quantite, taille, prix)
VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $_SESSION['username'],
    $_POST['produit_id'],
    $_POST['quantite'],
    $_POST['taille'],
    $_POST['prix']
]);

header("Location: mes-achats.php");
exit;