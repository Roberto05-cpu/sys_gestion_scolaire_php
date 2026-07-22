<?php

require "config/db.php";

session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $sql = "SELECT * FROM users
            WHERE email = :email";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        "email" => $email
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_role"] = $user["role"];

        // echo "<script>alert('Connexion reussie');</script>";
        if ($user["role"] === 'admin') {
            header("Location: admin/dashboard.php");
        } elseif ($user["role"] === 'eleve') {
            header("Location: eleve/dashboard.php");
        } else {
            header("Location: enseignant/dashboard.php");
        }
        exit;
    } else {

        $error = "Email ou mot de passe incorrect ou mauvais role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <section>
        <div class="div-princ">
            <img src="assets/images/img5.jpg" alt="">
            <div class="div-form">
                <h1>Bienvenue sur EDUGEST</h1>
                <p>Connectez vous a votre compte et accedez a votre espace personnel</p>
                <form method="post">
                    <div>
                        <label for="email">Adresse Email</label>
                        <input type="email" name="email" id="email" placeholder="Entrez votre email">
                    </div>
                    <div>
                        <label for="password">Mot de passe</label>
                        <input type="password" name="password" id="password" placeholder="Entrez votre mot de passe">
                    </div>
                    <div class="check">
                        <label>
                            Admin
                            <input type="radio" name="role" value='admin' required />
                        </label>
                        <label>
                            Enseignant
                            <input type="radio" name="role" value='enseignant' required />
                        </label>
                        <label>
                            Eleve
                            <input type="radio" name="role" value='eleve' required />
                        </label>
                    </div>
                    <button type="submit">
                        Se connecter
                    </button>
                    <?php if (!empty($error)) : ?>
                        <p style="color: red;"><?= htmlspecialchars($error) ?></p>
                    <?php endif; ?>
                </form>
                <a href="index.php" class="retour">Retour a l'accueil</a>
            </div>
        </div>
    </section>
</body>

</html>