<?php
// Initialize the session
session_start();

$adresse_depart = $adresse_arrivee = $prix = $place = $heure_depart = $heure_arrivee = "";
$adresse_depart_err = $adresse_arrivee_err = $prix_err = $place_err = $heure_depart_err = $heure_arrivee_err = "";

$email = $_SESSION['email'];
$user_ID = $_SESSION['user_ID'];

if (empty($email)) {
    header("location: ../accueil/main/pagePrincav.php");
    exit;
}

// Include config file
require_once "../../BDD_login.php";

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

// Validate price
    if (empty(trim($_POST["prix"]))) {
        $prix_err = "Please enter a phone number.";
    } elseif (!preg_match('/^[0-9]+$/', trim($_POST["prix"]))) {
        $prix_err = "Price can only contain numbers.";
    } else {
        $prix = $_POST["prix"];
    }

    if (empty(trim($_POST["place"]))) {
        $place_err = "Please enter a phone number.";
    } elseif (!preg_match('/^[0-9]+$/', trim($_POST["place"]))) {
        $place_err = "Seats can only contain numbers.";
    } else {
        $place = $_POST["place"];
    }

    // Prepare a select statement
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
                echo "No addresses found in the table.";
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
                echo "No addresses found in the table.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    if (empty(trim($_POST["heure_depart"]))) {
        $heure_depart_err = "Please enter a departure time.";
    } else {
        $heure_depart = $_POST["heure_depart"];
    }

    if (empty(trim($_POST["heure_arrivee"]))) {
        $heure_arrivee_err = "Please enter an arrival time.";
    } else {
        $heure_arrivee = $_POST["heure_arrivee"];
    }

    if (empty($heure_depart_err) && empty($adresse_depart_err) && empty($adresse_arrivee_err) && empty($place_err) && empty($prix_err) && empty($heure_arrivee_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO voyages (heure_depart, heure_arrivee, prix, place, user_ID, Campus_ID_Depart, Campus_ID) VALUES (:heure_depart, :heure_arrivee, :prix, :place, :user_ID, :Campus_ID_Depart, :Campus_ID_Arrivee)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":heure_depart", $param_heure_depart);
            $stmt->bindParam(":heure_arrivee", $param_heure_arrivee);
            $stmt->bindParam(":prix", $param_prix);
            $stmt->bindParam(":place", $param_place);
            $stmt->bindParam(":user_ID", $param_user_ID);
            $stmt->bindParam(":Campus_ID_Depart", $param_Campus_ID_Depart);
            $stmt->bindParam(":Campus_ID_Arrivee", $param_Campus_ID_Arrivee);


            $param_prix = $prix;
            $param_place = $place;
            $param_Campus_ID_Arrivee = $Campus_ID_Arrivee;
            $param_Campus_ID_Depart = $Campus_ID_Depart;
            $param_heure_arrivee = $heure_arrivee;
            $param_heure_depart = $heure_depart;
            $param_user_ID = $user_ID;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $last_id = $pdo->lastInsertId();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }


            // Close statement
            unset($stmt);
        }

        $siege_reserve = 0;
        $updated_at = date('Y-m-d H:i:s');
        $statut = "reserve";

        $sql = "INSERT INTO reservation (siege_reserve, date_reservation, statut, voyage_ID, user_ID) VALUES (:siege_reserve, :date_reservation, :statut, :voyage_ID, :user_ID)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":siege_reserve", $param_siege_reserve);
            $stmt->bindParam(":date_reservation", $param_date_reservation);
            $stmt->bindParam(":statut", $param_statut);
            $stmt->bindParam(":voyage_ID", $param_voyage_ID);
            $stmt->bindParam(":user_ID", $param_user_ID);

            $param_siege_reserve = 1;
            $param_date_reservation = $updated_at;
            $param_statut = "reserve";
            $param_voyage_ID = $last_id;
            $param_user_ID = $user_ID;


            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                header("location: ../accueil/main/pagePrincav.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }

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
    <div class="entry">
        <div class="connexion">
            <div>Créez votre Trajet :</div>
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

            <div class="overlap align3">
                <input name="heure_depart" type="time"
                       class="text-input <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $heure_depart; ?>" placeholder="Heure de Depart" required>
            </div>

            <div class="overlap align4">
                <input name="heure_arrivee" type="time"
                       class="text-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $heure_arrivee; ?>" placeholder="Heure d'arrivée" required>
            </div>

            <div class="overlap align5">
                <input name="prix" type="number"
                       class="text-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $prix; ?>" placeholder="Prix" required>
            </div>

            <div class="overlap align6">
                <input name="place" type="number"
                       class="text-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $place; ?>" placeholder="Nombre de places" required>
            </div>

            <input type="submit" class="avatar" value="Valider"></input>
        </form>
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
