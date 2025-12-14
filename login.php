<?php
session_start();
include "conn.php";



$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index5.php");
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
    .login-box {
        background: #fff;
        padding: 35px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
        border-top: 6px solid #ffb300;
    }

    .login-box h2 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: bold;
        color: #ff8f00;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 15px;
        position: relative;
        right: 10PX;
    }

    .login-box input:focus {
        border-color: #ffb74d;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(255,183,77,0.3);
    }

    .login-box button {
        width: 100%;
        padding: 12px;
        background: linear-gradient(to right, #ff9800, #ffc107);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
    }

    .login-box button:hover {
        background: linear-gradient(to right, #fb8c00, #ffb300);
    }

    .login-box a {
        color: #ff6f00;
        text-decoration: none;
        font-weight: 500;
    }

    .login-box a:hover {
        text-decoration: underline;
    }

    .error {
        color: red;
        text-align: center;
        font-size: 14px;
        margin-bottom: 15px;
        font-weight: 500;
    }
</style>
</head>
<body style="background: linear-gradient(to bottom right, #fff7e6, #ffe0b2); font-family: 'Segoe UI', sans-serif; height: 100vh; display: flex; justify-content: center; align-items: center;">

<div class="login-box">
    <h2>Connexion</h2>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <input type="text" name="username" placeholder="Username" >
        <input type="password" name="password" placeholder="Mot de passe" required >
        <button type="submit">Se connecter</button>
        <p style="text-align:center; margin-top: 15px;">
    Pas de compte ? <a href="users.php">Créer un compte</a>
</p>
    </form>
</div>

</body>
</html>