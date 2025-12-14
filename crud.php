<?php
session_start();
include "conn.php";

$connectedUser = null;
$photoPath = "uploads/default.png";

if (isset($_SESSION['username'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $connectedUser = $stmt->fetch();

    if (!empty($connectedUser['photo']) && file_exists($connectedUser['photo'])) {
        $photoPath = $connectedUser['photo'];
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: crud.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users ORDER BY id DESC");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="page.css">
</head>

<body>
    <div class="header">
        <a href="dashboard.php">
            <img src="<?= htmlspecialchars($photoPath) ?>" alt="User">
        </a>
    </div>

    <div class="container">
        <h2>Gestion des utilisateurs</h2>

        <div class="top-bar">
            <a href="ajouter_user.php" class="add-btn">+ Ajouter un utilisateur</a>

            <div class="search-box">
                <input type="text" id="search" placeholder="Rechercher...">
                <button><i class="fa fa-search"></i></button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Rôle</th>
                    <th>Photo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $u): ?>
                    <tr>
                        <td><?= htmlspecialchars($u['id']) ?></td>
                        <td><?= htmlspecialchars($u['username']) ?></td>
                        <td><?= htmlspecialchars($u['role']) ?></td>
                        <td>
                            <img class="user-img"
                                 src="<?= !empty($u['photo']) ? htmlspecialchars($u['photo']) : 'uploads/default.png' ?>">
                        </td>
                        <td>
                            <a href="crud.php?delete=<?= $u['id'] ?>"
                               class="btn delete"
                               onclick="return confirm('Supprimer cet utilisateur ?')">
                               Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById("search").addEventListener("keyup", function(){
            let val = this.value.toLowerCase();
            document.querySelectorAll("tbody tr").forEach(row=>{
                let name = row.children[1].innerText.toLowerCase();
                row.style.display = name.includes(val) ? "" : "none";
            });
        });
    </script>
</body>
</html>