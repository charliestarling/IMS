<?php 
session_start();
if ($_SESSION["user_role"] != 'Medical') {
  header("Location: ../logout.php");
  exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>HMS :: Medical Appointments</title>
  <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>
<body>
  <div class="dashboard">
    <header>
      <div class="btn_logout"><a href="m_home.php">Back</a></div>
      <h1>Patient Appointments</h1>
      <div class="icon">
        <img src="/HospitalManagementSystem/images/user_icon.png" 
          alt="User" width="50" height="50">
        <?php 
          echo $_SESSION['user_firstName']." ".$_SESSION['user_surname'];
        ?>
      </div>
    </header>
    <main>
        <div class="result_table">
            <h3>Appointments</h3>
            <form action="view_health.php" class="sql_results" method="post">
              <table>
                  <tr> 
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Description</th>
                  </tr>
                    <?php 
                      $sql = "SELECT a.appointment_id, a.date, a.time, CONCAT(u_patient.first_name, ' ', u_patient.surname) AS patient, 
                      a.description
                        FROM appointments a
                        INNER JOIN users u_staff ON a.staff_user_id = u_staff.user_id
                        INNER JOIN users u_patient ON a.patient_user_id = u_patient.user_id
                        WHERE u_staff.email_address = ?";
                      $stmt = mysqli_prepare($conn, $sql);
                      mysqli_stmt_bind_param($stmt, "s", $_SESSION['email_address']);
                      mysqli_stmt_execute($stmt);
                      $result = mysqli_stmt_get_result($stmt);
                      
                      if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['date'] . "</td>";
                            echo "<td>" . $row['time'] . "</td>";
                            echo "<td>" . $row['patient'] . "</td>";
                            echo "<td>" . $row['description'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                      } else{
                        
                        echo "<tr><td colspan='4'>You have no appointments.</td></tr>";
                      }
                      
                      mysqli_stmt_close($stmt);
                      mysqli_close($conn);
                    ?>
              </table>
            </form>
        </div>
    </main>
  </div>
</body>
</html>
