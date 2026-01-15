<?php
session_start();
require_once 'includes/db.php';
include "includes/header.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query
    $stmt = $conn->prepare("
        SELECT u.*, r.role_name, r.role_level
        FROM t_user u
        JOIN t_roles r ON u.role_id_fk = r.role_id
        WHERE u.username = ?
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Successful login
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role_name'] = $user['role_name'];
        $_SESSION['role_level'] = $user['role_level'];

        header("Location: front_page.php");
        exit();
    } else {
        $error = "Felaktigt användarnamn eller lösenord.";
    }
}
?>

<h2>Logga in</h2>
<?php if ($error): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form method="POST">
    <label>Användarnamn:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Lösenord:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Logga in</button>
</form>
