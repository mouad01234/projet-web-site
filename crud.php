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
     <style>body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
}


.header {
    display: flex;
    justify-content: flex-end;
    padding: 20px 40px;
}

.header img {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #2563eb;
    position: relative;
    right: 1260px;
}

.container {
    max-width: 1100px;
    margin: 40px auto;
    background: #ffffff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.container h2 {
    font-size: 2.2rem;
    color: #343a40;
    margin-bottom: 25px;
}

.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.add-btn {
    background-color: #28a745;
    color: #fff;
    padding: 10px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.add-btn:hover {
    background-color: #218838;
}

.search-box {
    display: flex;
    align-items: center;
}

.search-box input {
    border-radius: 20px;
    border: none;
    padding: 8px 15px;
    width: 180px;
    outline: none;
    background-color: #919191ff;
    height :30PX
}

.search-box button {
    margin-left: 5px;
    border: none;
    border-radius: 20px;
    background: #000;
    color: #fff;
    padding: 8px 15px;
    cursor: pointer;
    width: 50PX;
    height: 40px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

thead th {
    background-color: #343a40;
    color: #ffffff;
    padding: 12px;
    text-align: center;
}

tbody td {
    text-align: center;
    vertical-align: middle;
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
}

tbody tr:hover {
    background-color: #f1f5f9;
}


.user-img {
    width: 55px;
    height: 55px;
    border-radius: 5px;
    object-fit: cover;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    
}

.btn {
    padding: 7px 14px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.btn.delete {
    background-color: #dc3545;
    color: #fff;
    width: 100PX;
    height: 20PX;
}

.btn.delete:hover {
    background-color: #c82333;
}</style>
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
