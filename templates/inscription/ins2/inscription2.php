<?php

require_once '../../BDD_login.php';  // Connexion à la base de données

$path = '../../../Stockage/PP/';

$file_err ="";

session_start();
$email = $_SESSION["email"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo $email;
    if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === 0) {
        // Testons, si le fichier est trop volumineux
        if ($_FILES['screenshot']['size'] > 1000000) {
            $file_err = "L'envoi n'a pas pu être effectué, erreur ou image trop volumineuse";
            return;
        }

        // Testons, si l'extension n'est pas autorisée
        $fileInfo = pathinfo($_FILES['screenshot']['name']);
        $extension = $fileInfo['extension'];
        $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
        if (!in_array($extension, $allowedExtensions)) {
            $file_err = "L'envoi n'a pas pu être effectué, l'extension {$extension} n'est pas autorisée";
            return;
        }
        // Testons, si le dossier uploads est manquant
        if (!is_dir($path)) {
            $file_err = "L'envoi n'a pas pu être effectué, le dossier uploads est manquant";
            return;
        }
        // On peut valider le fichier et le stocker définitivement
        move_uploaded_file($_FILES['screenshot']['tmp_name'], $path . basename($_FILES['screenshot']['name']));

        $file_name = $_FILES['screenshot']['name'];

        $stmt = $pdo->prepare("UPDATE utilisateur SET photo_profil = :file_name WHERE email = :email");
        $stmt->bindParam(':file_name', $file_name);
        $stmt->bindParam(':email', $email);

        if($stmt->execute()) {
            header("location: ../ins3/inscription3.php?email=$email");
            exit;
        } else {
            $file_err = "Une erreur est survenue lors de l'enregistrement de l'image.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="inscription2.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../../template.css?v=<?php echo time(); ?>">
</head>
<body>
<div class="connexion">
    <div>Ajoutez votre photo de profil :</div>
</div>
<div class="top-bar">
    <div class="logo-container">
        <div class="logo-background">
            <img src="../../../images/Logo_omnes.png" alt="Logo" />
            <script src="inscription2.js"></script>
        </div>
    </div>
    <div class="menu-icon">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
<div class="bottom-bar"></div>
<img src="../../../images/imagevoiture.png" alt="Placeholder image" class="placeholder-image"/>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <div class="button-container">
        <label for="myFile" class="label-button">Choisir</label>
        <input type="file" id="myFile" name="screenshot" accept="image/*">
        <span class="invalid-feedback"><?php echo $file_err; ?></span>
    </div>
    <button type="submit" class="avatar">Upload</button>
</form>
</body>
</html>
