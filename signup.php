<?php
session_start();

if(isset($_POST["submit"])) {
    
    //get all user info
    $name = $_POST["name"]; 
    $email = $_POST["email"]; 
    $username = $_POST["username"]; 
    $password = $_POST["password"]; 
    $confirmPassword = $_POST["confirmPassword"];
    $role = $_POST["role"]; 

    //require database connection and functions
    require_once "include/databaseConn.php";
    require_once "include/functions.php";

    //empty input error
    if(emptyInputSignup($name, $email, $username, $password, $confirmPassword) !== false){
        header("location: signup.php?error=emptyinput");
        exit();
    }
    //email error
    if(invalidEmail($email) !== false){
        header("location: signup.php?error=invalidemail");
        exit();
    }
    //if bith passwords match
    if(passwordMatch($password, $confirmPassword) !== false){
        header("location: signup.php?error=passwordsdontmatch");
        exit();
    }
    //checks databse if username already exists
    if(usernameExists($conn, $username, $email) !== false){
        header("location: signup.php?error=usernametaken");
        exit();
    }
    
    //creates user if no error
    createUser($conn, $name, $email, $username, $password, $role);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />    
    <link rel="stylesheet" href="css/loginStyle.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
      crossorigin="anonymous"
    />
    <title>Sign Up</title>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Gear Up</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarNav"
          aria-controls="navbarNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="signup.php">Sign Up</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <div class="loginBg">
    <div class="login-form">
        
      <!--Signup Form-->
      <form class="form" action="signup.php" method="post">
          <h2>Signup</h2>
          <label for="name" class="form-label">Name</label>
          <input type="text" name="name" placeholder="Enter Full Name" class="form-control">
          <label for="Email" class="form-label">Email</label>
          <input type="email" name="email" placeholder="Enter Email" class="form-control">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" placeholder="Enter Username" class="form-control">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" placeholder="Enter Password" class="form-control">
          <label for="confirmPassword" class="form-label">Confirm Password</label>
          <input type="password" name="confirmPassword" placeholder="Confirm Password" class="form-control">
          <!--anybody that signup will automatically be a user-->
          <input type="hidden" name="role" value="User" />
          <button class="btn btn-dark" type="submit" name="submit">Signup</button>
      <?php
        //gets error and tells user what errors are
        if(isset($_GET["error"])) {
            if($_GET["error"] == "emptyinput") {
                echo "<p>Fill in all feilds!</p>";
            }
            else if($_GET["error"] == "invalidemail") {
                echo "<p>Choose a proper email!</p>";
            }
            else if($_GET["error"] == "passwordsdontmatch") {
                echo "<p>Passwords dont match!</p>";
            }
            else if($_GET["error"] == "stmtfailed") {
                echo "<p>Something went wrong, try again!</p>";
            }
            else if($_GET["error"] == "usernametaken") {
                echo "<p>Username already taken!</p>";
            }
            else if($_GET["error"] == "none") {
                echo "<p>You have signed up!!</p>";
            }
        }
        ?>
      </form>
    </div>
    </div>
  </body>
</html>
