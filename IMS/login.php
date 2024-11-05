<?php 
session_start();
if (isset($_SESSION["user_role"])){
  header("Location: a_home.php");
}
?>

<!DOCTYPE html>
<html lang="en-GB">

<head>
  <title>HMS :: Login</title>
  <?php include('includes/header.php'); ?>

</head>


<body>
  <div class="login-container">
    <h1>Hospital Management System</h1>

    <?php 

      if (isset($_POST["login"])){
        $email_address = $_POST["email"];
        $password = $_POST["password"];
        require_once "includes/config.php";
        $sql = "SELECT * FROM users WHERE email_address = '$email_address'";
        $email_result= mysqli_query($conn, $sql);
        $user = mysqli_fetch_array($email_result, MYSQLI_ASSOC);
        if($user){
          if(password_verify($password, $user["password"])){
            session_start();

            $_SESSION['user_role']=$user['user_role'];
            $_SESSION['user_firstName']= $user['first_name'];
            $_SESSION['user_surname']=$user['surname'];
            $_SESSION['email_address']=$user['email_address'];
            $_SESSION['user_id']=$user['user_id'];
            

            if ($user['user_role']=='Admin'){
              header("Location: admin/a_home.php");
            } elseif ($user['user_role']=='Reception'){
              header("Location: reception/r_home.php");
            } elseif ($user['user_role']=='Patient'){
              header("Location: patient/p_home.php");
            } else {
              header("Location: medical/m_home.php");
            }
            die();

          }else{
            echo "<p class='error_message'>Invalid Password.</p>";
          }
        }else{
          echo "<p class='error_message'>Email not found.</p>";
        }
      }

    ?>

    <form action="login.php" method="post">
      <input type="email" name="email" placeholder="Email Address" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="submit" value="Login" name="login">
    </form>
    <p>New User? <a href="register.php">Create New Account</a></p>
  </div>
</body>

</html>
