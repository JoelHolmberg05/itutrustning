<?php
include "includes/header.php";
require_once "includes/db.php";

// Get tool ID from URL
$tool_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($tool_id == 0) {
    echo "Invalid tool ID";
    exit;
}

// Fetch tool data
$stmt = $conn->prepare("
    SELECT t.t_id, t.t_name, t.t_snumber, t.t_type, t.t_status_fk, t.t_user_fk, t.t_room_fk
    FROM tool_table t
    WHERE t.t_id = :id
");
$stmt->execute([':id' => $tool_id]);
$tool = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tool) {
    echo "Tool not found";
    exit;
}

// Get dropdowns data
$users = $user->getUsers();
$rooms = $user->getRooms();
$departments = $user->getDepartments();

// Handle form submission
$message = '';
if (isset($_POST['update'])) {
    $name = $_POST['tool_name'];
    $serial = $_POST['tool_snumber'];
    $type = $_POST['tool_type'];
    $status = $_POST['tool_status'];
    $user_id = $_POST['tool_user'] ?: null;
    $room_id = $_POST['tool_room'] ?: null;
    
    $stmt = $conn->prepare("
        UPDATE tool_table 
        SET t_name = :name, t_snumber = :serial, t_type = :type, 
            t_status_fk = :status, t_user_fk = :user_id, t_room_fk = :room_id
        WHERE t_id = :id
    ");
    
    $success = $stmt->execute([
        ':name' => $name,
        ':serial' => $serial,
        ':type' => $type,
        ':status' => $status,
        ':user_id' => $user_id,
        ':room_id' => $room_id,
        ':id' => $tool_id
    ]);
    
    if ($success) {
        $message = "<p class='bg-success text-white text-center'>Tool updated successfully! <a href='search_page.php'>Back to search</a></p>";
    } else {
        $message = "<p class='bg-danger text-white text-center'>Error updating tool</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Edit Tool – <?= htmlspecialchars($tool['t_name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="main-container">
    <h2>Edit Tool: <?= htmlspecialchars($tool['t_name']) ?></h2>
    
    <?= $message ?>
    
    <form method="POST" action="">
        <label for="tool_name">Tool Name:</label>
        <input type="text" id="tool_name" name="tool_name" value="<?= htmlspecialchars($tool['t_name']) ?>" required><br><br>

        <label for="tool_snumber">Serial Number:</label>
        <input type="text" id="tool_snumber" name="tool_snumber" value="<?= htmlspecialchars($tool['t_snumber']) ?>" required><br><br>

        <label for="tool_type">Tool Type:</label>
        <select id="tool_type" name="tool_type" required>
            <option value="">Select Type</option>
            <option value="Computer" <?= $tool['t_type'] == 'Computer' ? 'selected' : '' ?>>Computer</option>
            <option value="Phone" <?= $tool['t_type'] == 'Phone' ? 'selected' : '' ?>>Phone</option>
            <option value="Router" <?= $tool['t_type'] == 'Router' ? 'selected' : '' ?>>Router</option>
            <option value="Switch" <?= $tool['t_type'] == 'Switch' ? 'selected' : '' ?>>Switch</option>
            <option value="Printer" <?= $tool['t_type'] == 'Printer' ? 'selected' : '' ?>>Printer</option>
            <option value="Server" <?= $tool['t_type'] == 'Server' ? 'selected' : '' ?>>Server</option>
            <option value="Other" <?= $tool['t_type'] == 'Other' ? 'selected' : '' ?>>Other</option>
        </select>
        <br><br>

        <label for="tool_status">Tool Status:</label>
        <select id="tool_status" name="tool_status" required>
            <option value="">Select Status</option>
            <option value="1" <?= $tool['t_status_fk'] == 1 ? 'selected' : '' ?>>1 - Active</option>
            <option value="2" <?= $tool['t_status_fk'] == 2 ? 'selected' : '' ?>>2 - Deactivated</option>
            <option value="3" <?= $tool['t_status_fk'] == 3 ? 'selected' : '' ?>>3 - Repairing</option>
        </select>
        <br><br>

        <label for="tool_user">Assigned User:</label>
        <select id="tool_user" name="tool_user">
            <option value="">Select User</option>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['u_id'] ?>" <?= $tool['t_user_fk'] == $u['u_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['u_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="tool_room">Room:</label>
        <select id="tool_room" name="tool_room">
            <option value="">Select Room</option>
            <?php foreach ($rooms as $r): ?>
                <option value="<?= $r['r_id'] ?>" <?= $tool['t_room_fk'] == $r['r_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($r['r_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit" name="update" class="btn btn-success">Update Tool</button>
        <a href="search_page.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>

<?php include "includes/footer.php"; ?>
