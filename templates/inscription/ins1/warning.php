<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>BlaBla Omnes</title>
    <link rel="stylesheet" href="inscription1.css?v=<?php echo time(); ?>">
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
        <a href="../../profil/profil.php" target="_blank">
            <img src="../../../images/user.png"  alt="Placeholder image">
        </a>
        <a href="../../trajets/creationtrajet/newTrajet.php" target="_blank">
            <img src="../../../images/plus1.png" alt="Placeholder image">
        </a>
        <a href="../../trajets/mesTrajets/mesTrajets.php" target="_blank">
            <img src="../../../images/voiture.png" alt="Placeholder image">
        </a>
    </div>


    <div class="small-circle"></div>
    <div >
        <div class="connexion connexion2">
            <div>Regardez votre messagerie !</div>
        </div>

        <img src="../../../images/warning.jpg" alt="Placeholder image" class="warning">

    <div class="logout-icon">
        <a href="../../Connexion/logout.php">
            <img  class="logout-icon" src="../../../images/deconnexion.png" alt="Déconnexion" />
        </a>
    </div>
</div>
</body>
</html>