<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="globals.css" />
    <link rel="stylesheet" href="rechercheTrajets.css" />
</head>
<body>
    <div class="iphone">
        <div class="div">
            <header class="header">
                <div class="logo">
                    <div class="overlap-group">
                        <img class="logo-omnes-education" src="../../../images/Logo_omnes.png" />
                    </div>
                    <div class="navbtn">
                        <div class="rectangle"></div>
                        <div class="rectangle-2"></div>
                        <div class="rectangle-3"></div>
                    </div>
                </div>
            </header>
            <div class="overlap">
                <input type="text" class="text-input" placeholder="Adresse de Départ" />
            </div>
            <div class="div-wrapper">
                <input type="text" class="text-input" placeholder="Adresse d’arrivée" />
            </div>
            <div class="overlap-2">
                <div class="rectangle-4"></div>
                <div class="text-wrapper-2">Date :</div>
                <input type="date" class="date-input" style="position: absolute; left: 10px; top: 5px; width: 150px;"/>
            </div>
            <div class="se-connecter">
                <div class="overlap-3">
                    <div class="rectangle-5"></div>
                    <div class="text-wrapper-4">Rechercher</div>
                </div>
            </div>
            <div class="rectangle-6"></div>
        </div>
    </div>

    <?php
    // Informations de connexion à la base de données
    $serveur = 'localhost';
    $utilisateur = 'root';
    $mot_de_passe = 'root';
    $base_de_donnees = 'test_projet';

    try {
        // Création d'une connexion PDO
        $connexion = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);

        // Définition du mode de gestion des erreurs
        $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        echo "Connexion réussie !";

        // Exécution d'une requête SQL
        $requete = $connexion->query('SELECT * FROM utilisateur');

    } catch(PDOException $e) {
        // En cas d'erreur, affichage du message d'erreur
        echo "Erreur de connexion : " . $e->getMessage();
    }
    ?>
</body>
</html>
