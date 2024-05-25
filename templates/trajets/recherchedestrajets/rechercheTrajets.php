<?php
// Initialize the session
session_start();

$adresse_depart = $adresse_arrivee = "";
$adresse_depart_err = $adresse_arrivee_err = "";

$email = $_SESSION['email'];
$user_ID = $_SESSION['user_ID'];

$erreur="";

$Heures_depart[]="";
$Heures_arrivee[]="";
$prix[]="";
$places[]="";
$voyage_IDs[]="";

$j=0;

if (empty($email)) {
    header("location: ../accueil/main/pagePrincav.php");
    exit;
}

// Include config file
require_once "../../BDD_login.php";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Assigner la valeur du numéro de voyage à la session
    if (isset($_GET['voyage_id'])) {
        $_SESSION['voyage_reserve'] = $_GET['voyage_id'];
        header("Location: reservation_trajets.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate adresses
    if (empty(trim($_POST["adresse_depart"]))) {
        $adresse_depart_err = "Please enter an adress.";
    } elseif (!preg_match('/^[a-zA-Z0-9, ]+$/', trim($_POST["adresse_depart"]))) {
        $adresse_depart_err = "Adress can only contain letters and numbers";
    } else {
        $adresse_depart = $_POST["adresse_depart"];
    }

    if (empty(trim($_POST["adresse_arrivee"]))) {
        $adresse_arrivee_err = "Please enter an adress.";
    } elseif (!preg_match('/^[a-zA-Z0-9, ]+$/', trim($_POST["adresse_arrivee"]))) {
        $adresse_arrivee_err = "Adress can only contain letters and numbers";
    } else {
        $adresse_arrivee = $_POST["adresse_arrivee"];
    }

    $_SESSION['voyage_depart'] = $adresse_depart;
    $_SESSION['voyage_arrivee'] = $adresse_arrivee;

    $sql = "SELECT Campus_ID FROM campus WHERE adresse = :adresse_depart";

    if ($stmt = $pdo->prepare($sql)) {
        // Attempt to execute the prepared statement

        $stmt->bindParam(":adresse_depart", $adresse_depart);

        if ($stmt->execute()) {
            // Check if there are any records
            if ($stmt->rowCount() > 0) {
                // Fetch all addresses and output them
                while ($row = $stmt->fetch()) {
                    $Campus_ID_Depart = $row["Campus_ID"];
                }
            } else {
                $erreur = "No addresses found in the table.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    $sql = "SELECT Campus_ID FROM campus WHERE adresse = :adresse_arrivee";

    if ($stmt = $pdo->prepare($sql)) {
        // Attempt to execute the prepared statement
        $stmt->bindParam(":adresse_arrivee", $adresse_arrivee);
        if ($stmt->execute()) {
            // Check if there are any records
            if ($stmt->rowCount() > 0) {
                // Fetch all addresses and output them
                while ($row = $stmt->fetch()) {
                    $Campus_ID_Arrivee = $row["Campus_ID"];
                }
            } else {
                $erreur = "No addresses found in the table.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Prepare a select statement
    $sql = "SELECT heure_depart, heure_arrivee, prix, place, voyage_ID FROM voyages WHERE campus_ID = :Campus_ID_Arrivee AND campus_ID_Depart = :Campus_ID_Depart";

    if ($stmt = $pdo->prepare($sql)) {
        // Liez les paramètres
        $stmt->bindParam(":Campus_ID_Arrivee", $Campus_ID_Arrivee);
        $stmt->bindParam(":Campus_ID_Depart", $Campus_ID_Depart);

        if ($stmt->execute()) {
            // Vérifiez s'il y a des enregistrements
            if ($stmt->rowCount() > 0) {
                $j =$stmt->rowCount();
                $i=0;
                // Récupérez toutes les adresses et affichez-les
                while ($row = $stmt->fetch()) {
                    // Supposons que les noms de colonnes dans la table sont 'Campus_ID_Arrivee' et 'Campus_ID_Depart'
                    $Heures_depart[$i]=$row["heure_depart"];
                    $Heures_arrivee[$i]=$row["heure_arrivee"];
                    $prix[$i]=$row["prix"];
                    $places[$i]=$row["place"];
                    $voyage_IDs[$i]=$row["voyage_ID"];
                    $i++;
                }
            } else {
                $erreur = "No addresses found in the table.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    } else {
        echo "Failed to prepare the SQL statement.";
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
    <link rel="stylesheet" href="rechercheTrajets.css?v=<?php echo time(); ?>">
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
            <div>Cherchez un trajet :</div>

        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="overlap align">
                <input autocomplete="off" name="adresse_depart" type="text"
                       class="text-input <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                       id="adresse_depart" value="<?php echo $adresse_depart; ?>" placeholder="Départ" required>
            </div>

            <div class="overlap align2">
                <input autocomplete="off" name="adresse_arrivee" type="text"
                       class="text-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $adresse_arrivee; ?>" id="adresse_arrivee" placeholder="Arrivée" required>
            </div>
            <input type="submit" class="avatar" value="Valider"></input>
        </form>
    </div>

    <div class="output">
        <?php

        // Assurez-vous que tous les tableaux ont la même longueur et que $j est défini
        for ($i = 0; $i < $j; $i++) {
            echo '<div class="flex">';
            echo '<span>Voyage ' . ($i + 1) . ':</span>';
            echo '<span>Heure de départ: ' . $Heures_depart[$i] . '</span>';
            echo '<span>Heure d\'arrivée: ' . $Heures_arrivee[$i] . '</span>';
            echo '<span>Prix: ' . $prix[$i] . '€</span>';
            echo '<span>Places: ' . $places[$i] . '</span>';
            echo '<form method="get">';
            echo '<input type="hidden" name="voyage_id" value="' . $voyage_IDs[$i] . '">';
            echo '<button type="submit">Réserver</button>';
            echo '</form>';
            echo '</div>';
        }
        echo isset($erreur) ? $erreur : '';
        ?>
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