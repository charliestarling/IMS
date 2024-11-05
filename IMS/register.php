<?php 
session_start();
if (isset($_SESSION["user_role"])){
  if ($user['user_role']=='Admin'){
    header("Location: a_home.php");
  } elseif ($user['user_role']=='Reception'){
    header("Location: r_home.php");
  } elseif ($user['user_role']=='Patient'){
    header("Location: p_home.php");
  } else {
    header("Location: m_home.php");
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>HMS :: Register</title>
  <?php include('includes/header.php'); ?>

</head>
<body>
  <div class="login-container" id="registerLogin">

    <?php 
    if (isset($_POST["submit"])){
      $first_name= $_POST["forename"];
      $surname= $_POST["surname"];
      $email_address= $_POST["email"];
      $password= $_POST["password"];
      $street_address= $_POST["address"];
      $postcode= $_POST["postcode"];
      $role = $_POST["role"];
      $errors = array();
      
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      require_once "includes/config.php";
      $sql_check_email= "SELECT * FROM users WHERE email_address = '$email_address'";
      $email_result= mysqli_query($conn, $sql_check_email);
      $email_row_count= mysqli_num_rows($email_result);
      if ($email_row_count>0){
        array_push($errors, "Email already exists!");
      }

      if (count($errors)>0){
        foreach($errors as $error){
        echo "<p class='error_message'>ERROR: $error.</p";
        }
      }
      else{
        $sql = "INSERT INTO registrations (first_name, surname, email_address, password, street_address, post_code, user_role)
        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        $prepare_stmt = mysqli_stmt_prepare($stmt, $sql);
        if($prepare_stmt){
          mysqli_stmt_bind_param($stmt, "sssssss", $first_name, $surname, $email_address, $passwordHash, $street_address, $postcode, $role);
          mysqli_stmt_execute($stmt);

          

          echo "<p>Registered Successfully</p>";
        } else{
          die("Something went wrong.");
        }
      }   
    }   
    ?>

    <h1>HMS - Register New Account</h1>
    <form action="register.php" method="post">
      <input type="name" id="register_first_name" name="forename" placeholder="First Name" required>
      <input type="name" id="register_surname" name="surname" placeholder="Surname" required>

      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" id="password" name="password" placeholder="Password" required>
      <input type="password" id="confirm_password" name="confirm_password" onkeyup='check();' placeholder="Confirm Password" required>
      
      <p id="confirm_message"></p>

      <input type="text" name="address" placeholder="Street Address" required>
      <input type="text" name="postcode" placeholder="Post Code" required>

      
      <label for="role">Select Role:</label>
      <select id="role" name="role">
        <option value="Patient">Patient</option>
        <option value="Reception">Receptionist</option>
        <option value="Medical">Medical Staff</option>
        <option value="Admin">Administrator</option>
      </select>

      <input type="submit" value="Sign Up" name="submit">
    </form>
    <p>Already have an account? <a href="login.php">Sign In</a></p>
  </div>

</body>
</html>
