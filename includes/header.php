<?php
require_once 'config.php';
require_once 'class.user.php';

$user = new USER($conn);

if (isset($_POST['logout'])) {
    $user->logout();
}

// Menu links for guests
$menuLinks = [
    ["title" => "Hem", "url" => "index.php"],
    ["title" => "Logga in", "url" => "login.php"],
];
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Qvintus - Inloggningssystem</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <nav id="navigation">
        <a href="index.php">
            <!--<img src="images/Qvintus_Logo.png" style="height: 60px; width: 120px;">-->
        </a>

    <a href="search_page.php" class="btn btn-sm btn-warning mt-2">List of tools</a>
    <a href="search_user.php" class="btn btn-sm btn-warning mt-2">List of users</a>
    <a href="addnew.php" class="btn btn-sm btn-warning mt-2">Add info</a>
    <form method="POST" style="display:inline;">
        <button type="submit" name="logout" class="btn btn-sm btn-warning mt-2">Log out</button>
    </form>
    </nav>
</header>