<?php

session_start();

require "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION["role"] !== 'admin') {
    if ($_SESSION["role"] === 'enseignant') {
        header("Location: ../enseignant/dashboard.php");
    } elseif ($_SESSION["role"] === 'eleve') {
        header("Location: ../eleve/dashboard.php");
    } else {
        header("Location: ../login.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION EMPLOI DU TEMPS</title>
    <link rel="stylesheet" href="../css/admin/emploi.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css">
</head>
<body>
    <section class="sidebar">
        <div>
            <img src="../assets/images/logo.jpg" alt="">
            <h1>EDUGEST</h1>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a></li>
            <li><a href="eleve/gestioneleve.php"><i class="fa-solid fa-user-graduate"></i><span>Élèves</span></a></li>
            <li><a href="enseignant/gestionprof.php"><i class="fa-solid fa-chalkboard-user"></i><span>Enseignants</span></a></li>
            <li><a href="classe/gestionclasse.php"><i class="fa-solid fa-school"></i><span>Classes</span></a></li>
            <li><a href="gestionmatiere.php"><i class="fa-brands fa-mattermost"></i><span>Matières</span></a></li>
            <li><a href="livres/gestionlivres.php"><i class="fa-solid fa-book"></i><span>Bibliothèque</span></a></li>
            <li><a href="gestionemploitemps.php"><i class="fa-solid fa-alarm-clock"></i><span>Emplois du temps</span></a></li>
            <li><a href=""><i class="fa-solid fa-user"></i><span>Profil</span></a></li>
            <li><a href="../logout.php"><i class="fa-solid fa-arrow-up-from-bracket"></i><span>Déconnexion</span></a></li>
        </ul>
    </section>
    <section>

    </section>
</body>
</html>