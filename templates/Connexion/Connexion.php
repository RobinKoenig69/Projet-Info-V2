<?php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// Include config file
require_once "../BDD_login.php";

// Define variables and initialize with empty values
$email = $password = "";
$email_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = $_POST["email"];
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($email_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT user_ID, email, pwd FROM utilisateur WHERE email = :email";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            // Set parameters
            $param_email = trim($_POST["email"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["user_ID"];
                        $email = $row["email"];
                        $hashed_password = $row["pwd"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;

                            $_SESSION["user_ID"] = $id;
                            $_SESSION["email"] = $email;

                            // Redirect user to welcome page
                            header("location: ../trajets/mesTrajets/mesTrajets.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid email or password.";
                }
            } else{
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BlaBla Omnes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="Connexion.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../template.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="main-container">
    <div class="top-bar">
        <div class="logo-container">
            <div class="logo-background">
                <img src="../../images/Logo_omnes.png" alt="Logo" />
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
    <img src="../../images/imagevoiture.png" alt="Placeholder image" class="placeholder-image"/>
    <div class="small-circle"></div>
    <div class="entry">
        <div class="connexion">
            <div>Connectez-vous :</div>
        </div>

        <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="overlap">
                <input name="email" type="email" class="text-input <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" placeholder="Email" required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="overlap-2">
                <input name="password" type="password" class="text-input <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" placeholder="Mot de passe" required>
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="button-container">
                <input type="submit" class="button" value="Se connecter">
            </div>
        </form>
    </div>
    </div>
</body>
</html>