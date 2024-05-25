<?php

require_once "../BDD_login.php";

$error = "";



// Function to add a campus
function addCampus($adresse, $nom)
{
    global $pdo;
    $sql = "INSERT INTO campus (adresse, nom) VALUES (:adresse, :nom)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();
    return $stmt->rowCount();
}

// Function to update a campus
function updateCampus($id, $adresse, $nom)
{
    global $pdo;
    $sql = "UPDATE campus SET adresse = :adresse, nom = :nom WHERE Campus_ID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->rowCount();
}

function deleteCampus($id)
{
    global $pdo;
    $sql = "DELETE FROM campus WHERE Campus_ID = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->rowCount();
}

function checkcampus($id)
{
    global $pdo;
    $sql = "SELECT Campus_ID_Depart & Campus_ID  FROM voyages WHERE (Campus_ID = :id OR Campus_ID_Depart = :id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (checkcampus($_POST['Campus_ID'])) {
        $error = "<p>Impossible de supprimer ce campus car il est utilisé dans un voyage.</p>";
    } else {
        $error = "<p>Impossible de supprimer ce campus car il est utilisé dans un voyage.</p>";
    }
    if (empty($error)) {
        if (isset($_POST['add'])) {
            $adresse = $_POST['adresse'];
            $nom = $_POST['nom'];
            if (addCampus($adresse, $nom)) {
                echo "<p>Campus added successfully!</p>";
            } else {
                $error = "<p>Error adding campus.</p>";
            }
        } elseif (isset($_POST['update'])) {
            // Updating a campus
            $id = $_POST['campus_id'];
            $adresse = $_POST['adresse'];
            $nom = $_POST['nom'];
            if (updateCampus($id, $adresse, $nom)) {
                echo "<p>Campus updated successfully!</p>";
            } else {
                $error = "<p>Error updating campus.</p>";
            }
        } elseif (isset($_POST['delete'])) {
            // Deleting a campus
            $id = $_POST['campus_id'];
            if (deleteCampus($id)) {
                echo "<p>Campus deleted successfully!</p>";
            } else {
                $error = "<p>Error deleting campus.</p>";
            }
        }
    }
}



// Function to fetch all users
function fetchUsers() {
    global $pdo;
    $sql = "SELECT user_ID, nom, prenom, email, num_tel FROM utilisateur";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch all voyages
function fetchVoyages() {
    global $pdo;
    $sql = "SELECT v.voyage_ID, v.heure_depart, v.heure_arrivee, v.prix, u.nom AS driver_name 
            FROM voyages v 
            JOIN utilisateur u ON v.user_ID = u.user_ID";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Functions to modify and delete data
function updateUser($userId, $nom, $prenom, $email, $num_tel) {
    global $pdo;
    $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email, num_tel = :num_tel WHERE user_ID = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':num_tel', $num_tel);
    $stmt->execute();
    return $stmt->rowCount();
}

function updateVoyage($voyageId, $heure_depart, $heure_arrivee, $prix) {
    global $pdo;
    $sql = "UPDATE voyages SET heure_depart = :heure_depart, heure_arrivee = :heure_arrivee, prix = :prix WHERE voyage_ID = :voyageId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':heure_depart', $heure_depart);
    $stmt->bindParam(':heure_arrivee', $heure_arrivee);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':voyageId', $voyageId);
    $stmt->execute();
    return $stmt->rowCount();
}

function fetchDrivers() {
    global $pdo;
    $sql = "SELECT user_ID, nom, prenom FROM utilisateur WHERE user_type = 'conducteur' AND Validated = 0";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function validateDriverLicense($userId, $isValid) {
    global $pdo;
    $sql = "UPDATE utilisateur SET Validated = :isValid WHERE user_ID = :userId AND user_type = 'conducteur'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':isValid', $isValid, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->rowCount();
}
// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['updateUser'])) {
        $userId = $_POST['user_id'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $num_tel = $_POST['num_tel'];
        if(updateUser($userId, $nom, $prenom, $email, $num_tel)) {
            echo "<p>User updated successfully!</p>";
        } else {
            echo "<p>Error updating user.</p>";
        }
    } elseif (isset($_POST['updateVoyage'])) {
        $voyageId = $_POST['voyage_id'];
        $heure_depart = $_POST['heure_depart'];
        $heure_arrivee = $_POST['heure_arrivee'];
        $prix = $_POST['prix'];
        if(updateVoyage($voyageId, $heure_depart, $heure_arrivee, $prix)) {
            echo "<p>Voyage updated successfully!</p>";
        } else {
            echo "<p>Error updating voyage.</p>";
        }
    }
    elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['validateLicense'])) {
        $userId = $_POST['user_id'];
        if(validateDriverLicense($userId, 1)) {  // Set `Validated` to 1
            echo "<p>Driver's license validated successfully!</p>";
        } else {
            echo "<p>Error validating driver's license.</p>";
        }
    }
}


// Functions to modify and delete data (simplified version)
function deleteUser($userId)
{
    global $pdo;
    $sql = "DELETE FROM utilisateur WHERE user_ID = :userId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':userId', $userId);
    $stmt->execute();
    return $stmt->rowCount();
}

function deleteVoyage($voyageId)
{
    global $pdo;
    $sql = "DELETE FROM voyages WHERE voyage_ID = :voyageId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':voyageId', $voyageId);
    $stmt->execute();
    return $stmt->rowCount();
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle user and voyage deletion from form submission
    if (isset($_POST['deleteUser'])) {
        $userId = $_POST['user_id'];
        if (deleteUser($userId)) {
            echo "<p>User deleted successfully!</p>";
        } else {
            echo "<p>Error deleting user.</p>";
        }
    } elseif (isset($_POST['deleteVoyage'])) {
        $voyageId = $_POST['voyage_id'];
        if (deleteVoyage($voyageId)) {
            echo "<p>Voyage deleted successfully!</p>";
        } else {
            echo "<p>Error deleting voyage.</p>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Interface</title>
</head>

<body>
    <h1>Admin Panel - Campus Management</h1>
    <form method="post">
        <h2>Add Campus</h2>
        Adresse: <input type="text" name="adresse" required><br>
        Nom: <input type="text" name="nom" required><br>
        <button type="submit" name="add">Add Campus</button>
    </form>

    <form method="post">
        <h2>Update Campus</h2>
        Campus ID: <input type="number" name="campus_id" required><br>
        New Adresse: <input type="text" name="adresse" required><br>
        New Nom: <input type="text" name="nom" required><br>
        <button type="submit" name="update">Update Campus</button>
    </form>

    <form method="post">
        <h2>Delete Campus</h2>
        Campus ID: <input type="number" name="campus_id" required><br>
        <button type="submit" name="delete">Delete Campus</button>
    </form>
    <p><?php echo $error; ?></p>
    <section>
        <h2>Users</h2>
        <?php
        $users = fetchUsers();
        foreach ($users as $user) {
            echo "<div>{$user['nom']} {$user['prenom']} - {$user['email']} - {$user['num_tel']}
                  <form method='post'>
                      <input type='hidden' name='user_id' value='{$user['user_ID']}'>
                      <input type='text' name='nom' value='{$user['nom']}'>
                      <input type='text' name='prenom' value='{$user['prenom']}'>
                      <input type='text' name='email' value='{$user['email']}'>
                      <input type='text' name='num_tel' value='{$user['num_tel']}'>
                      <button type='submit' name='updateUser'>Update User</button>
                      <button type='submit' name='deleteUser'>Delete User</button>
                  </form>
                 </div>";
        }
        ?>
    </section>

    <section>
        <h2>Voyages</h2>
        <?php
        $voyages = fetchVoyages();
        foreach ($voyages as $voyage) {
            echo "<div>Voyage ID: {$voyage['voyage_ID']}, Depart: {$voyage['heure_depart']}, Arrivee: {$voyage['heure_arrivee']}
                  <form method='post'>
                      <input type='hidden' name='voyage_id' value='{$voyage['voyage_ID']}'>
                      <input type='text' name='heure_depart' value='{$voyage['heure_depart']}'>
                      <input type='text' name='heure_arrivee' value='{$voyage['heure_arrivee']}'>
                      <input type='text' name='prix' value='{$voyage['prix']}'>
                      <button type='submit' name='updateVoyage'>Update Voyage</button>
                      <button type='submit' name='deleteVoyage'>Delete Voyage</button>

                  </form>
                 </div>";
        }
        ?>
    </section>
</section>

</body>

</html>