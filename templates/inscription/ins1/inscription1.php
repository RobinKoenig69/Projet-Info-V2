<?php
include '../../../index2.php';  // Connexion à la base de données

try {
    // Récupérer le dernier user_ID utilisé et l'incrémenter
    $query = $connexion->query("SELECT MAX(user_ID) as max_id FROM utilisateur");
    $result = $query->fetch();
    $new_user_id = $result['max_id'] + 1;

    // Données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $num_tel = htmlspecialchars($_POST['num_tel']);
    $pwd = password_hash($_POST['pwd'], PASSWORD_DEFAULT);

    // Préparation de la requête SQL d'insertion
    $sql = "INSERT INTO utilisateur (user_ID, nom, prenom, email, num_tel, pwd, user_type, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, 'passager', NOW(), NOW())";

    $stmt = $connexion->prepare($sql);
    if ($stmt->execute([$new_user_id, $nom, $prenom, $email, $num_tel, $pwd])) {
        echo "Nouvel enregistrement créé avec succès.";
    } else {
        echo "Erreur lors de l'inscription : " . $stmt->errorInfo()[2];
    }
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="inscription1.css" />
</head>
<body>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="page-inscription">
        <div class="div">
            <div class="overlap">
                <input type="email" name="email" class="text-input" placeholder="E-mail" required>
            </div>
            <div class="overlap-group">
                <input type="tel" name="num_tel" class="text-input" placeholder="Numero de téléphone" required>
            </div>
            <div class="div-wrapper">
                <input type="text" name="nom" class="text-input" placeholder="Nom" required>
            </div>
            <div class="overlap-2">
                <input type="text" name="prenom" class="text-input" placeholder="Prenom" required>
            </div>
            <div class="overlap-3">
                <input type="password" name="pwd" class="text-input" placeholder="Mot de passe" required>
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
