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

// recuperation des classes
$stmt_classes = $pdo->prepare("SELECT * FROM classes ORDER BY nom ASC");
$stmt_classes->execute();
$classes = $stmt_classes->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $classe_id = trim($_POST['classe_id']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $matricule = trim($_POST['matricule']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $date_naissance = trim($_POST['date_naissance']);
    $sexe = trim($_POST['sexe']);

    if (empty($classe_id) || empty($nom) || empty($prenom) || empty($matricule) || empty($email) || empty($password) || empty($date_naissance) || empty($sexe)) {
        $error = "Veuillez remplir tous les champs";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        // verifier si email existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email =?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Cet Email existe deja";
        } else {
            $pdo->beginTransaction();

            try {
                // inserer dans users
                $sql1 = "INSERT INTO users (email, password, role) VALUES (?, ?, 'eleve')";
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->execute([$email, $password_hash]);
                $user_id = $pdo->lastInsertId();

                // inserer dans eleves
                $sql2 = "INSERT INTO eleves (user_id, classe_id, matricule, nom, prenom, sexe, date_naissance) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute([$user_id, $classe_id, $matricule, $nom, $prenom, $sexe, $date_naissance]);

                $pdo->commit();
                $success = "Eleve ajouté avec succès !";
            } catch(Exception $e) {
                $pdo->rollBack();
                $error = "Erreur: " . $e->getMessage();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJOUT ELEVE</title>
    <link rel="stylesheet" href="../../css/admin/ajoutenseigant.css">
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
             <div>
                <label for="classe_id">Classe</label>
                <select name="classe_id" id="classe_id" required>
                    <option value="">-- Choisir une classe --</option>
                    <?php foreach($classes as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['niveau'] ?> <?= $c['nom'] ?></option>
                    <?php endforeach ?>
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