<?php
session_start();
include "conn.php";

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit;
}

if(!isset($_GET['id'])){
    header("Location: index5.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM produits WHERE id=?");
$stmt->execute([$_GET['id']]);
$product = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($product['nom']) ?></title><style>
body{
    margin:0;
    min-height:100vh;
    font-family:'Segoe UI', Tahoma, sans-serif;
    background:radial-gradient(circle at top,#fff3e6,#fde0c9,#f7c59f);
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    max-width:520px;
    width:90%;
    background:rgba(255,255,255,.95);
    padding:35px;
    border-radius:22px;
    text-align:center;
    backdrop-filter:blur(10px);
    box-shadow:
        0 20px 40px rgba(0,0,0,.25),
        inset 0 0 0 1px rgba(255,255,255,.4);
    animation:pop .6s ease;
}

@keyframes pop{
    from{transform:scale(.9);opacity:0}
    to{transform:scale(1);opacity:1}
}

.card img{
    width: 300px;;
    height:150px;
    object-fit:cover;
    border-radius:18px;
    margin-bottom:15px;
    box-shadow:0 12px 25px rgba(0,0,0,.25);
}

.card h2{
    margin:10px 0 5px;
    font-size:28px;
    letter-spacing:.5px;
}

.card h4{
    margin:5px 0 25px;
    font-size:26px;
    color:#f41212;
    text-shadow:0 2px 6px rgba(244,18,18,.3);
}
label{
    display:block;
    text-align:left;
    margin-top:15px;
    font-weight:600;
}

input, select{
    width:100%;
    padding:14px;
    margin-top:6px;
    border-radius:14px;
    border:1px solid #ddd;
    font-size:16px;
    transition:.3s;
    background:#fff;
}

input:focus, select:focus{
    outline:none;
    border-color:#ffa048;
    box-shadow:0 0 0 4px rgba(255,160,72,.25);
}


button{
    margin-top:30px;
    padding:16px;
    width:100%;
    border:none;
    border-radius:16px;
    font-size:19px;
    font-weight:bold;
    color:#fff;
    cursor:pointer;
    background:linear-gradient(135deg,#ffa048,#f68456);
    box-shadow:0 12px 25px rgba(246,132,86,.45);
    transition:.3s;
}

button:hover{
    transform:translateY(-3px);
    box-shadow:0 18px 35px rgba(246,132,86,.6);
}

button:active{
    transform:scale(.98);
}
</style>
</head>

<body>

<div class="card">
    <img src="<?= htmlspecialchars($product['image']) ?>">
    <h2><?= htmlspecialchars($product['nom']) ?></h2>
    <h4 style="color:red"><?= $product['prix'] ?> DH</h4>

    <form action="fin.php" method="POST">
        <input type="hidden" name="produit_id" value="<?= $product['id'] ?>">
        <input type="hidden" name="prix" value="<?= $product['prix'] ?>">

        <label>Quantité</label>
        <input type="number" name="quantite" value="1" min="1" class="form-control mb-2">

        <label>Taille</label>
        <select name="taille" class="form-control mb-3">
            <option>S</option>
            <option>M</option>
            <option>L</option>
            <option>XL</option>
        </select>

        <button type="submit">Confirmer l'achat</button>
    </form>
</div>

</body>
</html>