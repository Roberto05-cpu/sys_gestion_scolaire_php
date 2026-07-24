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

// total eleves 
$sql1 = "SELECT * FROM eleves";
$stmt1 = $pdo->query($sql1);
$eleves = $stmt1->fetchAll();
$nbeleves = count($eleves); 

// grouper les eleves par sexe
$sql2 = "SELECT sexe , COUNT(*) as total FROM eleves GROUP BY sexe";
$stmt2 = $pdo->query($sql2);
$data = $stmt2->fetchAll(PDO::FETCH_ASSOC);
$labels = [];
$values = [];
foreach ($data as $row) {
    $labels[] = ($row['sexe'] === 'homme') ? 'Garcons' : 'Filles';
    $values[] = (int)$row['total'];
}
$charData = json_encode([
    'labels' => $labels,
    'data' => $values
]);

// grouper les eleves par niveau
$dataniveau = $pdo->query("SELECT c.niveau , COUNT(e.id) as total FROM classes c LEFT JOIN eleves e ON c.id = e.classe_id GROUP BY c.niveau")->fetchAll(PDO::FETCH_ASSOC);
$labelsNiv = [];
$valuesNiv = [];
foreach ($dataniveau as $row) {
    $labelsNiv[] = $row['niveau'];
    $valuesNiv[] = (int)$row['total'];
}
$charDataNiv = json_encode([
    'labelsNiv' => $labelsNiv,
    'dataNiv' => $valuesNiv
]);

// total enseignants 
$sql3 = "SELECT * FROM enseignants";
$stmt3 = $pdo->query($sql3);
$enseignants = $stmt3->fetchAll();
$nbenseignants = count($enseignants); 

// total classes 
$sql4 = "SELECT * FROM classes";
$stmt4 = $pdo->query($sql4);
$classes = $stmt4->fetchAll();
$nbclasses = count($classes); 

// total livres
$livres = $pdo->query("SELECT * FROM livres")->fetchAll();
$nblivres = count($livres);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <section class="dashboard">
        <div class="head">
            <h1>DASHBOARD</h1>
            <div>
                <img src="../assets/images/img1.jpg" alt="">
                <p>Admin: <span>Eduadminschool05@gmail.com</span></p>
            </div>
        </div>
        <div class="stats">
            <div>
                <p>Total Elèves</p>
                <span><?= $nbeleves ?></span>
            </div>
            <div>
                <p>Total Enseignants</p>
                <span><?= $nbenseignants ?></span>
            </div>
            <div>
                <p>Total Classes</p>
                <span><?= $nbclasses ?></span>
            </div>
            <div>
                <p>Total Livres</p>
                <span><?= $nblivres ?></span>
            </div>
        </div>
        <div class="chart-container">
            <div class="chart-card">
                <h2>Repartition par niveau</h2>
                <canvas id="niveauChart" data-chartniv="<?= htmlspecialchars($charDataNiv, ENT_QUOTES, 'UTF-8') ?>"></canvas>
            </div>
            <div class="chart-card">
                <h2>Repartition Eleves Garcons / Filles</h2>
                <canvas id="genreChart" data-chart="<?= htmlspecialchars($charData, ENT_QUOTES, 'UTF-8') ?>"></canvas>
            </div>
        </div>
    </section>
    <script src="../assets/js/chart.js"></script>
</body>

</html>