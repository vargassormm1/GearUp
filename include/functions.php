<?php
//error handler for empty signup inputs
//returns true if one of the unputs in signup page is left blank
function emptyInputSignup($name, $email, $username, $password, $confirmPassword) {
    $result;
    if (empty($name) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

//error handler for email
//returns true of email entered is not in proper format
function invalidEmail($email) {
    $result;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { 
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

//Checks if both passwords entered are the same or not
//returns true of passwords dont match
function passwordMatch($password, $confirmPassword) {
    $result;
    if ($password !== $confirmPassword) { 
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

//checks if username entered already exists in the database
function usernameExists($conn, $username, $email) {
    
    //? are palceholders for user data so it doesnt send to database immideately
    //sends sql staement first then provide data later
    $sql = "SELECT * FROM users WHERE username = ? OR userEmail = ?;";
    
    //prepared statment
    $stmt = mysqli_stmt_init($conn);

    //checks if statement fails
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    //bind the user data to our statment and execute
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    //if returns data then username exist otherwise username not taken
    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    }
    else {
        $result = false;
        return $result;
    }
    
    //close down prepared stmt
    mysqli_stmt_close($stmt);
}

//creates user and add info to database
function createUser($conn, $name, $email, $username, $password, $role) {

    //inserting data to users databse using placeholders
    //sends sql staement first then provide data later
    $sql = "INSERT INTO users (name, userEmail, username, userPassword, role) VALUES (?, ?, ?, ?, ?);";
    
    //prepared stmt
    $stmt = mysqli_stmt_init($conn);

    //checks if statement fails
    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: signup.php?error=stmtfailed");
        exit();
    }

    //hash the password
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    //bind the user data to our statment and execute
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $username, $hashPassword, $role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: signup.php?error=none");
    exit();
}

//admin function to add users
function addUser($conn, $name, $email, $username, $password, $role) {
    
    //inserting data to database
    $sql = "INSERT INTO users (name, userEmail, username, userPassword, role) VALUES (?, ?, ?, ?, ?);";
    //prepare stmt
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("location: users.php?error=stmtfailed");
        exit();
    }

    //hash password
    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    //bind user data to prepared stmt and execute
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $username, $hashPassword, $role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: users.php?error=none");
    exit();
}

//checks if any input in login page is empty
//returns true if input is empty
function emptyInputLogin($username, $password) {
    $result;
    if (empty($username) || empty($password) ) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

//logs in the user to site
function loginUser($conn, $username, $password) {
    
    //use usernameExists function and pass in both username so user can login with both username and email
    $usernameExists = usernameExists($conn, $username, $username);
    
    //if false then username does not exists
    if($usernameExists === false) {
        header("location: login.php?error=wronglogin");
        exit();
    }

    //get password from array returned from usernameExists
    $passwordHashed = $usernameExists["userPassword"];
    
    //compares hashed password with password entered
    $checkPassword = password_verify($password, $passwordHashed);

    //if passwords dont match exit
    if($checkPassword === false) {
        header("location: login.php?error=wronglogin");
        exit();
    }
    //if role is admin the go to admin.php and start admin session
    else if ($usernameExists["role"] == "Admin" && $checkPassword === true) {
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["admin"] = $usernameExists["usersId"];
        $_SESSION["usersId"] = $usernameExists["usersId"];
        $_SESSION["username"] = $usernameExists["username"];
        header("location: admin.php");
        exit();
    }
    //if not admin then go to home.php
    else if ($checkPassword === true) {
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["usersId"] = $usernameExists["usersId"];
        $_SESSION["username"] = $usernameExists["username"];
        header("location: home.php");
        exit();
    }
}
