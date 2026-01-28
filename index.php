<?php
    include "includes/header.php";
    require_once "includes/db.php"; // Your PDO connection
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="js/script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <title>Home page</title>
</head>
<body>

    <a href="search_page.php" class="button">List of tools</a>

    <br><br>
    <br><br>

    <?php
    if (isset($_POST['submit'])) {
        $name = $_POST['tool_name'];
        $serial = $_POST['tool_snumber'];
        $type = $_POST['tool_type'];
        $status = $_POST['tool_status'];
        $user_id = $_POST['tool_user'] ?: null;
        $room_id = $_POST['tool_room'] ?: null;
        if ($user->insertTool($name, $serial, $type, $status, $user_id, $room_id)) {
            echo "Tool inserted successfully!";
        } else {
            echo "Error inserting tool.";
        }
    }

    // Registration logic
    if(isset($_POST['register'])) {
        $checkReturn = $user->checkUserRegisterInput();
            
        if ($checkReturn == "Success!") {
            $registerResult = $user->register();
            echo "<p class='bg-info text-white text-center'>{$registerResult} <a href='index.php'>Log In</a><p>";
        } else {
            echo $checkReturn;
        }
    }

    $users = $user->getUsers();
    $rooms = $user->getRooms();
    $departments = $user->getDepartments();
    ?>

    <form method="post" action="" class="addinfoform">
        <h1 class="formheader">Register New Tool</h1><br>
        <label for="name">Tool name:</label>
        <input type="text" id="tool_name" name="tool_name" required><br><br>

        <label for="tool_serial_number">Tool serial number:</label>
        <input type="text" id="tool_snumber" name="tool_snumber" required><br><br>

        <select id="tool_type" name="tool_type" required>
            <option value="">Select Type</option>
            <option value="Computer">Computer</option>
            <option value="Phone">Phone</option>
            <option value="Router">Router</option>
            <option value="Switch">Switch</option>
            <option value="Printer">Printer</option>
            <option value="Server">Server</option>
            <option value="Other">Other</option>
        </select>
        <br><br>

        <label for="tool_status">Tool status:</label>
        <select id="tool_status" name="tool_status" required>
            <option value="">Select Status</option>
            <option value="1">1 - Active</option>
            <option value="2">2 - Deactivated</option>
            <option value="3">3 - Repairing</option>
        </select>
        <br><br>

        <label for="tool_user">Tool user:</label>
        <select id="tool_user" name="tool_user">
            <option value="">Select User</option>
            <?php foreach ($users as $u): ?>
                <option value="<?php echo $u['u_id']; ?>"><?php echo $u['u_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="tool_room">Tool room:</label>
        <select id="tool_room" name="tool_room">
            <option value="">Select Room</option>
            <?php foreach ($rooms as $r): ?>
                <option value="<?php echo $r['r_id']; ?>"><?php echo $r['r_name']; ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <button type="submit" name="submit">Submit</button>
    </form>

    <br><br>

    <form method="post" action="" class="addinfoform">
        <h1 class="formheader">Register New User</h1><br>
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="u_department">Department</label><br>
        <select id="u_department" name="u_department" required>
            <option value="">Select Department</option>
            <?php foreach ($departments as $d): ?>
                <option value="<?php echo $d['d_id']; ?>"><?php echo $d['d_name']; ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="password">Password</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confpassword">Confirm password</label><br>
        <input type="password" id="confpassword" name="confpassword" required><br><br>

        <input class="button" type="submit" name="register" id="registerbtn" value="Register"><br><br>
    </form>

</body>
</html>

<?php include "includes/footer.php"; ?>
