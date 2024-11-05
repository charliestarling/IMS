<?php 
session_start();
if ($_SESSION["user_role"]!='Reception'){
  header("Location: ../logout.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>HMS :: Reception Dashboard</title>
  <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>

</head>

<body>
  <div class="dashboard">
    <header>
      <div class="btn_logout"><a href="../logout.php">Log out</a></div>
      <h1>Reception Dashboard</h1>
      <div class="icon">
        <img src="/HospitalManagementSystem/images/user_icon.png" 
          alt="User" width="50" height="50">
        <?php 
          echo $_SESSION['user_firstName']." ".$_SESSION['user_surname'];
          ?>
      </div>
    </header>
    <main>
      <section><a href="appts.php">Patient Appointments</a></section>
    </main>
  </div>
</body>

</html>
