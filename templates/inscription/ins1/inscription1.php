<?php
require_once '../../BDD_login.php';  // Connexion à la base de données

$nom = $prenom = $email = $num_tel = $password = "";
$nom_err = $password_err = $prenom_err = $email_err = $num_tel_err = "";
$created_at = date('Y-m-d H:i:s');
$updated_at = date('Y-m-d H:i:s');

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

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

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    {
        // Prepare a select statement
        $sql = "SELECT user_ID FROM utilisateur WHERE nom = :nom";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nom", $param_nom, PDO::PARAM_STR);

            // Set parameters
            $param_nom = trim($_POST["nom"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $nom_err = "This username is already taken.";
                } else{
                    $nom = trim($_POST["nom"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Check input errors before inserting in database
    if (empty($nom_err) && empty($prenom_err) && empty($email_err) && empty($num_tel_err) && empty($password_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO utilisateur (nom, prenom, email, num_tel, pwd, created_at, updated_at) VALUES (:nom, :prenom, :email, :num_tel, :password, :created_at, :updated_at)";

        if ($stmt = $pdo->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":nom", $param_nom, PDO::PARAM_STR);
            $stmt->bindParam(":prenom", $param_prenom, PDO::PARAM_STR);
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":num_tel", $param_num_tel, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":created_at", $param_created_at, PDO::PARAM_STR);
            $stmt->bindParam(":updated_at", $param_updated_at, PDO::PARAM_STR);

            // Set parameters
            $param_nom = $nom;
            $param_prenom = $prenom;
            $param_email = $email;
            $param_num_tel = $num_tel;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_created_at = $created_at;
            $param_updated_at = $updated_at;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                session_start();
                $_SESSION["email"] = $email;
                header("Location: ../ins2/inscription3.php");
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
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="inscription1.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../template.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="connexion">
    <div>Inscrivez-vous :</div>
</div>
    <div class="top-bar">
        <div class="logo-container">
            <div class="logo-background">
                <img src="../../../images/Logo_omnes.png" alt="Logo" />
            </div>
        </div>
        <div class="menu-icon">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="bottom-bar"></div>
    <div class="avatar"></div>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="page-inscription">
        <div class="div">
            <div class="overlap">
                <input type="email" name="email" class="text-input <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="E-mail" required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="overlap-group">
                <input type="tel" name="num_tel" class="text-input <?php echo (!empty($num_tel_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $num_tel; ?>" placeholder="Numero de téléphone" required>
                <span class="invalid-feedback"><?php echo $num_tel_err; ?></span>
            </div>
            <div class="div-wrapper">
                <input type="text" name="nom" class="text-input <?php echo (!empty($nom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nom; ?>" placeholder="Nom" required >
                <span class="invalid-feedback"><?php echo $nom_err; ?></span>
            </div>
            <div class="overlap-2">
                <input type="text" name="prenom" class="text-input <?php echo (!empty($prenom_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $prenom; ?>" placeholder="Prenom" required>
                <span class="invalid-feedback"><?php echo $prenom_err; ?></span>
            </div>
            <div class="overlap-3">
                <input type="password" name="password" class="text-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" placeholder="Mot de passe" required>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="se-connecter">
                <button type="submit" class="overlap-group-2">
                    <div class="rectangle-2"></div>
                    <div class="text-wrapper-5">Suivant</div>
                </button>
            </div>
        </div>
    </div>
</form>
</body>
</html>
