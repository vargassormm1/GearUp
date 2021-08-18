<?php
session_start();

require_once "include/databaseConn.php";

//if user not logged in send back to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

//remove user from database
if(isset($_POST["remove"])){
    $usersId = $_POST["usersId"]; 
    $sql = "DELETE FROM users WHERE usersId=$usersId";
    mysqli_query($conn, $sql) or die("Bad SQL: $sql");
}

//add new user to database
if(isset($_POST["submit"])) {
    //all info user info
    $name = $_POST["name"]; 
    $email = $_POST["email"]; 
    $username = $_POST["username"]; 
    $password = $_POST["password"]; 
    $confirmPassword = $_POST["confirmPassword"];
    $role = $_POST["role"]; 

    require_once "include/functions.php";

    //empty input error
    if(emptyInputSignup($name, $email, $username, $password, $confirmPassword) !== false){
        header("location: users.php?error=emptyinput");
        exit();
    }
    //email error
    if(invalidEmail($email) !== false){
        header("location: users.php?error=invalidemail");
        exit();
    }
    //passwords have to match
    if(passwordMatch($password, $confirmPassword) !== false){
        header("location: users.php?error=passwordsdontmatch");
        exit();
    }
    //checks database if username exists
    if(usernameExists($conn, $username, $email) !== false){
        header("location: users.php?error=usernametaken");
        exit();
    }
    
    // use addUser function
    addUser($conn, $name, $email, $username, $password, $role);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />    
    <link rel="stylesheet" href="css/adminStyle.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6"
      crossorigin="anonymous"
    />
    <title>Admin</title>
  </head>
  <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
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
              <a class="nav-link" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin.php">Inventory</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="users.php">Users</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="include/logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <div class="admin">
        
        <!--add new user form-->
        <form class="admin-form" action="users.php" method="post">
        <h2>Add User</h2>
        <h4>Name</h4>
        <input type="text" name="name" placeholder="Enter Full Name" />
        <h4>Email</h4>
        <input type="text" name="email" placeholder="Enter Email" />
        <h4>Username</h4>
        <input type="text" name="username" placeholder="Enter Username" />
        <h4>Password</h4>
        <input type="password" name="password" placeholder="Enter Password" />
        <h4>Confirm Password</h4>
        <input
          type="password"
          name="confirmPassword"
          placeholder="Confirm password"
        />
        <!--admin can chose what type of role user has-->
        <h4>Role</h4>
        <select name="role">
          <option value="User">User</option>
          <option value="Admin" selected>Admin</option>
        </select>
        <button class="btn btn-dark" type="submit" name="submit">Add User</button>
      </form>
      
      <!--products table-->
      <div class="products-table">
        <h2>Users</h2>
        <table>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Username</th>
            <th>Role</th>
            <th>Remove</th>
          </tr>

          <?php
          //displays all the users from database
          require_once "include/databaseConn.php";
          $getProd = "select * from users";
          $runProd= mysqli_query($conn,$getProd);
          
          while($row_pro=mysqli_fetch_array($runProd))
              {

                  $usersId=$row_pro['usersId'];
                  $name=$row_pro['name'];
                  $email=$row_pro['userEmail'];
                  $username=$row_pro['username'];
                  $password=$row_pro['userPassword'];
                  $role=$row_pro['role'];


                  echo "<tr>
                          <td>$name</td>
                          <td>$email</td>
                          <td>$username</td>
                          <td>$role</td>
                          <td>
                            <form action='users.php' method='POST'>
                            <input type='hidden' value='$usersId' name='usersId'>
                            <button class='btn btn-danger' type='submit' name='remove'>Remove</button>
                           </form>
                        </tr>";
              } 
            
            $conn->close();
            ?>
        </table>
      </div>
    </div>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
