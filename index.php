<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="page.css">
    <link rel="stylesheet" href="bootstrap-5.3.3-dist/index.html">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            overflow-x: hidden;
            overflow-y: hidden;
        }

        .input-box {
            border-radius: 25px;
            border: none;
            font-size: 16px;
            outline: none;
            margin-right: 10px;
        }

        .search-btn {
            background-color: black;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-btn i {
            font-size: 16px;
        }

        .login {
            position: relative;
            left: 1210px;
            bottom: 30px;
            width: 110px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login:hover {
            background-color: #e60000;
        }

        @media (max-width: 600px) {
            .login {
                left: 370px;
            }
        }

        .cta-btn {
            display: inline-block;
            margin-top: 25px;
            padding: 14px 34px;
            background-color: #ff4d4d;
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .cta-btn i {
            margin-left: 10px;
        }

        .cta-btn:hover {
            background-color: #e60000;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body>
<header>

    <img class="imggg" src="PngItem_1783030.png">
    <hr>
    <hr id="xx">

    <h2 class="h22">Online <br> Shop</h2>

    <ul class="navbar2">
        <label><a href="index.php">Home</a></label>
        <label><a href="index 2.php">À propos</a></label>
        <label><a href="index 4.php">Services</a></label>
        <label><a href="index 3.php">Contact us</a></label>
    </ul>

    <a href="login.php"><button class="login">Login</button></a>

    <section class="home">
        <img class="imggg" id="img44" src="10022177_1.png">

        <div class="text-home">
            <h1 id="hA1">Nouvelle collection</h1>
            <h3>toutes les tailles sont disponibles</h3>
            <p>les dernières tendances de mode pour hommes</p>

            <a href="index5.php" class="cta-btn">
                Découvrir la boutique <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <br><br><br><br><br><br><br>

</header>
</body>
</html>