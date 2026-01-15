<?php
    include "includes/header.php";

    if ($user->checkUserLogInStatus()) {
        if (!$user->checkUserRole(50)) {
            $user->redirect("home.php");
        }
    }

    else {
        $user->redirect("index.php");
    }

    if (isset($_POST['searchuser_submit'])) {
        $userlist = $user->searchUser();
        //var_dump($userlist);
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
</head>
<body>

    <h1>Admin page</h1>

    <form method="post" id="editaccountform">
    <label for="searchinput">Username</label><br>
    <input type="text" id="searchinput" name="searchinput"><br>


    <br><input class="button" type="submit" name="searchuser_submit" id="searchuser_submit" placeholder="Enter username here"><br><br>

</form>

<div class="userlist">
    <?php
    if(isset($userlist)) {
        foreach ($userlist as $row) {
            echo "<p> {$row['user_name']} <a href='account.php?userToEdit={$row['user_id']}'>Edit user</a> </p>";
        }
    }
    ?>
</div>


    
</body>
</html>