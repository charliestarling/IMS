<?php 
session_start();
if ($_SESSION["user_role"] != 'Patient') {
    header("Location: ../logout.php");
    exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');
$patient_email = $_SESSION['email_address'];
if (isset($_POST['new'])){
  header("Location: new_appointment.php");
}

if (isset($_POST['selection'])) {
  $target = mysqli_real_escape_string($conn, $_POST['selection']); 

  if (isset($_POST['cancel'])) {
    $sql = "DELETE FROM appointments WHERE appointment_id = '$target'"; 
    $conn->query($sql);
    $conn->close();
  } 
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>HMS :: Patient Appointments</title>
    <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>

<body>
    <div class="dashboard">
        <header>
            <div class="btn_logout"><a href="p_home.php">Back</a></div>
            <h1>Appointments</h1>
            <div class="icon">
                <img src="/HospitalManagementSystem/images/user_icon.png" alt="User" width="50" height="50">
                <?php 
                echo $_SESSION['user_firstName']." ".$_SESSION['user_surname'];
                ?>
            </div>
        </header>
        <main>
            <div class="result_table">
                <h3>My Appointments</h3>
                <form action="view_appts.php" class="sql_results" method="post">
                    <table>
                        <tr>
                            <th class='checkboxRow'></th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Staff</th>
                            <th>Description</th>
                        </tr>
                        <?php
                        $sql = "SELECT a.appointment_id, a.date, a.time, CONCAT(u_staff.first_name, ' ', u_staff.surname) AS staff, a.description
                          FROM appointments a
                          INNER JOIN users u_staff ON a.staff_user_id = u_staff.user_id
                          INNER JOIN users u_patient ON a.patient_user_id = u_patient.user_id
                          WHERE u_patient.email_address = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "s", $patient_email);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selection' 
                                value='".$row['appointment_id']."' class='larger_check_box'></td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['time'] . "</td>";
                                echo "<td>" . $row['staff'] . "</td>";
                                echo "<td>" . $row['description'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                        } else{
                        
                            echo "<tr><td colspan='5'>You have no appointments.</td></tr>";
                          }
                        mysqli_stmt_close($stmt);
                        mysqli_close($conn);
                        ?>
                    </table>
                    <input type="submit" value="New" name="new">
                    <input type="submit" value="Cancel" name="cancel">
                </form>
            </div>
        </main>
    </div>
</body>

</html>
