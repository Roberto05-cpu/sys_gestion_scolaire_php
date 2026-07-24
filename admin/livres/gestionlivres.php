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
}

if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
    header('Content-Type: application/json');
    
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    $sql = "SELECT * FROM livres";
    
    $params = [];
    
    if ($search !== '') {
        $sql .= " WHERE (titre LIKE ? OR auteur LIKE ?)";
        $params[] = "%".$search."%";
        $params[] = "%".$search."%";
    }
    
    $sql .= " ORDER BY titre ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $allbooks = $stmt->fetchAll();
    
    echo json_encode($allbooks);
    exit;
}

// recuperation initiale des livres
$sql = "SELECT * FROM livres";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$allbooks = $stmt->fetchAll()

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION LIVRES</title>
    <link rel="stylesheet" href="../../css/admin/prof.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css">
</head>

<body>
    <section class="sidebar">
        <div>
            <img src="../../assets/images/logo.jpg" alt="">
            <h1>EDUGEST</h1>
        </div>
        <ul>
            <li><a href="../dashboard.php"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a></li>
            <li><a href="../eleve/gestioneleve.php"><i class="fa-solid fa-user-graduate"></i><span>Élèves</span></a></li>
            <li><a href="../enseignant/gestionprof.php"><i class="fa-solid fa-chalkboard-user"></i><span>Enseignants</span></a></li>
            <li><a href="../classe/gestionclasse.php"><i class="fa-solid fa-school"></i><span>Classes</span></a></li>
            <li><a href="../gestionmatiere.php"><i class="fa-brands fa-mattermost"></i><span>Matières</span></a></li>
            <li><a href="gestionlivres.php"><i class="fa-solid fa-book"></i><span>Bibliothèque</span></a></li>
            <li><a href="../gestionemploitemps.php"><i class="fa-solid fa-alarm-clock"></i><span>Emplois du temps</span></a></li>
            <li><a href=""><i class="fa-solid fa-user"></i><span>Profil</span></a></li>
            <li><a href="../../logout.php"><i class="fa-solid fa-arrow-up-from-bracket"></i><span>Déconnexion</span></a></li>
        </ul>
    </section>
    <section class="prof-contain">
        <div class="head">
            <h1>Liste des livres</h1>
            <a href="ajoutlivre.php">Ajouter<i class="fa-solid fa-plus"></i></a>
        </div>
        <form action="" method="get" id="filterForm">
            <input type="text" id="searchInput" name="search" placeholder="Rechercher par titre, auteur,">
            <button type="submit"><i class="fa-solid fa-search"></i></button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Annee</th>
                    <th>ISBN</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="livresList">
                <?php if (count($allbooks) > 0): ?>
                    <?php foreach ($allbooks as $book): ?>
                        <tr>
                            <td><?= htmlspecialchars($book['titre']) ?></td>
                            <td><?= htmlspecialchars($book['auteur']) ?></td>
                            <td><?= htmlspecialchars($book['annee']) ?></td>
                            <td><?= htmlspecialchars($book['isbn']) ?></td>
                            <td><?= htmlspecialchars($book['description']) ?></td>
                            <td>
                                <a href="modifiereleve.php?id=<?= $book['id'] ?>" class="btn-edit" ><i class="fa-solid fa-pen"></i></a>
                                <a href="gestioneleve.php?delete=<?= $book['id'] ?>" class="btn-delete" onclick="return confirm('Supprimer ce livre')" ><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Aucun livre trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <script src="../../assets/js/livre.js"></script>
</body>

</html>