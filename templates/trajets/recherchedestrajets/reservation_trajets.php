<?php
// Initialize the session
session_start();

$email = $_SESSION['email'];
$user_ID = $_SESSION['user_ID'];
$reservation = $_SESSION['voyage_reserve'];

$adresse_depart = $_SESSION["voyage_depart"];
$adresse_arrivee = $_SESSION["voyage_arrivee"];

$erreur="";

$fumeur = $heure_depart = $heure_arrivee = $prix = $place = "";

if (!isset($user_ID)){
    header("Location: ../../accueil/main/pagePrincav.php");
    exit;
}

require_once "../../BDD_login.php";

if (isset($reservation)) {
    // Prepare a select statement
    $sql = "SELECT heure_depart, heure_arrivee, prix, place, fumeur FROM voyages WHERE voyage_ID = :reservation";

    if ($stmt = $pdo->prepare($sql)) {
        // Liez les paramètres
        $stmt->bindParam(":reservation", $reservation);

        if ($stmt->execute()) {
            // Vérifiez s'il y a des enregistrements
            if ($stmt->rowCount() > 0) {
                // Récupérez toutes les adresses et affichez-les
                while ($row = $stmt->fetch()) {
                    $heure_depart = $row["heure_depart"];
                    $heure_arrivee = $row["heure_arrivee"];
                    $prix = $row["prix"];
                    $place = $row["place"];
                    $fumeur = $row['fumeur'];
                }
            } else {
                $erreur = "No addresses found in the table.";
            }
        } else {
            $erreur = "Oops! Something went wrong. Please try again later.";
        }
    } else {
        $erreur = "Failed to prepare the SQL statement.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_place = $place - 1;

    if ($new_place < 0){
        $erreur = "Plus de places disponibles";
    }

    if (empty($erreur)){
        $sql = "UPDATE voyages SET place = :new_place WHERE voyage_ID = :reservation";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":new_place", $new_place);
            $stmt->bindParam(":reservation", $reservation);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Code to execute if the update is successful
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }

        $siege_reserve = 1;
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
            $param_voyage_ID = $reservation;
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
    <link rel="stylesheet" href="rechercheTrajets.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../template.css?v=<?php echo time(); ?>">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZPX2ee3ukXiDkpm3ZSUTYzeuJn-ttahU&libraries=places"></script>
    <script src="../../maps/mapsjs.js"></script>
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
    <div id="map"></div>

    <div class="output2">
        <?php
        echo '<div class="flex">';
        echo '<span>Voyage:</span>';
        echo '<span>Heure de départ: ' . $heure_depart . '</span>';
        echo '<span>Heure d\'arrivée: ' . $heure_arrivee . '</span>';
        echo '<span>Prix: ' . $prix . '€</span>';
        echo '<span>Places Libres: ' . $place . '</span>';
        echo '<span>Fumeur: ' . $fumeur . '</span>';
        echo '</div>';
        echo isset($erreur) ? $erreur : '';
        ?>
    </div>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="se-connecter">
            <button type="submit" class="overlap-group-2">
                <div class="rectangle-2"></div>
                <div class="text-wrapper-5">Réserver</div>
            </button>
        </div>
    </form>

    <div class="logout-icon">
        <a href="../../Connexion/logout.php">
            <img  class="logout-icon" src="../../../images/deconnexion.png" alt="Déconnexion" />
        </a>
    </div>
</body>
</html>

<script>
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 48.8566, lng: 2.3522 },
            zoom: 7
        });

        var directionsService = new google.maps.DirectionsService();
        var directionsRenderer = new google.maps.DirectionsRenderer();
        directionsRenderer.setMap(map);

        calculateAndDisplayRoute(directionsService, directionsRenderer);
    }

    function calculateAndDisplayRoute(directionsService, directionsRenderer) {
        var start = <?php echo json_encode($adresse_depart); ?>;
        var end = <?php echo json_encode($adresse_arrivee); ?>;

        directionsService.route({
            origin: start,
            destination: end,
            travelMode: 'DRIVING'
        }, function (response, status) {
            if (status === 'OK') {
                directionsRenderer.setDirections(response);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
    }

    window.onload = initMap;
</script>
