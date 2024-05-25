<?php
// Initialize the session
session_start();

$adresse_depart = $adresse_arrivee = $prix = $place = $fumeur = $heure_depart = $heure_arrivee = $date = "";
$adresse_depart_err = $adresse_arrivee_err = $prix_err = $fumeur_err = $place_err = $heure_depart_err = $heure_arrivee_err =$date_err = "";

$email = $_SESSION['email'];
$user_ID = $_SESSION['user_ID'];

if (empty($email)) {
    header("location: ../../accueil/main/pagePrincav.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BlaBla Omnes</title>
    <link rel="stylesheet" href="newTrajet.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../template.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="main-container">
    <div class="top-bar">
        <div class="logo-container">
            <div class="logo-background">
                <img src="../../../images/Logo_omnes.png" alt="Logo"/>
            </div>
        </div>
        <div class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="bottom-bar">
        <a href="../../trajets/recherchedestrajets/rechercheTrajets.php" target="_blank">
            <img src="../../../images/loupe.png" alt="Placeholder image">
        </a>
        <a href="../../inscription/ins3/inscription3_profil.php" target="_blank">
            <img src="../../../images/message.png" alt="Placeholder image">
        </a>
        <a href="../../trajets/creationtrajet/newTrajet.php" target="_blank">
            <img src="../../../images/plus1.png" alt="Placeholder image">
        </a>
        <a href="../../trajets/mesTrajets/mesTrajets.php" target="_blank">
            <img src="../../../images/voiture.png" alt="Placeholder image">
        </a>
    </div>


    <div class="small-circle"></div>
    <div >
        <div class="connexion connexion2">
            <div>Attendez que votre compte soit validé pour créer un trajet !</div>
        </div>

        <img src="../../../images/warning.jpg" alt="Placeholder image" class="warning">

    <div class="logout-icon">
        <a href="../../Connexion/logout.php">
            <img  class="logout-icon" src="../../../images/deconnexion.png" alt="Déconnexion" />
        </a>
    </div>
</div>
</body>
</html>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZPX2ee3ukXiDkpm3ZSUTYzeuJn-ttahU&libraries=places"></script>
<script>
    // Initialise la saisie semi-automatique des adresses de Google Maps pour l'élément d'entrée d'adresse_depart
    var input = document.getElementById('adresse_depart');
    var autocomplete = new google.maps.places.Autocomplete(input);

    // Initialise la saisie semi-automatique des adresses de Google Maps pour l'élément d'entrée d'adresse_depart
    var input2 = document.getElementById('adresse_arrivee');
    var autocomplete2 = new google.maps.places.Autocomplete(input2);
</script>