<?php
include "conn.php";
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];


$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

$isAdmin = ($role == 'admin');

$photoPath = (!empty($user['photo']) && file_exists($user['photo']))
    ? $user['photo']
    : 'uploads/default.png';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background: #f1f5f9;
            color: #1e293b;
        }

        .dashboard {
            max-width: 800px;
            margin: 60px auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        }

        .header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .header img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #2563eb;
        }

        .header-info h2 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            color: #1e40af;
        }

        .header-info p {
            margin: 5px 0 0;
            font-weight: 500;
            color: #64748b;
        }

        .section {
            margin-top: 30px;
        }

        .section h3 {
            font-size: 20px;
            margin-bottom: 20px;
            color: #0f172a;
        }

        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 15px;
            transition: 0.3s;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .card:hover {
            background: #e0f2fe;
            transform: translateY(-2px);
        }

        .logout {
            margin-top: 30px;
            text-align: center;
        }

        .logout a {
            background: #ef4444;
            color: white;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .logout a:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <div class="header">
        <img src="<?= htmlspecialchars($photoPath) ?>" alt="Photo">
        <div class="header-info">
            <h2>Bienvenue, <?= htmlspecialchars($user['username']) ?></h2>
            <p>Rôle : <?= ucfirst(htmlspecialchars($role)) ?></p>
        </div>
    </div>

    <?php if ($isAdmin): ?>
        <div class="section">
            <h3>Tableau de bord Administrateur</h3>
            <a href="crud.php" class="card">Gestion des utilisateurs</a>
            <a href="gerer_pro.php" class="card">gérer les produits</a>
            <a href="messages.php" class="card">voir les messages</a>
        </div>
    <?php else: ?>
        <div class="section">
            <h3>Tableau de bord Client</h3>
            <a href="modifier.php" class="card">Modifier votre profil</a>
            <a href="mes-achats.php" class="card">Historique des commandes</a>
        </div>
    <?php endif; ?>

    <div class="logout">
        <a href="logout.php">Se déconnecter</a>
    </div>
</div>

</body>
</html>