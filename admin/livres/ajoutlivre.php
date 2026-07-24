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

    $titre = trim($_POST["titre"] ?? "");
    $auteur = trim($_POST["auteur"] ?? "");
    $annee = trim($_POST["annee"] ?? "");
    $isbn = trim($_POST["isbn"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if (
        empty($titre) ||
        empty($auteur) ||
        empty($annee) ||
        empty($isbn) ||
        empty($description)
    ) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!isset($_FILES["book_file"]) || $_FILES["book_file"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Veuillez sélectionner un fichier PDF.";
    } elseif (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $error = "Veuillez sélectionner une image pour le livre.";
    } else {

        $book_file = $_FILES["book_file"];
        $image_file = $_FILES["image"];

        $book_file_name = $book_file["name"];
        $book_file_tmp = $book_file["tmp_name"];
        $book_file_size = $book_file["size"];
        $book_extension = strtolower(pathinfo($book_file_name, PATHINFO_EXTENSION));

        $image_name = $image_file["name"];
        $image_tmp = $image_file["tmp_name"];
        $image_size = $image_file["size"];
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if ($book_extension !== "pdf") {
            $error = "Seuls les fichiers PDF sont autorisés.";
        } elseif ($book_file_size > 10 * 1024 * 1024) {
            $error = "Le fichier PDF ne doit pas dépasser 10 MB.";
        } elseif (!in_array($image_extension, ["jpg", "jpeg", "png", "webp"], true)) {
            $error = "Seuls les formats JPG, JPEG, PNG et WEBP sont autorisés pour l'image.";
        } elseif ($image_size > 5 * 1024 * 1024) {
            $error = "L'image ne doit pas dépasser 5 MB.";
        } else {
            $image_info = @getimagesize($image_tmp);

            if ($image_info === false) {
                $error = "Le fichier image est invalide.";
            } else {
                $project_root = dirname(__DIR__, 2);
                $book_upload_dir = $project_root . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "books";
                $image_upload_dir = $project_root . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "images";

                if (!is_dir($book_upload_dir)) {
                    mkdir($book_upload_dir, 0777, true);
                }

                if (!is_dir($image_upload_dir)) {
                    mkdir($image_upload_dir, 0777, true);
                }

                $new_book_name = uniqid("book_", true) . ".pdf";
                $new_image_name = uniqid("cover_", true) . "." . $image_extension;

                $book_file_path = $book_upload_dir . DIRECTORY_SEPARATOR . $new_book_name;
                $image_file_path = $image_upload_dir . DIRECTORY_SEPARATOR . $new_image_name;

                if (move_uploaded_file($book_file_tmp, $book_file_path) && move_uploaded_file($image_tmp, $image_file_path)) {

                    $db_book_path = "uploads/books/" . $new_book_name;
                    $db_image_path = "uploads/images/" . $new_image_name;

                    $sql = "INSERT INTO livres
                            (titre, auteur, annee, isbn, description, fichierPdf, image)
                            VALUES (?, ?, ?, ?, ?, ?, ?)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        $titre,
                        $auteur,
                        $annee,
                        $isbn,
                        $description,
                        $db_book_path,
                        $db_image_path
                    ]);

                    $success = "Le livre a été ajouté avec succès.";
                } else {
                    $error = "Erreur lors de l'upload des fichiers.";
                }
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
    <title>AJOUT LIVRE</title>
    <link rel="stylesheet" href="../../css/admin/ajoutenseigant.css">
</head>

<body>
    <section>
        <h1>Ajouter un Livre</h1>

        <form method="POST" enctype="multipart/form-data" class="form-add">

            <div>
                <label for="titre">Titre</label>

                <input
                    type="text"
                    id="titre"
                    name="titre"
                    required>
            </div>

            <div>
                <label for="auteur">Auteur</label>

                <input
                    type="text"
                    id="auteur"
                    name="auteur"
                    required>
            </div>

            <div>
                <label for="annee">Année</label>

                <input
                    type="date"
                    id="annee"
                    name="annee"
                    required>
            </div>

            <div>
                <label for="isbn">ISBN</label>

                <input
                    type="text"
                    id="isbn"
                    name="isbn"
                    required>
            </div>

            <div>
                <label for="description">Description</label>

                <input
                    id="description"
                    name="description"
                    required></input>
            </div>

            <div>
                <label for="book_file">Fichier PDF</label>

                <input
                    type="file"
                    id="book_file"
                    name="book_file"
                    accept=".pdf"
                    required>
            </div>

            <div>
                <label for="image">Fichier Image</label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
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
        <a href="gestionlivres.php">Retour</a>
    </section>
</body>

</html>