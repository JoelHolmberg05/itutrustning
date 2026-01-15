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
    ["title" => "Böcker", "url" => "search_page.php"],
    ["title" => "Exklusivt", "url" => "exclusives.php"],
    ["title" => "Om oss", "url" => "about_us.php"],
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
            <img src="images/Qvintus_Logo.png" style="height: 60px; width: 120px;">
        </a>

        <?php if ($user->checkUserLogInStatus()): ?>
            <a href="../index.php" class="button">Hem</a>

            <?php if ($user->checkUserRole(50)): ?>
                <a href="admin.php" class="button">Adminpanel</a>
            <?php endif; ?>

            <form method="post" action="" style="display: inline;">
                <input type="submit" value="Logga ut" name="logout" class="button">
            </form>

        <?php else: ?>
            <?php foreach ($menuLinks as $link): ?>
                <a href="<?= $link['url'] ?>" class="nav-button"><?= $link['title'] ?></a>
            <?php endforeach; ?>
        <?php endif; ?>
    </nav>
</header>