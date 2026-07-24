<?php

session_start();

require "../../config/db.php";

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
    $telephone = $_POST['telephone'];
    $specialite = $_POST['specialite'];
    $sexe = $_POST['sexe'];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // verfier si l'email existe 
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email =?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $error = "Cet Email existe deja";
    } else {
        $pdo->beginTransaction();

        try {
            // insert dans users
            $sql1 = "INSERT INTO users (email, password, role) VALUES (?, ?, 'enseignant')";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([$email, $password_hash]);
            $user_id = $pdo->lastInsertId();

            // inser dans enseignant
            $sql2 = "INSERT INTO enseignants (user_id, matricule, nom, prenom, sexe, specialite, telephone) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([$user_id, $matricule, $nom, $prenom, $sexe, $specialite, $telephone]);

            $pdo->commit();
            $success = "Enseignant ajouté avec succès !";
            header("Location: gestionprof.php");
            exit ;
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = "Erreur: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJOUT ENSEIGNANT</title>
    <link rel="stylesheet" href="../../css/admin/ajoutenseigant.css">
</head>

<body>
    <section>
        <h1>Ajout Enseignant</h1>

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
                <label for="telephone">Telephone</label>

                <input
                    type="tel"
                    id="telephone"
                    name="telephone"
                    required>
            </div>

            <div>
                <label for="specialite">Spécialité</label>

                <input
                    type="text"
                    id="specialite"
                    name="specialite"
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
        <a href="gestionprof.php">Retour</a>
    </section>
</body>

</html>