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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Prepare an insert statement
    $sql = "DELETE FROM utilisateur WHERE email = :email";

    if ($stmt = $pdo->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

        $param_email = $email;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            session_destroy();
            header("location: ../accueil/main/pagePrincav.php");
            exit;
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
            <img src="../../images/loupe.png" alt="Placeholder image">
        </a>
        <a href="../profil/profil.php" target="_blank">
            <img src="../../images/user.png" alt="Placeholder image">
        </a>
        <a href="../trajets/creationtrajet/newTrajet.php" target="_blank">
            <img src="../../images/plus1.png" alt="Placeholder image">
        </a>
        <a href="../trajets/mesTrajets/mesTrajets.php" target="_blank">
            <img src="../../images/voiture.png" alt="Placeholder image">
        </a>
    </div>

    <div class="entry">
        <div class="connexion">
            <div>Etes vous sûr de vouloir supprimer votre compte ?</div>
        </div>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


            <button type="submit" class="delete_account2">Supprimer le compte</button>
        </form>
    </div>
    <div class="logout-icon">
        <a href="../Connexion/logout.php">
            <img class="logout-icon" src="../../images/deconnexion.png" alt="Déconnexion"/>
        </a>
    </div>
</div>
</body>
</html>