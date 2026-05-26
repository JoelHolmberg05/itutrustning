<?php

class user {
    public $errorMessage;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    public function checkUserRegisterInput() {
        $error = 0;
        
        $cleanDepartment = isset($_POST['u_department']) ? $this->cleanInput($_POST['u_department']) : '';

        if (isset($_POST['register'])) {
            $cleanName = $this->cleanInput($_POST['username']);
            $stmt_checkIfUserExist = $this->conn->prepare("SELECT * FROM user_table WHERE u_name = :uname OR u_department_fk = :department");
            $stmt_checkIfUserExist->bindValue(":uname", $cleanName, PDO::PARAM_STR);
            $stmt_checkIfUserExist->bindValue(":department", $cleanDepartment, PDO::PARAM_STR);
            $stmt_checkIfUserExist->execute();
        }

        $userNameMatch = $stmt_checkIfUserExist->fetch();

        if (!empty($userNameMatch)) {
            if ($userNameMatch['u_name'] == $cleanName) {
                $this->errorMessage .= " | Username is already taken";
                $error=1;
            }

            if ($userNameMatch['u_department_fk'] == $cleanDepartment) {
                $this->errorMessage .= " | Department is already in use";
                $error=1;
            }
        }
        
        if (isset($_POST['editaccount']) && $_POST['password'] == "") {

        }

        else {
            if ($_POST['password'] != $_POST['confpassword']) {
                $this->errorMessage .= " | Passwords do not match";
                $error=1;
            }

            if (strlen($_POST['password']) < 8) {
                $this->errorMessage .= " | Make sure your password is at least 8 characters long";
                $error=1; 
            }
        }

        if (!isset($_POST['u_department']) || $_POST['u_department'] == "") {
            $this->errorMessage .= " | Department is required";
            $error=1;
        }

        if ($error !=0) {
            echo $this->errorMessage;
            return $this->errorMessage;
        }

        else {
            return "Success!";
        }

    }

    public function register() {
        $cleanName = $this->cleanInput($_POST['username']);
        $department_id = $_POST['u_department'];
        //Encrypt password with the password hash-function
        $encryptedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt_insertUser = $this->conn->prepare("INSERT INTO user_table (u_name, u_department_fk, u_password, u_role_fk)
		VALUES (:u_name, :u_department_fk, :u_password, 1)");
		$stmt_insertUser->bindParam(':u_name', $cleanName, PDO::PARAM_STR);
		$stmt_insertUser->bindParam(':u_department_fk', $department_id, PDO::PARAM_INT);
		$stmt_insertUser->bindParam(':u_password', $encryptedPassword, PDO::PARAM_STR);
		$check = $stmt_insertUser->execute();

        if ($check) {
            return "User created successfully!";
        }

        else {
            return "Something went wrong.";
        }
    }

    public function logIn() {
        $cleanName = $this->cleanInput($_POST['username']);

        $stmt_checkIfUserExist = $this->conn->prepare("SELECT * FROM table_users WHERE u_name = :uname OR u_email = :email");
        $stmt_checkIfUserExist->bindValue(":uname", $cleanName, PDO::PARAM_STR);
        $stmt_checkIfUserExist->bindValue(":email", $cleanName, PDO::PARAM_STR);
        $stmt_checkIfUserExist->execute();
        $userNameMatch = $stmt_checkIfUserExist->fetch();

        if (!$userNameMatch) {
            $this->errorMessage = "No such user or email in database";
            return $this->errorMessage;
        }

        $checkPasswordMatch = password_verify($_POST["password"], $userNameMatch["u_password"]);

        if($checkPasswordMatch == true) {
            $_SESSION['u_name'] = $userNameMatch['u_name'];
            $_SESSION['u_role_fk'] = $userNameMatch['u_role_fk'];
            $_SESSION['u_id'] = $userNameMatch['u_id'];
            return "Success!";
         }
         
         else {
            $this->errorMessage = "INVALID PASSWORD"; 
            return $this->errorMessage;  
         }
    }

    public function checkUserLogInStatus() {
        if (isset($_SESSION['u_id'])){
            return true;
        }

        else {
            return false;
        }
    }

    public function checkUserRole($req) {
        if (isset($_SESSION['role_level']) && $_SESSION['role_level'] >= $req) {
            return true;
        } else {
            return false;
        }
    }

    public function redirect($url) {
    header("Location: " .$url." ");
        exit();
    }


    public function logout() {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }

    public function editUserInfo() {
        $error = 0;
        $cleanEmail = $this->cleanInput($_POST['updemail']);

		

        if (isset($_POST['password']) && $_POST['password'] != "") {
            $encryptedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $editUserInfo = $this->conn->prepare("UPDATE table_users SET  u_password = :u_password WHERE u_id = :u_id");
            $editUserInfo->bindParam(":u_password", $cleanPassword, PDO::PARAM_STR);
            $editUserInfo->bindParam(":u_id", $_SESSION['u_id'], PDO::PARAM_STR);
            $check = $editUserInfo->execute();
        }

        else {
            $editUserInfo = $this->conn->prepare("UPDATE table_users SET u_email = :u_email WHERE u_id = :u_id");
            $editUserInfo->bindParam(":u_email", $cleanEmail, PDO::PARAM_STR);
            $editUserInfo->bindParam(":u_id", $_SESSION['u_id'], PDO::PARAM_STR);
            $check = $editUserInfo->execute();
        }
        if ($check) {
            return true;
        }
    }

    public function getUserInfo($uid) {
        $stmt_userInfoQuery = $this->conn->prepare("SELECT * FROM user_table WHERE u_id = :u_id");
		$stmt_userInfoQuery->bindParam(':u_id', $uid, PDO::PARAM_STR);
		$stmt_userInfoQuery->execute();
        $userInfo = $stmt_userInfoQuery->fetch();
        return $userInfo;
    }

    public function searchUser() {
        $cleanParam = $this->cleanInput($_POST['searchinput']);
        $searchUserQuery = $this->conn->prepare("SELECT * FROM user_table WHERE u_name = :searchparam");
		$searchUserQuery->bindParam(':searchparam', $cleanParam, PDO::PARAM_STR);
        $searchUserQuery->execute();
        return $searchUserQuery;
    }

    public function getUsers() {
        $stmt = $this->conn->prepare("SELECT u_id, u_name FROM user_table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRooms() {
        $stmt = $this->conn->prepare("SELECT r_id, r_name FROM room_table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insertTool($name, $serial_number, $type, $status, $user_id, $room_id) {
        $stmt = $this->conn->prepare("INSERT INTO tool_table (t_name, t_snumber, t_type, t_status_fk, t_user_fk, t_room_fk) VALUES (:name, :serial, :type, :status, :user_id, :room_id)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':serial', $serial_number, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':room_id', $room_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getDepartments() {
        $stmt = $this->conn->prepare("SELECT * FROM department_table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>