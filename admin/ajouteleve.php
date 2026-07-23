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

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $matricule = $_POST['matricule'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $date_naissance = $_POST['date_naissance'];
    $sexe = $_POST['sexe'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJOUT ELEVE</title>
    <link rel="stylesheet" href="../css/admin/ajoutenseigant.css">
</head>

<body>
    <section>
        <h1>Ajout Eleve</h1>

        <form method="POST">

            <div>
                <label for="nom">Nom</label>

                <input
                    type="text"
                    id="nom"
                    name="nom"
                    required>
            </div>

            <div>
                <label for="prenom">Prenom</label>

                <input
                    type="text"
                    id="prenom"
                    name="prenom"
                    required>
            </div>

            <div>
                <label for="matricule">Matricule</label>

                <input
                    type="text"
                    id="matricule"
                    name="matricule"
                    required>
            </div>

            <div>
                <label for="email">Email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    required>
            </div>

            <div>
                <label for="password">Password</label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    required>
            </div>

            <div>
                <label for="date_naissance">Date Naissance</label>

                <input
                    type="date"
                    id="date_naissance"
                    name="date_naissance"
                    required>
            </div>

            <div>
                <label for="sexe">Sexe</label>
                <select name="sexe" id="sexe" required>
                    <option value="">-- Choisir --</option>
                    <option value="homme">Homme</option>
                    <option value="femme">Femme</option>
                </select>
            </div>

            <button type="submit">
                Ajouter
            </button>

        </form>
        <?php if (!empty($error)): ?>
            <p style="color: red;">
                <?= htmlspecialchars($error) ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <p style="color: green;">
                <?= htmlspecialchars($success) ?>
            </p>
        <?php endif; ?>
        <a href="gestioneleve.php">Retour</a>
    </section>
</body>

</html>