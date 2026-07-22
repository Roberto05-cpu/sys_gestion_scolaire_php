<?php

require "../config/db.php";

$email = "Eduadminschool05@gmail.com";
$password = "12345";
$role = 'admin';

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (email, password, role)
        VALUES (:email, :password, :role)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    "email" => $email,
    "password" => $password_hash,
    "role" => $role,
]);

echo "Admin créé avec succès";