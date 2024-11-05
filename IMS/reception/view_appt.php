
<?php 
session_start();
if ($_SESSION["user_role"] != 'Reception') {
    header("Location: ../logout.php");
    exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');
$user_id = $_SESSION['user_id'];

if (isset($_POST['new'])){
    header("Location: new_appointment.php");
    exit();
}

if (isset($_POST['selection'])) {
    $user_email_address = mysqli_real_escape_string($conn, $_POST['selection']); 
    $sql = "SELECT first_name, surname FROM users WHERE email_address = '$user_email_address'"; 
    $result = $conn->query($sql);
    $user_data = $result->fetch_assoc();
    $user_name = $user_data['first_name'] . " " . $user_data['surname'];

    if (isset($_POST['cancel'])) {
        $appointment_id = mysqli_real_escape_string($conn, $_POST['selection']); 
        $sql = "DELETE FROM appointments WHERE appointment_id = '$appointment_id'";
        $conn->query($sql);
        header("Location: view_appt.php");
        exit();
    } 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>HMS :: User Appointments</title>
    <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>

<body>
    <div class="dashboard">
        <header>
            <div class="btn_logout"><a href="appts.php">Back</a></div>
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
                <h3><?php echo $user_name; ?>'s Appointments</h3> 
                <form action="view_appt.php" class="sql_results" method="post">
                    <table>
                        <?php
                        $sql = "SELECT a.appointment_id, a.date, a.time, CONCAT(u_staff.first_name, ' ', u_staff.surname) AS staff, a.description
                          FROM appointments a
                          INNER JOIN users u_staff ON a.staff_user_id = u_staff.user_id
                          INNER JOIN users u_patient ON a.patient_user_id = u_patient.user_id
                          WHERE u_patient.email_address = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "s", $user_email_address);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            echo "<tr>
                                <th class='checkboxRow'></th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Staff</th>
                                <th>Description</th>
                                </tr>";
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
                        } else {
                        
                        $sql = "SELECT a.appointment_id, a.date, a.time, CONCAT(u_patient.first_name, ' ', u_patient.surname) AS patient, a.description
                                FROM appointments a
                                INNER JOIN users u_staff ON a.staff_user_id = u_staff.user_id
                                INNER JOIN users u_patient ON a.patient_user_id = u_patient.user_id
                                WHERE u_staff.email_address = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "s", $user_email_address);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        if (mysqli_num_rows($result) > 0) {
                            echo "<tr>
                                <th class='checkboxRow'></th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Description</th>
                                </tr>";
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selection' 
                                value='".$row['appointment_id']."' class='larger_check_box'></td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['time'] . "</td>";
                                echo "<td>" . $row['patient'] . "</td>";
                                echo "<td>" . $row['description'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>You have no appointments.</td></tr>";
                        }
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
