<?php
session_start();
include "conn.php";
$username = '';  
 
  
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

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: crud.php");
    exit();
}

$query = "SELECT * FROM messages ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Messages des Clients</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #fceabb, #f8b500);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            padding: 30px;
            position: relative;
            left: 30PX;
            top: 130PX;
        }

        h1 {
            text-align: center;
            color: #ff6f00;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
        }

        thead {
            background-color: #ffb300;
            color: white;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        tbody tr:hover {
            background-color: #fff8e1;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            th {
                background-color: transparent;
                color: #333;
                font-weight: bold;
                padding-top: 20px;
            }

            td {
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                padding-left: 15px;
                font-weight: bold;
                color: #888;
            }
        }
        .header {
            display: flex;
            align-items: center;
            gap: 20px;
          position: relative;
          right: 12PX;
          bottom: 20PX;
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
     left: 600PX;
     bottom: 50PX;
     height: 40PX;
   }
   
 
.search-box{
    display:flex;
    justify-content:center;
    margin:20px 0;
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
     
    </div>
   <div class="search-box">
    <input type="text" id="search" placeholder="Search product">
    <button><i class="fa fa-search"></i></button>
</div>
    <div class="container">
        <h1>Messages des Clients</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $message): ?>
                        <tr>
                            <td data-label="ID"><?= htmlspecialchars($message['id']) ?></td>
                            <td data-label="Nom"><?= htmlspecialchars($message['nom']) ?></td>
                            <td data-label="Email"><?= htmlspecialchars($message['email']) ?></td>
                            <td data-label="Message"><?= nl2br(htmlspecialchars($message['message'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">Aucun message trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<script>
    const searchInput = document.querySelector('search');

    searchInput.addEventListener('keyup', function() {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tr');

        rows.forEach((row, index) => {
            if (index === 0) return; 

            const usernameCell = row.querySelector('td:nth-child(2)');
            if (usernameCell) {
                const username = usernameCell.textContent.toLowerCase();
                row.style.display = username.includes(filter) ? '' : 'none';
            }
        });
    });
</script>
</body>
</html>