<?php

require_once '../config/Database.php';
require_once '../Includes/User.php';

$messageClass = "";

if (isset($_POST['email'], $_POST['password'])) {
    $db = new Database();
    $user = new User($db);

    $authenticatedUser = $user->authenticateUser($_POST['email'], $_POST['password']);
    if ($authenticatedUser) {
        $message = "User authenticated successfully!";
        $messageClass = "success";
    } else {
        $message = "Failed to authenticate user. Please check your inputs.";
        $messageClass = "error";
    }
} else {

    $message = "Please fill in all the fields.";
    $messageClass = "error";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Authentication Result</h1>
    <p class="<?php echo $messageClass; ?>">
        <?php echo $message; ?>
    </p>
    <div class="navigation">
        <a href="../pages/index.php" class="btn">Home</a>
        <?php if ($messageClass == "success"): ?>
            <a href="../pages/register.php" class="btn">Register</a>
        <?php else: ?>
            <a href="../pages/login.php" class="btn">Retry</a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>