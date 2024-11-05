<?php 
session_start();
if ($_SESSION["user_role"] != 'Medical') {
    header("Location: ../logout.php");
    exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');
if (isset($_POST['new'])){
  header("Location: holiday_form.php");
}

if (isset($_POST['selection'])) {
  $target = mysqli_real_escape_string($conn, $_POST['selection']); 

  if (isset($_POST['cancel'])) {
    $sql = "DELETE FROM absences WHERE absence_id = '$target'"; 
    $conn->query($sql);
    $conn->close();
  } 
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>HMS :: Booked Holiday</title>
    <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>

<body>
    <div class="dashboard">
        <header>
            <div class="btn_logout"><a href="m_home.php">Back</a></div>
            <h1>Absence & Holiday</h1>
            <div class="icon">
                <img src="/HospitalManagementSystem/images/user_icon.png" alt="User" width="50" height="50">
                <?php 
                echo $_SESSION['user_firstName']." ".$_SESSION['user_surname'];
                ?>
            </div>
        </header>
        <main>
            <div class="result_table">
                <h3>My Absences</h3>
                <form action="holiday_records.php" class="sql_results" method="post">
                    <table>
                        <tr>
                            <th class='checkboxRow'></th>
                            <th>Date</th>
                            <th>Description</th>
                        </tr>
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $sql = "SELECT absence_id, date, description FROM absences 
                        WHERE staff_id = ?";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "s", $user_id);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td><input type='checkbox' name='selection' 
                                value='".$row['absence_id']."' class='larger_check_box'></td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>" . $row['description'] . "</td>";
                                echo "</tr>";
                            }
                        } else{
                        
                            echo "<tr><td colspan='3'>You have no appointments.</td></tr>";
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
