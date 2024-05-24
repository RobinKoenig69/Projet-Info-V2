<?php
// Initialize the session
session_start();

$nom = $prenom = $email = $num_tel = "";
$nom_err = $prenom_err = $email_err = $num_tel_err = "";
$updated_at = date('Y-m-d H:i:s');

$email = $_SESSION['email'];
$user_ID = $_SESSION['user_ID'];

if (empty($email)) {
    header("location: ../accueil/main/pagePrincav.php");
    exit;
}

// Include config file
require_once "../BDD_login.php";

// Validate credentials
if (isset($email)){
    // Prepare a select statement
    $sql = "SELECT nom, prenom, email, num_tel, photo_profil, user_type, Photo_Permis FROM utilisateur WHERE user_ID = :user_ID";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":user_ID", $param_user_ID, PDO::PARAM_STR);
        // Set parameters
        $param_user_ID = $user_ID;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Check if the email exists, if yes then fetch the result
            if ($stmt->rowCount() == 1) {
                if ($row = $stmt->fetch()) {
                    // Retrieve individual field values
                    $nom = $row["nom"];
                    $prenom = $row["prenom"];
                    $email = $row["email"];
                    $num_tel = $row["num_tel"];
                    $photo_profil = $row["photo_profil"] ? $row["photo_profil"] : 'Default.png';
                    $user_type = $row["user_type"];
                    $Photo_Permis = $row["Photo_Permis"] ? $row["Photo_Permis"] : 'Permis.jpg';
                }
            } else {
                echo "No account found with that email.";
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        unset($stmt);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $Validated = 1;

// Validate username
    if (empty(trim($_POST["nom"]))) {
        $nom_err = "Please enter a family name.";
    } elseif (!preg_match('/^[a-zA-Z]+$/', trim($_POST["nom"]))) {
        $nom_err = "Username can only contain letters.";
    } else {
        $nom = trim($_POST["nom"]);
    }

// Validate prenom
    if (empty(trim($_POST["prenom"]))) {
        $prenom_err = "Please enter a name.";
    } elseif (!preg_match('/^[a-zA-Z]+$/', trim($_POST["prenom"]))) {
        $prenom_err = "Name can only contain letters.";
    } else {
        $prenom = trim($_POST["prenom"]);
    }

// Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

// Validate num_tel
    if (empty(trim($_POST["num_tel"]))) {
        $num_tel_err = "Please enter a phone number.";
    } elseif (!preg_match('/^[0-9]+$/', trim($_POST["num_tel"]))) {
        $num_tel_err = "Phone number can only contain numbers.";
    } else {
        $num_tel = trim($_POST["num_tel"]);
    }

// Check input errors before inserting in database
    if (empty($nom_err) && empty($prenom_err) && empty($email_err) && empty($num_tel_err) && empty($password_err)) {

        // Prepare an insert statement
        $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email, num_tel = :num_tel, updated_at = :updated_at, Validated =:Validated WHERE user_ID = :user_ID";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nom", $param_nom, PDO::PARAM_STR);
            $stmt->bindParam(":prenom", $param_prenom, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":num_tel", $param_num_tel, PDO::PARAM_STR);
            $stmt->bindParam(":updated_at", $param_updated_at, PDO::PARAM_STR);
            $stmt->bindParam(":user_ID", $param_user_ID, PDO::PARAM_STR);
            $stmt->bindParam(":Validated", $param_Validated);

            $param_user_ID = $user_ID;
            $param_nom = $nom;
            $param_prenom = $prenom;
            $param_email = $email;
            $param_num_tel = $num_tel;
            $param_updated_at = $updated_at;
            $param_Validated = $Validated;

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
    <link rel="stylesheet" href="profil.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../template.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="main-container">
    <div class="top-bar">
        <div class="logo-container">
            <div class="logo-background">
                <img src="../../images/Logo_omnes.png" alt="Logo"/>
            </div>
        </div>
        <div class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="bottom-bar">
        <a href="../trajets/recherchedestrajets/rechercheTrajets.php" target="_blank">
            <img src="../../images/loupe.png"  alt="Placeholder image">
        </a>
        <a href="../inscription/ins3/inscription3_profil.php" target="_blank">
            <img src="../../images/message.png"  alt="Placeholder image">
        </a>
        <a href="../trajets/creationtrajet/newTrajet.php" target="_blank">
            <img src="../../images/plus1.png"  alt="Placeholder image">
        </a>
        <a href="../trajets/mesTrajets/mesTrajets.php" target="_blank">
            <img src="../../images/voiture.png"  alt="Placeholder image">
        </a>
    </div>
    <div class="avatar"></div>
    <img src="../../Stockage/PP/<?php echo $photo_profil ?>" alt="Placeholder image" class="placeholder-image"/>
    <img src="../../Stockage/Permis/<?php echo $Photo_Permis ?>" alt="Placeholder image" class="placeholder-image2"/>
    <div class="small-circle"></div>
    <div class="entry">
        <div class="connexion">
            <div>Profil :</div>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="overlap align">
                <input name="nom" type="nom" class="text-input <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $nom; ?>" placeholder="Nom" required>
            </div>

            <div class="overlap align2">
                <input name="prenom" type="prenom"
                       class="text-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $prenom; ?>" placeholder="Prenom" required>
            </div>

            <div class="overlap align3">
                <input name="num_tel" type="num_tel"
                       class="text-input <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $num_tel; ?>" placeholder="Tel" required>
            </div>

            <div class="overlap align4">
                <input name="email" type="email"
                       class="text-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"
                       value="<?php echo $email; ?>" placeholder="Email" required>
            </div>

            <div class="button-container">
                <input type="submit" class="button" value="Valider Profil">
            </div>
        </form>
    </div>
</div>
</body>
</html>