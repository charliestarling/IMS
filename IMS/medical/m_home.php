<?php 
session_start();
if ($_SESSION["user_role"]!='Medical'){
  header("Location: ../logout.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>HMS :: Medical Dashboard</title>
  <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>

</head>

<body>
  <div class="dashboard">
    <header>
      <div class="btn_logout"><a href="../logout.php">Log out</a></div>
      <h1>Medical Dashboard</h1>
      <div class="icon">
        <img src="/HospitalManagementSystem/images/user_icon.png" 
          alt="User" width="50" height="50">
        <?php 
          echo $_SESSION['user_firstName']." ".$_SESSION['user_surname'];
        ?>
      </div>
    </header>
    <main>
      <section><a href="view_appts.php">My Appointments</a></section>
      <section><a href="vhd_patient_list.php">View Health Data</a></section>
      <section><a href="holiday_records.php">Update Availability</a></section>
    </main>
  </div>
</body>

</html>
