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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['product_id'];
    $nom = $_POST['product_name'];
    $prix = $_POST['product_price'];
    $description = $_POST['product_description'];

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp = $_FILES['product_image']['tmp_name'];
        $image_path = 'uploads/' . time() . '_' . basename($image_name);

        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['product_image']['type'], $allowed_types)) {
            if (move_uploaded_file($image_tmp, $image_path)) {
                $stmt = $conn->prepare("UPDATE produits SET nom = :nom, prix = :prix, description = :description, image = :image WHERE id = :id");
                $stmt->bindParam(':nom', $nom);
                $stmt->bindParam(':prix', $prix);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':image', $image_path);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            } else {
                echo "Erreur lors de l'upload de l'image.";
                exit();
            }
        } else {
            echo "Format d'image non autorisé.";
            exit();
        }
    } else {
        $stmt = $conn->prepare("UPDATE produits SET nom = :nom, prix = :prix, description = :description WHERE id = :id");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prix', $prix);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    }

    if ($stmt->execute()) {
        header("Location: gerer_pro.php?modif=success");
        exit();
    } else {
        echo "Erreur lors de la modification.";
    }
}

$stmt = $conn->prepare("SELECT * FROM produits");
$stmt->execute();
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Produits</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2.5rem;
            color: #343a40;
            margin-bottom: 20px;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .table {
            margin-top: 20px;
        }

        .table th {
            background-color: #343a40;
            color: #ffffff;
            text-align: center;
        }

        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        img {
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 20px;
         
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
            color: #1e40af;
        }

        .header-info p {
            margin: 5px 0 0;
            font-weight: 500;
            color: #64748b;
        }
        .input-box {
     border-radius:  25px;
     border: none;
     font-size: 16px;
     outline: none;
     margin-right: 14px;
     text-align: center;
     background-color: #f7f7f7;
     position: relative;
     left: 700PX;
     bottom: 1900PX;
     height: 40PX;
   }
   
  
.search-box{
    display:flex;
    justify-content:center;
    margin:20px 0;
    position: relative;
    left: 500PX;
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
    </style>
</head>
<body>


<div class="header">
<a href="dashboard.php"><img src="<?= htmlspecialchars($photoPath) ?>" alt="Photo" style="width: 50px; height: 50px;"></a>
         <div class="search-box">
    <input type="text" id="search" placeholder="Search product">
    <button><i class="fa fa-search"></i></button>
</div> 
    </div>
   
<div class="container">
    <h1 class="text-center">Gérer les Produits</h1>
    <a href="add_product.php" class="btn btn-success mb-3">Ajouter un Produit</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prix</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produits as $produit): ?>
                <tr>
                    <td><?= htmlspecialchars($produit['id']) ?></td>
                    <td><?= htmlspecialchars($produit['nom']) ?></td>
                    <td><span class=" p-2"><?= htmlspecialchars($produit['prix']) ?> $</span></td>
                    <td><img src="<?= htmlspecialchars($produit['image']) ?>" alt="Image" width="50"></td>
                    <td>
                        <a href="edit_produit.php?id=<?= $produit['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                        <a href="delete_produit.php?id=<?= $produit['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

const searchInput = document.getElementById('search');
const searchButton = document.querySelector('.search-box button');
const tableRows = document.querySelectorAll('tbody tr');

function filterTable() {
    const filter = searchInput.value.toLowerCase();
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
}

searchInput.addEventListener('keyup', filterTable);

searchButton.addEventListener('click', filterTable);

</script>
</body>
</html>