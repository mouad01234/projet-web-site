<?php
include "conn.php";

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
        }

        $photoPath = file_exists($targetDir . $photoName) ? $targetDir . $photoName : 'uploads/default.png';

        try {
            $stmt = $conn->prepare("INSERT INTO users (username, password, photo, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $photoPath, $role]);
            header("Location: crud.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    } else {
        echo "Champs manquants.";
    }
}
?><!DOCTYPE html><html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f9f9f9, #dbeafe);
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            width: 450px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #1e3a8a;
            margin-bottom: 30px;
        }
        label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }
        input, select {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            font-size: 15px;
        }
        button {
            width: 100%;
            padding: 14px;
            background: #2563EB;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #1d4ed8;
        }
        #preview {
            display: none;
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 15px;
            border: 2px solid #ddd;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #2563EB;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="card">
    <h2>Ajouter un utilisateur</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Nom d'utilisateur :</label>
        <input type="text" name="username" required><label>Mot de passe :</label>
    <input type="password" name="password" required>

    <label>Photo de profil :</label>
    <input type="file" name="photo" accept="image/*">
    <img id="preview" src="#" alt="Aperçu photo">

    <label>Rôle :</label>
    <select name="role" required>
    <option value="">-- Choisissez --</option>
    <?php
    $stmt = $conn->query("SELECT role_name FROM roles");
    while ($row = $stmt->fetch()) {
        echo "<option value='" . htmlspecialchars($row['role_name']) . "'>" . htmlspecialchars($row['role_name']) . "</option>";
    }
    ?>
</select>

    <button type="submit">Ajouter</button>
</form>
<a href="crud.php" class="back-link">Retour</a>

</div><script>
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
</script></body>
</html>