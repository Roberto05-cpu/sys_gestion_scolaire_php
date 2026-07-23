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

// supprimer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$id]);
    header("Location: gestionprof.php");
    exit;
}

// filtrer , rechercher
$search = "";
$sql = "SELECT u.id, u.email, e.* FROM users u JOIN enseignants e ON u.id = e.user_id WHERE u.role = 'enseignant'";

if (isset($_GET['search']) && $_GET['search'] !== "") {
    $search = "%".$_GET['search']."%";
    $sql .= " AND (e.nom LIKE ? OR e.prenom LIKE ? OR e.matricule LIKE ? OR e.specialite LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $search, $search, $search, $search
    ]);
} else {
    $sql .= " ORDER BY e.nom ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

$enseignants = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION ENSEIGNANTS</title>
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
            <h1>Liste des enseignants</h1>
            <a href="ajoutenseignanr.php">Ajouter<i class="fa-solid fa-plus"></i></a>
        </div>
        <form action="" method="get">
            <input type="text" name="search" placeholder="Rechercher par nom, prenom, matricule, specialite">
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
                    <th>Matricule</th>
                    <th>Nom & Prenom</th>
                    <th>Email</th>
                    <th>Spécialité</th>
                    <th>Sexe</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($enseignants) > 0) : ?>
                    <?php foreach($enseignants as $ens) : ?>
                        <tr>
                            <td><?= htmlspecialchars($ens['matricule']) ?></td>
                            <td><?= htmlspecialchars($ens['nom']) ?> <?= htmlspecialchars($ens['prenom']) ?></td>
                            <td><?= htmlspecialchars($ens['email']) ?></td>
                            <td><?= htmlspecialchars($ens['specialite']) ?></td>
                            <td><?= htmlspecialchars($ens['sexe']) ?></td>
                            <td><?= htmlspecialchars($ens['telephone']) ?></td>
                            <td>
                                <a href="modifierenseignant.php?id=<?= $ens['user_id'] ?>" class="btn-edit" ><i class="fa-solid fa-pen"></i></a>
                                <a href="gestionprof.php?delete=<?= $ens['user_id'] ?>" class="btn-delete" onclick="return confirm('SUpprimer cet enseignant')" ><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach ;?>
                <?php else : ?>
                    <tr><td colspan="7">Aucun enseignant</td></tr>
                <?php endif ;?>
            </tbody>
        </table>
    </section>
</body>

</html>