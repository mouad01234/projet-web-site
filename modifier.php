<?php
include "conn.php";
session_start();
  
if (isset($_SESSION['username']) ) {  
    $username = $_SESSION['username'];  


    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");  
    $stmt->execute([$username]);  
    $user = $stmt->fetch();  

    $photoPath = (!empty($user['photo']) && file_exists($user['photo']))  
        ? $user['photo']  
        : 'uploads/default.png';  
} else {
    $photoPath = '';  
    $user = ['username' => ''];
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'];
    $newPassword = $_POST['password'];
    $role = $user['role'];
    $photoPath = $user['photo'];

    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    } else {
        $hashedPassword = $user['password'];
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $photoName = basename($_FILES['photo']['name']);
        $tmpName = $_FILES['photo']['tmp_name'];
        move_uploaded_file($tmpName, "uploads/" . $photoName);
        $photoPath = "uploads/" . $photoName;
    }

    $update = $conn->prepare("UPDATE users SET username = ?, password = ?, photo = ? WHERE username = ?");
    if ($update->execute([$newUsername, $hashedPassword, $photoPath, $username])) {
        $_SESSION['username'] = $newUsername;
        $success = "Profil modifié avec succès.";
        header("Refresh: 1");
    } else {
        $error = "Erreur lors de la mise à jour.";
    }
}

if(isset($_POST['search'])){
    $search = $_POST['search'];
    $stmt = $conn->prepare("SELECT * from pr where name like ?");
    $stmt->execute("%$search%");
    $reesults = $stmt->fetchall();
    foreach($results as $result){
        '<p>'.$result['name'].'</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #667eea, #764ba2);
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            width: 420px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
            position: relative;
            right: 60PX;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-weight: 600;
        }

        label {
            font-weight: 500;
            color: #555;
            margin-bottom: 6px;
            display: block;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #6c5ce7;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #5a4de5;
        }

        .feedback {
            text-align: center;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .success {
            color: green;
        }

        .error {
            color: red;
        }

        #preview {
            display: block;
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            margin: 10px auto;
            border: 2px solid #ddd;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 20px;
          position: relative;
          right: 400PX;
          bottom: 270PX;
        }

        .header img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #2563eb;
        }

        .header-info h2 {
            margin: 0;
            font-size: 23px;
            font-weight: 700;
            color:rgb(0, 0, 0);
        }

        .header-info p {
            margin: 5px 0 0;
            font-weight: 500;
            color: #64748b;
        }
    </style>
</head>
<body>
<div class="header">
<a href="dashboard.php"><img src="<?= htmlspecialchars($photoPath) ?>" alt="Photo" style="width: 50px; height: 50px;"></a>
    
    </div>
<div class="card">
    <h2>Modifier votre profil</h2>

    <?php if ($success): ?>
        <div class="feedback success"><?= htmlspecialchars($success) ?></div>
    <?php elseif ($error): ?>
        <div class="feedback error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

        <label>Nouveau mot de passe (laissez vide si inchangé) :</label>
        <input type="password" name="password" placeholder="Nouveau mot de passe">

        <label>Photo de profil :</label>
        <input type="file" name="photo" accept="image/*" onchange="previewPhoto(event)">
        <img id="preview" src="<?= file_exists($user['photo']) ? htmlspecialchars($user['photo']) : 'uploads/default.png' ?>" alt="Photo actuelle">

        <button type="submit">Enregistrer les modifications</button>
    </form>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}
</script>

</body>
</html>