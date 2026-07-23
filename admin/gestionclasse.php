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
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION CLASSES</title>
    <link rel="stylesheet" href="../css/admin/prof.css">
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
            <li><a href="gestioneleve.php"><i class="fa-solid fa-user-graduate"></i><span>Élèves</span></a></li>
            <li><a href="gestionprof.php"><i class="fa-solid fa-chalkboard-user"></i><span>Enseignants</span></a></li>
            <li><a href="gestionclasse.php"><i class="fa-solid fa-school"></i><span>Classes</span></a></li>
            <li><a href="gestionmatiere.php"><i class="fa-brands fa-mattermost"></i><span>Matières</span></a></li>
            <li><a href="gestionlivres.php"><i class="fa-solid fa-book"></i><span>Bibliothèque</span></a></li>
            <li><a href="gestionemploitemps.php"><i class="fa-solid fa-alarm-clock"></i><span>Emplois du temps</span></a></li>
            <li><a href=""><i class="fa-solid fa-user"></i><span>Profil</span></a></li>
            <li><a href=""><i class="fa-solid fa-arrow-up-from-bracket"></i><span>Déconnexion</span></a></li>
        </ul>
    </section>
    <section class="prof-contain">
        <div class="head">
            <h1>Liste des classes</h1>
            <a href="ajoutclasse.php">Ajouter<i class="fa-solid fa-plus"></i></a>
        </div>
        <form action="" method="get">
            <input type="text" name="search" placeholder="Rechercher par niveau">
            <!-- <div>
                <label for="sexe">Filtrer par</label>
                <select name="search" id="sexe" required>
                    <option value="">-- Sexe --</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>
            </div> -->
            <button type="submit"><i class="fa-solid fa-search"></i></button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nom classe</th>
                    <th>Niveau</th>
                    <th>Effectif</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </section>
</body>
</html>