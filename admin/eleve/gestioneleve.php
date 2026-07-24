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

// Vérifie si c'est une requête AJAX
if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
    header('Content-Type: application/json');
    
    $classe_id = isset($_GET['classe_id']) ? $_GET['classe_id'] : '';
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    
    $sql = "SELECT u.id, u.email, e.*, c.nom as classe_nom, c.niveau FROM users u 
            JOIN eleves e ON u.id = e.user_id 
            JOIN classes c ON e.classe_id = c.id 
            WHERE u.role = 'eleve'";
    
    $params = [];
    
    if ($classe_id !== '') {
        $sql .= " AND e.classe_id = ?";
        $params[] = $classe_id;
    }
    
    if ($search !== '') {
        $sql .= " AND (e.nom LIKE ? OR e.prenom LIKE ? OR e.matricule LIKE ?)";
        $params[] = "%".$search."%";
        $params[] = "%".$search."%";
        $params[] = "%".$search."%";
    }
    
    $sql .= " ORDER BY e.nom ASC, e.prenom ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $eleves = $stmt->fetchAll();
    
    echo json_encode($eleves);
    exit;
}

// recuperation des classes
$stmt_classes = $pdo->prepare("SELECT * FROM classes ORDER BY nom ASC");
$stmt_classes->execute();
$classes = $stmt_classes->fetchAll();

// supprimer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$id]);
    header("Location: gestioneleve.php");
    exit;
}

// Récupération initiale des élèves
$sql = "SELECT u.id, u.email, e.*, c.nom as classe_nom, c.niveau FROM users u 
        JOIN eleves e ON u.id = e.user_id 
        JOIN classes c ON e.classe_id = c.id 
        WHERE u.role = 'eleve' 
        ORDER BY e.nom ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$eleves = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GESTION ELEVES</title>
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
            <li><a href="gestioneleve.php"><i class="fa-solid fa-user-graduate"></i><span>Élèves</span></a></li>
            <li><a href="../enseignant/gestionprof.php"><i class="fa-solid fa-chalkboard-user"></i><span>Enseignants</span></a></li>
            <li><a href="../classe/gestionclasse.php"><i class="fa-solid fa-school"></i><span>Classes</span></a></li>
            <li><a href="../gestionmatiere.php"><i class="fa-brands fa-mattermost"></i><span>Matières</span></a></li>
            <li><a href="../livres/gestionlivres.php"><i class="fa-solid fa-book"></i><span>Bibliothèque</span></a></li>
            <li><a href="../gestionemploitemps.php"><i class="fa-solid fa-alarm-clock"></i><span>Emplois du temps</span></a></li>
            <li><a href=""><i class="fa-solid fa-user"></i><span>Profil</span></a></li>
            <li><a href="../../logout.php"><i class="fa-solid fa-arrow-up-from-bracket"></i><span>Déconnexion</span></a></li>
        </ul>
    </section>
    <section class="prof-contain">
        <div class="head">
            <h1>Liste des Élèves</h1>
            <a href="ajouteleve.php">Ajouter<i class="fa-solid fa-plus"></i></a>
        </div>
        <form action="" method="get" id="filterForm">
            <input type="text" id="searchInput" name="search" placeholder="Rechercher par nom, prenom, matricule">
            <div>
                <label for="classeSelect">Filtrer par classe</label>
                <select name="classe_id" id="classeSelect">
                    <option value="">Toutes les classes</option>
                    <?php foreach ($classes as $c) : ?>
                        <option value="<?= $c['id'] ?>"><?= $c['niveau'] ?> <?= $c['nom'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <button type="button" id="btnClear"> Réinitialiser</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom & Prenom</th>
                    <th>Email</th>
                    <th>Classe</th>
                    <th>Sexe</th>
                    <th>Date Naissance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="elevesList">
                <?php if (count($eleves) > 0) : ?>
                    <?php foreach($eleves as $eleve) : ?>
                        <tr class="eleve-row" data-classe="<?= $eleve['classe_id'] ?>" data-search="<?= strtolower($eleve['nom'].' '.$eleve['prenom'].' '.$eleve['matricule']) ?>">
                            <td><?= htmlspecialchars($eleve['matricule']) ?></td>
                            <td><?= htmlspecialchars($eleve['nom']) ?> <?= htmlspecialchars($eleve['prenom']) ?></td>
                            <td><?= htmlspecialchars($eleve['email']) ?></td>
                            <td><?= htmlspecialchars($eleve['niveau'] . ' ' . $eleve['classe_nom']) ?></td>
                            <td><?= htmlspecialchars($eleve['sexe']) ?></td>
                            <td><?= htmlspecialchars($eleve['date_naissance']) ?></td>
                            <td>
                                <a href="modifiereleve.php?id=<?= $eleve['user_id'] ?>" class="btn-edit" ><i class="fa-solid fa-pen"></i></a>
                                <a href="gestioneleve.php?delete=<?= $eleve['user_id'] ?>" class="btn-delete" onclick="return confirm('Supprimer cet élève')" ><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach ;?>
                <?php else : ?>
                    <tr><td colspan="7">Aucun élève</td></tr>
                <?php endif ;?>
            </tbody>
        </table>
    </section>

    <script src="../../assets/js/eleve.js"></script>
</body>
</html>