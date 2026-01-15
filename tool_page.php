<?php
include "includes/header.php";
require_once "includes/db.php"; // PDO connection

// ✅ Hämta sökterm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$results = [];

if (!empty($search)) {
    $stmt = $conn->prepare("
        SELECT t.t_id, t.t_name, t.t_snumber, t.t_type, 
               s.s_name as status, u.u_name as user, r.r_name as room
        FROM tool_table t
        LEFT JOIN status_table s ON t.t_status_fk = s.s_id
        LEFT JOIN user_table u ON t.t_user_fk = u.u_id
        LEFT JOIN room_table r ON t.t_room_fk = r.r_id
        WHERE (t.t_name LIKE :search OR t.t_type LIKE :search OR u.u_name LIKE :search)
        ORDER BY t.t_id DESC
        LIMIT 50
    ");
    $stmt->execute([':search' => "%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Search Tools – <?= htmlspecialchars($search) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="main-container">



    <?php if (!empty($results)): ?>
        <div class="card-grid">
            <?php foreach ($results as $tool): ?>
                <div class="card" data-bs-toggle="modal" data-bs-target="#toolModal<?= $tool['t_id'] ?>">
                    <div class="card-content">
                        <h4><?= htmlspecialchars($tool['t_name']) ?></h4>
                        <p class="genre"><?= htmlspecialchars($tool['t_type']) ?></p>
                        <p class="desc">Serial: <?= htmlspecialchars($tool['t_snumber']) ?></p>
                    </div>
                </div>

                <!-- Modal with more info -->
                <div class="modal fade" id="toolModal<?= $tool['t_id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= htmlspecialchars($tool['t_name']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Type:</strong> <?= htmlspecialchars($tool['t_type']) ?></p>
                                <p><strong>Serial Number:</strong> <?= htmlspecialchars($tool['t_snumber']) ?></p>
                                <p><strong>Status:</strong> <?= htmlspecialchars($tool['status'] ?? 'Unknown') ?></p>
                                <p><strong>User:</strong> <?= htmlspecialchars($tool['user'] ?? 'N/A') ?></p>
                                <p><strong>Room:</strong> <?= htmlspecialchars($tool['room'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No tools found</p>
    <?php endif; ?>

    <a href="front_page.php" class="btn btn-secondary mt-4">⬅ Back</a>
</div>
</body>
</html>

<?php include "includes/footer.php"; ?>
