<?php
include "conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
        $username = $_POST['username'];
        $plainPassword = $_POST['password'];
        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $targetDir = "uploads/";
        $photoName = "default.png";

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $photoName = basename($_FILES['photo']['name']);
            $tmpName = $_FILES['photo']['tmp_name'];
            move_uploaded_file($tmpName, $targetDir . $photoName);
       

        $photoPath = file_exists($targetDir . $photoName) ? $targetDir . $photoName : 'uploads/default.png';

       }
        try {
            $stmt = $conn->prepare("INSERT INTO users (username, password, photo, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $photoPath, $role]);

            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    } else {
        echo "Champs manquants.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un compte</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    .card {
        background: #fff;
        padding: 35px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 450px;
        border-top: 6px solid #ffb300;
    }

    .card h2 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: bold;
        color: #ff8f00;
    }

    .card label {
        font-weight: 500;
        margin-top: 10px;
        display: block;
    }

    .card input,
    .card select {
        width: 100%;
        padding: 12px;
        margin-top: 5px;
        margin-bottom: 15px;
        border-radius: 6px;
        border: 1px solid #ccc;
        font-size: 15px;
    }

    .card input:focus,
    .card select:focus {
        border-color: #ffb74d;
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(255,183,77,0.3);
    }

    .card button {
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

    .card button:hover {
        background: linear-gradient(to right, #fb8c00, #ffb300);
    }

    #preview {
        width: 100px;
        height: 100px;
        object-fit: cover;
        margin-top: 10px;
        display: none;
        border-radius: 50%;
        border: 2px solid #ffb300;
    }
</style>
</head>
<body style="background: linear-gradient(to bottom right, #fff7e6, #ffe0b2); font-family: 'Segoe UI', sans-serif; height: 100vh; display: flex; justify-content: center; align-items: center;">


<div class="card">
    <h2>Créer un compte</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Username :</label>
        <input type="text" name="username" placeholder="Username" > 

        <label>Mot de passe :</label>
        <input type="password" name="password" placeholder="Mot de passe" >

        <label>Photo de profil :</label>
        <input type="file" name="photo" accept="image/*">
        <img id="preview" src="#" alt="Aperçu photo">

        <label>Rôle :</label>
        <select name="role" required>
            <option value="">-- Choisissez --</option>
            <option value="client">Client</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">S'inscrire</button>
    </form>
</div>

<script>
 
    document.querySelector('input[name="photo"]').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
    
</script>

</body>
</html>