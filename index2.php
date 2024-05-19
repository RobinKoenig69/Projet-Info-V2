<?php
$serveur = 'localhost';
$utilisateur = 'root';
$mot_de_passe = '';  // Remplacez 'root' par le mot de passe de votre base de données
$base_de_donnees = 'test_projetsss';

try {
    $connexion = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;  // Arrêt de l'exécution en cas d'erreur de connexion
}
?>
