<?php
include "includes/header.php";
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
if (!$user->checkUserRole(2)) {
    header("Location: login.php");
    exit;
}
require_once "includes/db.php";
require_once "includes/class.user.php";

// Get user ID from URL
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id == 0) {
    echo "Invalid user ID";
    exit;
}

// Fetch user data
$stmt = $conn->prepare("
    SELECT u.u_id, u.u_name, u.u_department_fk, d.d_name as department
    FROM user_table u
    LEFT JOIN department_table d ON u.u_department_fk = d.d_id
    WHERE u.u_id = :id
");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found";
    exit;
}

// Get department options
$userObj = new user($conn);
$departments = $userObj->getDepartments();

// Handle form submission
$message = '';
if (isset($_POST['update'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $department_id = $_POST['u_department'];
    
    $stmt = $conn->prepare("
        UPDATE user_table 
        SET u_name = :name, u_password = :password, u_department_fk = :department
        WHERE u_id = :id
    ");
    
    $success = $stmt->execute([
        ':name' => $username,
        ':password' => password_hash($password, PASSWORD_DEFAULT),
        ':department' => $department_id,
        ':id' => $user_id
    ]);
    
    if ($success) {
        $message = "<p class='bg-success text-white text-center'>User updated successfully! <a href='search_user.php'>Back to search</a></p>";
    } else {
        $message = "<p class='bg-danger text-white text-center'>Error updating user</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Edit User – <?= htmlspecialchars($user['u_name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="main-container">
    <h2>Edit User: <?= htmlspecialchars($user['u_name']) ?></h2>
    
    <?= $message ?>
    
    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['u_name']) ?>" required><br><br>

        <label for="u_department">Department:</label>
        <select id="u_department" name="u_department" required>
            <option value="">Select Department</option>
            <?php foreach ($departments as $d): ?>
                <option value="<?= $d['d_id'] ?>" <?= $user['u_department_fk'] == $d['d_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($d['d_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confpassword">Confirm Password:</label>
        <input type="password" id="confpassword" name="confpassword" required><br><br>


        <button type="submit" name="update" class="btn btn-success">Update User</button>
        <a href="search_user.php" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confpassword = document.getElementById('confpassword').value;
            
            if (password !== confpassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</div>

</body>
</html>
