<?php 
session_start();
if ($_SESSION["user_role"] != 'Reception') {
  header("Location: ../logout.php");
  exit(); 
}

$patient = $_SESSION['user_id'];
$conn = mysqli_connect('localhost', 'root', '', 'hms');


if (isset($_POST["update"])){
  $date = $_POST["date"];
  $time = $_POST["time"];
  $staff = $_POST["staff"];
  $patient = $_POST['patient'];
  $description = $_POST["desc"]; 
  
  $sql = "INSERT INTO appointments (date, time, staff_user_id, patient_user_id, description)
  VALUES (?, ?, ?, ?, ?)";
  $stmt = mysqli_stmt_init($conn);
  $prepare_stmt = mysqli_stmt_prepare($stmt, $sql);
  if($prepare_stmt){
    mysqli_stmt_bind_param($stmt, "sssss", $date, $time, $staff, $patient, $description);
    mysqli_stmt_execute($stmt);
    header("Location: appts.php");
    exit();
  }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>HMS :: New Appointment</title>
  <?php include ('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>
<body>
  <div class="dashboard">
    <header>
      <div class="btn_logout"><a href="view_appt.php">Back</a></div>
      <h1>New Appointment</h1>
      <div class="icon">
        <img src="/HospitalManagementSystem/images/user_icon.png" 
          alt="User" width="50" height="50">
        <?php 
          echo $_SESSION['user_firstName']." ".$_SESSION['user_surname'];
        ?>
      </div>
    </header>
    <main>
        <div class="login-container">
            <h3>Appointment Form</h3>
            <form action="new_appointment.php" id="new_appt" method="post">
                <label for="date">Date: </label>
                <input class='input-field' type="date" name="date" placeholder="Date" 
                value="" required>

                <label  class='inline-label'for="staff">Staff: </label>
                <select id="staff" name="staff">
                    <?php
                        $sql = "SELECT user_id, first_name, surname FROM users 
                        WHERE user_role='Medical'";
                        $result = $conn->query($sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['user_id'] . "'>" . 
                                $row['first_name'] . " " . $row['surname']."</option>";
                            }
                        } 
                    ?>
                </select>

                <label  class='inline-label'for="patient">Patient: </label>
                <select id="patient" name="patient">
                    <?php
                        $sql = "SELECT user_id, first_name, surname FROM users 
                        WHERE user_role='Patient'";
                        $result = $conn->query($sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['user_id'] . "'>" . 
                                $row['first_name'] . " " . $row['surname']."</option>";
                            }
                        } 
                    ?>
                </select>

                <label for="time">Time: </label>
                <select id="time" name="time">
                    <option value="10:00">10:00</option>
                    <option value="12:00">12:00</option>
                    <option value="14:00">14:00</option>
                    <option value="16:00">16:00</option>
                    <option value="18:00">18:00</option>
                </select>

                <label for="desc">Description: </label>
                <input type="text" name="desc" placeholder="Reason of appointment" 
                value="" required>
                <div class='btn_box'>
                    <input type="submit" class='btn_box' value="Save" name="update">
                </div>
            </form>
        </div>
    </main>
  </div>
</body>
</html>
