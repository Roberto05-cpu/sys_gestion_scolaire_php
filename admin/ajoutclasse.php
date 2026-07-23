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
    $nom = trim($_POST['nom']);
    $niveau = trim($_POST['niveau']);

    if (empty($nom) || empty($niveau)) {
        $error = "Veuillez remplir tous les champs";
    } else {
        $sql = "INSERT INTO classes (nom, niveau) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $niveau]);

        $success = "Classe ajoutee avec success";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AJOUT CLASSE</title>
    <link rel="stylesheet" href="../css/admin/ajoutenseigant.css">
</head>

<body>
    <section>
        <h1>Ajout Classe</h1>

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
                <label for="niveau">Niveau</label>

                <input
                    type="text"
                    id="niveau"
                    name="niveau"
                    required>
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
        <a href="gestionclasse.php">Retour</a>
    </section>
</body>

</html>