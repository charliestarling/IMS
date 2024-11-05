<?php 
session_start();
if ($_SESSION["user_role"] != 'Medical') {
    header("Location: ../logout.php");
    exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');
$error_message="";

if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $desc = $_POST['desc'];
    $staff_id = $_SESSION['user_id'];

    $granted = true; 

    $sql = "SELECT a.date FROM appointments a WHERE staff_user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $staff_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['date'] == $date) {
            $error_message= "Absence Denied: You already have an appointment(s) this day."; 
            $granted = false;
            break; 
        }
    }

    if ($granted) { 
        $sql = "INSERT INTO absences (date, description, staff_id) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $date, $desc, $staff_id);
        mysqli_stmt_execute($stmt);
        header("Location: holiday_records.php");
        exit(); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>HMS :: Register</title>
  <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>
<body>
  <div class="login-container" id="holiday_form">
    <header>
      <div class="btn_logout"><a href="holiday_records.php">Back</a></div>
      <h1>Medical Holiday</h1>
      <div class="icon">
        <img src="/HospitalManagementSystem/images/user_icon.png" 
          alt="User" width="50" height="50">
        <?php 
          echo $_SESSION['user_firstName']." ".$_SESSION['user_surname'];
        ?>
      </div>
    </header>
    <div class="pinch_form">
      <form action="holiday_form.php" method="post">

        <div>
          <label class='lbl_holiday' for="date">Date of absence:</label><br><br>
          <input type="date" name="date" required>
        </div>
        <div>
          <label class='lbl_holiday' for="desc">Cause of absence:</label><br><br>
          <textarea name="desc" rows="4" cols="33" placeholder="Description" required></textarea>
        </div>
        <input type="submit" value="Sign Up" name="submit">
      </form>
      <h3 style="color: red"><?php echo $error_message;?></h3>
    </div>
  </div>
</body>
</html>
