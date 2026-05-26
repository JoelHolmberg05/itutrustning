<?php
    include "includes/header.php";

    //check if form has been sent
    if(isset($_POST['register'])) {
        $checkReturn = $user->checkUserRegisterInput();
        
            
        if ($checkReturn == "Success!") {
            $registerResult = $user->register();

            echo "<p class='bg-info text-white text-center'>{$registerResult} <a href='index.php'>Log In</a><p>";
        }

        else {
            echo $checkReturn;
        }
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
    <title>Register Account</title>
</head>
<body>

<form id="registerform" method="POST" action="">
    <label for="username">Username</label><br>
    <input type="text" id="username" name="username"><br>

    <label for="email">Email</label><br>
    <input type="text" id="email" name="email"><br>

    <label for="password">Password</label><br>
    <input type="password" id="password" name="password"><br>

    <label for="confpassword">Confirm password</label><br>
    <input type="password" id="confpassword" name="confpassword"><br><br>


    <input class="button" type="submit" name="register" id="registerbtn" value="Register"><br><br>


    <p>Already have an account? <a href="index.php">Log in</a></p>

</form>

</body>
</html>