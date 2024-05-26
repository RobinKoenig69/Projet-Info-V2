<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["validated"] = 1;

    header("Location: ../ins2/inscription2.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Success</title>
    <link rel="stylesheet" href="inscription1.css">
</head>
<body>
<div class="container">
    <h2>Verification Successful</h2>
    <p>Your email has been successfully verified. You can now log in to your account.</p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

        <button type="submit">
            <div>Continue</div>
        </button>
        <form
</div>
</body>
</html>
