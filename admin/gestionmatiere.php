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

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST['nom']);

    if (empty($nom)) {
        $error = "Veuillez remplir ce champ";
    } else {
        $sql = "INSERT INTO matieres (nom) VALUES (?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom]);

        $success = "Matiere ajoutee avec success";
    }
}

$sql = "SELECT * FROM matieres";
$matieres = $pdo->query($sql)->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION MATIERES</title>
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
    <section class="prof-contain">
        <div class="head">
            <h1>Liste des Matieres</h1>
        </div>
        <form action="" method="post">
            <input type="text" name="nom" placeholder="Ajouter une matiere">
            <button class="add" type="submit">Ajouter<i class="fa-solid fa-plus"></i></button>
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
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Matiere</th>
                </tr>
            </thead>
            <tbody>
               <?php if (count($matieres) > 0) : ?>
                    <?php foreach($matieres as $m) : ?>
                        <tr>
                            <td> <?= htmlspecialchars($m['id']) ?></td>
                            <td><?= htmlspecialchars($m['nom']) ?></td>
                        </tr>
                    <?php endforeach ;?>
                <?php else : ?>
                    <tr><td colspan="7">Aucune matiere</td></tr>
                <?php endif ;?>
            </tbody>
        </table>
    </section>
</body>
</html>