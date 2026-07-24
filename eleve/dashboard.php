<?php 

session_start();

require "../config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../login.php");
    exit;
}

if ($_SESSION["role"] !== 'eleve') {
    if ($_SESSION["role"] === 'enseignant') {
        header("Location: ../enseignant/dashboard.php");
    } elseif ($_SESSION["role"] === 'admin') {
        header("Location: ../admin/dashboard.php");
    } else {
        header("Location: ../login.php");
    }
}

$sql = "SELECT u.id, u.email, e.* FROM users u JOIN eleves e ON u.id = e.user_id WHERE e.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION["user_id"]]);
$eleve = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>BIENVENUE ELEVE <?= $eleve['nom'] ?></p>
    <a href="../logout.php">Deconnexion</a>
</body>
</html>