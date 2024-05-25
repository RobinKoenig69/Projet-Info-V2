<?php
// Initialize the session
session_start();

$adresse_depart = $adresse_arrivee = "";
$adresse_depart_err = $adresse_arrivee_err = "";

$email = $_SESSION['email'];
$user_ID = $_SESSION['user_ID'];

$erreur = "";

$Heures_depart[] = "";
$Heures_arrivee[] = "";
$prix[] = "";
$Campus_departs_ID[] = "";
$Campus_arrivee_ID[] = "";
$Adresses_depart[] = "";
$Adresses_arrivee[] = "";
$voyage_IDs[] = "";
$reservation_IDs[]="";
$dates[]="";

$erreur ="";

$j = 0;

if (empty($email)) {
    header("location: ../../accueil/main/pagePrincav.php");
    exit;
}

// Include config file
require_once "../../BDD_login.php";

if (isset($user_ID)) {

    $sql = "SELECT v.heure_depart, v.heure_arrivee,v.date, v.prix, v.statut, v.Campus_ID, v.Campus_ID_Depart, v.voyage_ID, r.reservation_ID FROM reservation r JOIN voyages v ON r.voyage_ID = v.voyage_ID WHERE r.user_ID = :user_ID";

    if ($stmt = $pdo->prepare($sql)) {
        // Lier les paramètres
        $stmt->bindParam(":user_ID", $user_ID, PDO::PARAM_INT);

        // Tenter d'exécuter la déclaration préparée
        if ($stmt->execute()) {
            // Vérifier s'il y a des enregistrements
            if ($stmt->rowCount() > 0) {
                $j = $stmt->rowCount();
                $i = 0;

                // Récupérer toutes les lignes et les afficher
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $Heures_depart[$i] = $row["heure_depart"];
                    $Heures_arrivee[$i] = $row["heure_arrivee"];
                    $prix[$i] = $row["prix"];
                    $Campus_arrivee_ID[$i] = $row["Campus_ID"];
                    $Campus_departs_ID[$i] = $row["Campus_ID_Depart"];
                    $voyage_IDs[$i] = $row["voyage_ID"];
                    $reservation_IDs[$i]=$row["reservation_ID"];
                    $dates[$i]=$row["date"];
                    $i++;
                }
            } else {
                $erreur = "Aucun enregistrement trouvé.";
            }
        } else {
            $erreur = "Oups! Une erreur est survenue. Veuillez réessayer plus tard.";
        }
    }

    for ($k = 0; $k < $j; $k++) {
        $sql = "SELECT adresse FROM campus WHERE Campus_ID =:Campus_ID";

        if ($stmt = $pdo->prepare($sql)) {
            // Lier les paramètres
            $stmt->bindParam(":Campus_ID", $Campus_departs_ID[$k], PDO::PARAM_INT);


            if ($stmt->execute()) {
                // Vérifier s'il y a des enregistrements
                if ($stmt->rowCount() > 0) {
                    // Récupérer toutes les lignes et les afficher
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $Adresses_depart[$k] = $row["adresse"];
                    }
                } else {
                    $erreur = "Aucun enregistrement trouvé.";
                }
            } else {
                $erreur = "Oups! Une erreur est survenue. Veuillez réessayer plus tard.";
            }
        }
    }

    for ($k = 0; $k < $j; $k++) {
        $sql = "SELECT adresse FROM campus WHERE Campus_ID =:Campus_ID";

        if ($stmt = $pdo->prepare($sql)) {
            // Lier les paramètres
            $stmt->bindParam(":Campus_ID", $Campus_arrivee_ID[$k], PDO::PARAM_INT);


            if ($stmt->execute()) {
                // Vérifier s'il y a des enregistrements
                if ($stmt->rowCount() > 0) {
                    // Récupérer toutes les lignes et les afficher
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $Adresses_arrivee[$k] = $row["adresse"];
                    }
                } else {
                    $erreur = "Aucun enregistrement trouvé.";
                }
            } else {
                $erreur = "Oups! Une erreur est survenue. Veuillez réessayer plus tard.";
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['reservation_ID'])) {
        $_SESSION['voyage_annul'] = $_POST['reservation_ID'];
    }

    // Prepare an insert statement
    $sql = "DELETE FROM reservation WHERE reservation_ID =:reservation_ID";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":reservation_ID", $_SESSION['voyage_annul']);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            header("Refresh:0");
        } else {
            $erreur = "Oops! Something went wrong. Please try again later.";
        }


        // Close statement
        unset($stmt);
    }

}


// Close connection
unset($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BlaBla Omnes</title>
    <link rel="stylesheet" href="mesTrajets.css?v=<?php echo time(); ?>">
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
    <div class="entry">
        <div class="connexion">
            <div>Vos Trajets :</div>
        </div>
    </div>

    <div class="output">
        <?php
        // Assurez-vous que tous les tableaux ont la même longueur et que $j est défini
        for ($i = 0; $i < $j; $i++) {
            echo '<div class="flex">';
            echo '<span>Voyage ' . ($i + 1) . ':</span>';
            echo '<span>Heure de départ: ' . $Heures_depart[$i] . '</span>';
            echo '<span>Heure d\'arrivée: ' . $Heures_arrivee[$i] . '</span>';
            echo '<span>Date: ' . $dates[$i] . '</span>';
            echo '<span>Prix: ' . $prix[$i] . '€</span>';
            echo '<span>Départ: ' . $Adresses_depart[$i] . '</span>';
            echo '<span>Arrivee: ' . $Adresses_arrivee[$i] . '</span>';
            echo '<form method="post">';
            echo '<input type="hidden" name="reservation_ID" value="' . $reservation_IDs[$i] . '">';
            echo '<button class="button_annuler" type="submit">Annuler</button>';
            echo '</form>';
            echo '</div>';
        }
        echo isset($erreur) ? $erreur : '';
        ?>
    </div>

    <div class="logout-icon">
        <a href="../../Connexion/logout.php">
            <img  class="logout-icon" src="../../../images/deconnexion.png" alt="Déconnexion" />
        </a>
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