

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    h1 {
        color: #333;
    }

    form {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
    }

    label {
        font-weight: bold;
        color: #555;
    }

    input, textarea, button {
        width: 100%;
        margin-top: 8px;
        margin-bottom: 16px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }

    button:hover {
        background-color: #0056b3;
    }
</style><?php
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'] ?? '';
    $productPrice = $_POST['product_price'] ?? '';
    $productDescription = $_POST['product_description'] ?? '';
    $imageName = '';

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $imageName = basename($_FILES['product_image']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $imageName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        move_uploaded_file($_FILES['product_image']['tmp_name'], $targetFile);
    }

    $stmt = $conn->prepare("INSERT INTO produits (nom, prix, description, image) VALUES (:nom, :prix, :description, :image)");
    $stmt->execute([
        ':nom' => $productName,
        ':prix' => $productPrice,
        ':description' => $productDescription,
        ':image' => $imageName
    ]);

    echo "Produit ajouté avec succès !";
}
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Produit avec Image</title>
    <style>

    </style>
</head>
<body>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label>Nom du produit:</label>
        <input type="text" name="product_name" required>

        <label>Prix:</label>
        <input type="number" name="product_price" step="0.01" required>

        <label>Description:</label>
        <textarea name="product_description" rows="4" required></textarea>

        <label>Image:</label>
        <input type="file" name="product_image" accept="image/*" required>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>