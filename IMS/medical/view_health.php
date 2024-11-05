<?php 
session_start();
if ($_SESSION["user_role"] != 'Medical') {
  header("Location: ../logout.php");
  exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');

if (isset($_POST['view'])){
  $patient_email = mysqli_real_escape_string($conn, $_POST['selection']);
  $sql="SELECT first_name FROM users WHERE email_address= ? LIMIT 1"; 
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $patient_email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  
 
  $row = mysqli_fetch_assoc($result);
  $patient_first_name = $row['first_name'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>HMS :: Patient Health Data</title>
  <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>
<body>
  <div class="dashboard">
    <header>
      <div class="btn_logout"><a href="vhd_patient_list.php">Back</a></div>
      <h1>Health Data</h1>
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
            <h3><?php echo isset($patient_first_name) ? $patient_first_name . "'s Test Data" : "Patient Test Data"; ?></h3>
            <form action="view_health.php" class="sql_results" method="post">
              <table>
                  <tr> 
                    <th>Test Date</th>
                    <th>Staff</th>
                    <th>Description</th>
                    <th>Comment</th>
                  </tr>
                    <?php 
                      if (isset($patient_email)) { 
                        $sql = "SELECT t.test_id, t.date, CONCAT(u_staff.first_name, ' ', u_staff.surname) AS staff, 
                        t.description, t.comment
                          FROM tests t
                          INNER JOIN users u_staff ON t.staff_user_id = u_staff.user_id
                          INNER JOIN users u_patient ON t.patient_user_id = u_patient.user_id
                          WHERE u_patient.email_address = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "s", $patient_email);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if (mysqli_num_rows($result) > 0) {
                          while ($row = mysqli_fetch_assoc($result)) {
                              echo "<tr>";
                              echo "<td>" . $row['date'] . "</td>";
                              echo "<td>" . $row['staff'] . "</td>";
                              echo "<td>" . $row['description'] . "</td>";
                              echo "<td>" . $row['comment'] . "</td>";
                              echo "</tr>";
                          }
                          echo "</table>";
                        } else{
                          
                          echo "<tr><td colspan='4'>No test results found</td></tr>";
                        }
                        
                        mysqli_stmt_close($stmt);
                      } else {
                        echo "<tr><td colspan='4'>Please select a patient.</td></tr>";
                      }
                      mysqli_close($conn);
                    ?>
              </table>
            </form>
        </div>
    </main>
  </div>
</body>
</html>