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
require_once "includes/db.php"; // PDO connection

// Get search term
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$results = [];

$stmt = $conn->prepare("
    SELECT u.u_id, u.u_name, u.u_password, d.d_name as department
    FROM user_table u
    LEFT JOIN department_table d ON u.u_department_fk = d.d_id
    WHERE (u.u_name LIKE :search OR d.d_name LIKE :search) AND u.u_name != 'N/A'
    ORDER BY u.u_id DESC
    LIMIT 50
");
$stmt->execute([':search' => "%$search%"]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Search Users – <?= htmlspecialchars($search) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="main-container">
    <h2>Search Users</h2>
    
    <form method="GET" action="" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by username or department..." value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <?php if (!empty($search)): ?>
        <h3>Results for: "<?= htmlspecialchars($search) ?>"</h3>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <div class="card-grid">
            <?php foreach ($results as $user): ?>
                <?php 
                    // Fetch equipment for this user
                    $equipStmt = $conn->prepare("
                        SELECT t.t_id, t.t_name, t.t_type, t.t_snumber, s.s_name as status
                        FROM tool_table t
                        LEFT JOIN status_table s ON t.t_status_fk = s.s_id
                        WHERE t.t_user_fk = :user_id
                        ORDER BY t.t_id DESC
                    ");
                    $equipStmt->execute([':user_id' => $user['u_id']]);
                    $equipment = $equipStmt->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="card">
                    <div class="card-content">
                        <h4><?= htmlspecialchars($user['u_name']) ?></h4>
                        <p class="genre"><?= htmlspecialchars($user['department'] ?? 'No Department') ?></p>
                        
                        <hr>
                        <p><strong>Assigned Equipment:</strong></p>
                        <?php if (!empty($equipment)): ?>
                            <ul style="font-size: 0.9em; margin: 0; padding-left: 20px;">
                                <?php foreach ($equipment as $tool): ?>
                                    <li><?= htmlspecialchars($tool['t_name']) ?> (<?= htmlspecialchars($tool['t_type']) ?>)</li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p><em>No equipment assigned</em></p>
                        <?php endif; ?>
                        
                        <a href="edit_user.php?id=<?= $user['u_id'] ?>" class="btn btn-sm btn-warning mt-2">Edit</a>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No users found</p>
    <?php endif; ?>

    <a href="addnew.php" class="btn btn-secondary mt-4">Back</a>
</div>
</body>
</html>