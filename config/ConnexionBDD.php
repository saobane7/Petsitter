<?php

declare(strict_types=1);

$hote = getenv('PETGARDEN_DB_HOST');
$base = getenv('PETGARDEN_DB_NAME');
$utilisateur = getenv('PETGARDEN_DB_USER');
$motDePasse = getenv('PETGARDEN_DB_PASSWORD');

$hote = $hote !== false ? $hote : '127.0.0.1';
$base = $base !== false ? $base : 'petgarden';
$utilisateur = $utilisateur !== false ? $utilisateur : 'root';
$motDePasse = $motDePasse !== false ? $motDePasse : '';

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $hote, $base);

$pdo = new PDO($dsn, $utilisateur, $motDePasse, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);
