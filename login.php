<?php
require_once 'includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the query
    $stmt = $conn->prepare("
        SELECT u.*
        FROM user_table u
        WHERE u.u_name = ?
    ");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['u_password'])) {
        // Successful login
        $_SESSION['user_id'] = $user['u_id'];
        $_SESSION['user'] = $user['u_name'];
        $_SESSION['username'] = $user['u_name'];
        $_SESSION['u_role_fk'] = $user['u_role_fk'];
        $_SESSION['role_name'] = 'User'; // Default
        $_SESSION['role_level'] = $user['u_role_fk'];

        header("Location: addnew.php");
        exit();
    } else {
        $error = "Felaktigt användarnamn eller lösenord.";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Logga in</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>

<div class="main-container">
    <h2>Logga in</h2>
    <?php if ($error): ?>
        <p style="color:red;" class="text-danger"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="username" class="form-label">Användarnamn:</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Lösenord:</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Logga in</button>
    </form>
</div>

</body>
</html>
