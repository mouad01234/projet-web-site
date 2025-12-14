<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include 'conn.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID de produit invalide');
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM produits WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$produit = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$produit) {
    die('Produit introuvable');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Produit</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>

        .container { max-width: 500px; margin: 50px auto; }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">Modifier Produit <?= htmlspecialchars($produit['nom']) ?></h1>
    <form action="gerer_pro.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($produit['id']) ?>">

        <div class="form-group">
            <label for="product_name">Nom du produit :</label>
            <input type="text" id="product_name" name="product_name" class="form-control" required
                   value="<?= htmlspecialchars($produit['nom']) ?>">
        </div>

        <div class="form-group">
            <label for="product_price">Prix du produit :</label>
            <input type="number" id="product_price" name="product_price" step="0.01" class="form-control" required
                   value="<?= htmlspecialchars($produit['prix']) ?>">
        </div>

        <div class="form-group">
            <label for="product_description">Description :</label>
            <textarea id="product_description" name="product_description" class="form-control"><?= htmlspecialchars($produit['description']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="product_image">Changer image :</label>
            <input type="file" name="product_image" accept="image/*" class="form-control">
            <?php if (!empty($produit['image'])): ?>
                <p>Image actuelle : <img src="<?= htmlspecialchars($produit['image']) ?>" width="100"></p>
            <?php endif; ?>
        </div>  

        <button type="submit" class="btn btn-primary btn-block">Enregistrer</button>
    </form>
</div>
</body>
</html>