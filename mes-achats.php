<?php
session_start();
include "conn.php";

$stmt = $conn->prepare("
SELECT o.*, p.nom, p.image
FROM orders o
JOIN produits p ON o.produit_id = p.id
WHERE o.username = ?
ORDER BY o.id DESC
");
$stmt->execute([$_SESSION['username']]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<title>Mes achats</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="background:#f5f7fb">

<div class="container mt-5">
<h2 class="text-center mb-4">🛒 Mes achats</h2>

<?php foreach($orders as $o): ?>
<div class="card mb-3">
  <div class="row no-gutters">
    <div class="col-md-4">
      <img src="<?= $o['image'] ?>" class="img-fluid">
    </div>
    <div class="col-md-8 p-3">
      <h5><?= $o['nom'] ?></h5>
      <p>Quantité: <?= $o['quantite'] ?></p>
      <p>Taille: <?= $o['taille'] ?></p>
      <strong><?= $o['prix'] ?> DH</strong><br>
      <span class="badge badge-success">Achat confirmé</span>
    </div>
  </div>
</div>
<?php endforeach; ?>

<a href="index5.php" class="btn btn-primary">Retour au store</a>
</div>

</body>
</html>