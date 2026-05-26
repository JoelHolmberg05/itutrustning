<?php
    include "includes/header.php";

    if (!$user->checkUserLogInStatus()) {
        $user->redirect("index.php");
    }

    if (isset($_POST['editaccount'])) {
        if ($user->editUserInfo()) {
            $feedback = "User info updated successfully.";
        }
    }

    $userInfo = $user->getUserInfo($_SESSION['user_id']);

    if (isset($feedback)) {
        echo $feedback;
    }

    if ($user->checkUserRole(50) && isset($_GET['userToEdit'])) {
        $userToEdit = $_GET['userToEdit'];
    }

    else {
        $userToEdit = $_SESSION['uid'];
    }

    if(isset($_POST['editaccount'])){
	
        $checkReturn = $user->checkUserRegisterInput();
            
            //If all checks are passed, run register-fuction
            if($checkReturn == "success"){
                if($user->editUserInfo($userToEdit)){
                $feedback = "user info updated successfully";
                }
            }
            //If something does not meet requirements, echo out what went wrong.
            else {
                $feedback =$checkReturn;
            }
    }

    $userInfo = $user->getUserInfo($userToEdit); 
    $roleInfo = $conn->query("SELECT * FROM role_table");
    $statusInfo = $conn->query("SELECT * FROM status_table");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
</head>
<body>
    
    <form id="editaccountform" method="POST" action="">
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" value="<?php echo $userInfo['user_name']; ?>"disabled><br>
    
        <label for="updemail">Email</label><br>
        <input type="text" id="email" name="email"><br>
    
        <label for="oldpassword">Old password</label><br>
        <input type="password" id="oldpassword" name="oldpassword"><br>

        <label for="updpassword">New password</label><br>
        <input type="password" id="password" name="password"><br>

        <label for="confupdpassword">Confirm password</label><br>
        <input type="password" id="confpassword" name="confpassword"><br>

        <label for="userrole">User role</label><br>
        <!--<select class="admin" id="userrole" name="userrole">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select<br>-->

        <label for="userstatus">User status</label><br>
        <select class="admin" id="userstatus" name="userstatus">
            <option value="active">Active</option>
            <option value="deactivated">Deactivated</option>
        </select><br><br>
    
        <a class="button" href="admin.php">Return</a>
        <input class="button" type="submit" name="editaccount" id="editaccountbtn" value="Update"><br><br>
    
    </form>
    
    <?php 
		if($user->checkUserRole(50)){
	?>
	
	<form method="POST" action="">
		<select name="update_status">
			<?php 
			foreach ($statusInfo as $row){
			echo "<option value='{$row['s_id']}'>{$row['s_name']}</option>" ;
			}
			?>
		</select>
		<select name="update_role">
			<?php foreach ($roleInfo as $row){
			echo "<option value='{$row['r_id']}'>{$row['r_name']}</option>" ;
			} ?>
		</select>
	  <input type="submit" name="submit_edit" value="Update">
	</form>
	
<?php } ?>
    
</body>
</html>