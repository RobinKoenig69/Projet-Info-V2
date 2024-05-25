<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$email = $_SESSION['email'];
$admin = "admin";

if (empty($email)) {
    header("location: ../accueil/main/pagePrincav.php");
    exit;
}

if ($_SESSION["type"] == $admin){
    $_SESSION['email'] = $email;
    header("Location: profil_edit_admin.php");
}

// Include config file
require_once "../BDD_login.php";

// Validate credentials
if (isset($email)){
    // Prepare a select statement
    $sql = "SELECT nom, prenom, email, num_tel, photo_profil, user_type, Photo_Permis FROM utilisateur WHERE email = :email";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
        // Set parameters
        $param_email = $email;

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

    <a href="profil_edit.php">
        <input type="submit" class="avatar" value="Editer"></input>
    </a>

    <img src="../../Stockage/PP/<?php echo $photo_profil ?>" alt="Placeholder image" class="placeholder-image"/>
    <img src="../../Stockage/Permis/<?php echo $Photo_Permis ?>" alt="Placeholder image" class="placeholder-image2"/>
    <div class="entry">
        <div class="connexion">
            <div>Profil :</div>
        </div>

        <div class="overlap align">
            <div class="info">Nom : <?php echo $nom ?></div>
        </div>

        <div class="overlap align2">
            <div class="info">Prenom : <?php echo $prenom ?></div>
        </div>

        <div class="overlap align3">
            <div class="info">Tel : <?php echo $num_tel ?></div>
        </div>

        <div class="overlap align4">
            <div class="info">Email : <?php echo $email ?></div>
        </div>

        <div class="overlap align5">
            <div class="info">Status : <?php echo $user_type ?></div>
        </div>

    </div>
    <div class="logout-icon">
        <a href="../Connexion/logout.php">
            <img  class="logout-icon" src="../../images/deconnexion.png" alt="Déconnexion" />
        </a>
    </div>
</div>
</body>
</html>