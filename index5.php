<?php
session_start();
include "conn.php";

$user = null;
$photoPath = "uploads/default.png";

if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch();

    if (!empty($user['photo']) && file_exists($user['photo'])) {
        $photoPath = $user['photo'];
    }
}

$isLogged = isset($_SESSION['username']);
$isAdmin  = ($isLogged && $_SESSION['role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body{
    background: rgba(243, 234, 189, 1);
    font-family: Arial, sans-serif;
    margin:0;
}

.main-navbar{
    width: 100%;
    height: 70px;
    background: rgb(255, 249, 220);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 40px;
    position: relative;
    bottom: 4px;
    z-index: 1000;
}

.nav-links{
    display:flex;
    gap:40px;
    position: relative;
    left:440px;
}

.nav-links a{
    color:#000;
    font-size:20px;
    text-decoration:none;
    font-family:"poppins";
}

.nav-links a:hover{
    color:#f68456;
}

.user img{
    width:55px;
    height:55px;
    border-radius:50%;
    border:2px solid #2563eb;
    position: relative;
    right: 1240px;
}

.search-box{
    display:flex;
    justify-content:center;
    margin:30px 0 20px;
}

.search-box input{
    border-radius:20px;
    border:none;
    padding:8px 15px;
    width:220px;
}

.search-box button{
    margin-left:5px;
    border:none;
    border-radius:20px;
    background:#000;
    color:#fff;
    padding:8px 15px;
}

.product-section{
    padding:40px 0 60px;
    background: rgb(255, 249, 220);
}

.product-card{
    background:#fff;
    border-radius:8px;
    padding:20px;
    text-align:center;
    box-shadow:0 4px 8px rgba(0,0,0,.1);
    transition:.3s;
    height:100%;
}

.product-card:hover{
    transform:translateY(-10px);
    box-shadow:0 8px 16px rgba(0,0,0,.2);
}

.product-card img{
    width:100%;
    height:250px;
    object-fit:cover;
    border-radius:8px;
}

.product-price{
    color:#f41212;
    font-size:20px;
    font-weight:bold;
}

.btn-primary{
    background:#ffa048;
    border:none;
}

.btn-primary:hover{
    background:#1d36f7;
}

.btn-secondary{
    background:#ccc;
    border:none;
    cursor: not-allowed;
}

footer{
    background:#525252;
    color:#fff;
    text-align:center;
    padding:15px 0;
}
</style>
</head>

<body>

<nav class="main-navbar">
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="index 2.php">À propos</a>
        <a href="index 4.php">Services</a>
        <a href="index 3.php">Contact</a>
    </div>

    <div class="user">
        <a href="dashboard.php">
            <?php if ($isLogged) { ?>
                <img src="<?= $photoPath ?>" class="profile-img">
            <?php } ?>
        </a>
    </div>
</nav>

<div class="search-box">
    <input type="text" id="search" placeholder="Search product">
    <button><i class="fa fa-search"></i></button>
</div>

<section class="product-section">
<div class="container">
<div class="row">

<?php
$stmt = $conn->prepare("SELECT * FROM produits ORDER BY id DESC");
$stmt->execute();
$products = $stmt->fetchAll();

foreach($products as $p) {
?>
<div class="col-lg-4 col-md-6 col-sm-12 mb-4 product-item">
    <div class="product-card">
        <img src="<?= htmlspecialchars($p['image']) ?>" alt="">
        <h4 class="mt-3"><?= htmlspecialchars($p['nom']) ?></h4>
        <p class="product-price"><?= htmlspecialchars($p['prix']) ?> $</p>

        <?php
        if (!$isLogged) {
            echo '<a href="login.php" class="btn btn-primary"> buy</a>';
        } elseif ($isAdmin) {
            echo '<button class="btn btn-secondary" disabled>Admin view only</button>';
        } else {
            echo '<a href="produits.php?id='.$p['id'].'" class="btn btn-primary">Buy</a>';
        }
        ?>
    </div>
</div>
<?php
}
?>

</div>
</div>
</section>

<footer>
    &copy; 2025 Boutique de vêtements
</footer>

<script>
document.getElementById("search").addEventListener("keyup", function(){
    let val = this.value.toLowerCase();
    document.querySelectorAll(".product-item").forEach(item=>{
        let title = item.querySelector("h4").innerText.toLowerCase();
        item.style.display = title.includes(val) ? "block" : "none";
    });
});
</script>

</body>
</html>