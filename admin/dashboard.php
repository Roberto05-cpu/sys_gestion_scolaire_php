<?php 

session_start();

require "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
</head>
<body>
    <section class="sidebar">
        <div>
            <img src="../assets/images/logo.jpg" alt="">
            <h1>EDUGEST</h1>
        </div>
        <ul>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Dashboard</a></li>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Élèves</a></li>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Enseignants</a></li>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Classes</a></li>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Matières</a></li>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Bibliothèque</a></li>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Emplois du temps</a></li>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Profil</a></li>
            <li><img src="../assets/images/layout-dashboard.png" alt=""><a href="">Déconnexion</a></li>
        </ul>
    </section>
    <section class="dashboard">
        <div class="head">
            <h1>DASHBOARD</h1>
            <div>
                <img src="../assets/images/img1.jpg" alt="">
                <p>Admin: <span>Eduadminschool05@gmail.com</span></p>
            </div>
        </div>
        <div class="stats">
            <div>
                <p>Total Elèves</p>
                <span>500</span>
            </div>
            <div>
                <p>Total Enseignants</p>
                <span>10</span>
            </div>
            <div>
                <p>Total Classes</p>
                <span>40</span>
            </div>
            <div>
                <p>Total Livres</p>
                <span>50</span>
            </div>
        </div>
    </section>
</body>
</html>