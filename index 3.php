<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Contactez-nous</title>
  <link rel="stylesheet" href="page.css">
</head>
<body>
<?php
include 'conn.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (nom, email, message) VALUES (:nom, :email, :message)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':message' => $message
    ]);

    echo "Message envoyé avec succès.";
} 
?>
  <header class="header4">
    <img class="imggg" src="PngItem_1783030.png"> 
    <hr><hr id="xx">
    <h2 class="h22"> Online <br> Shop</h2>
    <ul class="navbar2">
      <label><a href="index.php"> Home</a></label>
      <label><a href="index 2.php"> À propos</a></label>
      <label><a href="index 4.php"> Services</a></label>
      <label><a href="index3.php"> Contact us</a></label>
    </ul>

    <div class="ASS">
      <div class="AWS">
        <?php if (!empty($success)) echo "<p style='color: green;'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p style='color: red;'>$error</p>"; ?>

        <form method="POST" action="">
          <div class="form-group">
            <label class="label2" for="name">Nom:</label>
            <input class="input2" type="text" name="nom" required>
          </div>
          <div class="form-group">
            <label class="label2" for="email">Email:</label>
            <input class="input2" type="email" name="email" required>
          </div>
          <div class="form-group">
            <label class="label2">Message:</label>
            <textarea name="message" rows="5" required></textarea>
          </div>
          <button type="submit" class="btn">Envoyer</button>
        </form>
      </div>

      <h3 class="h3">
        
        Support technique : +0805321110
      </h3>
    </div>
  </header>
</body>
</html>