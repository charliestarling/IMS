<?php 
session_start();
if ($_SESSION["user_role"] != 'Medical') {
  header("Location: ../logout.php");
  exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');


if (isset($_POST['view'])) {
    header("Location: view_health.php");}
 


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>HMS :: Patient Health</title>
  <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>
<body>
  <div class="dashboard">
    <header>
      <div class="btn_logout"><a href="m_home.php">Back</a></div>
      <h1>Patient Health</h1>
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
            <h3>Patient List</h3>
            <form action="view_health.php" class="sql_results" method="post">
              <table>
                  <tr>
                    <th class="checkboxRow"></th> 
                    <th class='user_id_column'>User ID</th>
                    <th>Name</th>
                    <th>Email Address</th>
                    <th class='role_column'>Role</th>
                  </tr>
                    <?php 
                      $sql="SELECT user_id, first_name, surname, email_address, user_role FROM users
                      WHERE user_role='Patient'";
                      $result=$conn->query($sql);
                      if ($result -> num_rows >0){
                        while($row = $result-> fetch_assoc()){
                          echo "
                          <tr><td><input type='checkbox' name='selection' value='".$row['email_address']."' class='larger_check_box'></td>
                          <td>".$row['user_id']."</td>
                          <td>".$row['first_name']." ".$row['surname']."</td>
                          <td>".$row['email_address']."</td>
                          <td>".$row['user_role']."</td>
                          </tr>";
                        }
                        echo "</table>";
                      }
                      else{
                        
                        echo "<h3>There aren't any Users Registered</h3>";
                      }
                      
                    ?>
              </table>
              <input type="submit" value="View" name="view">
            </form>
        </div>
    </main>
  </div>
</body>
</html>
