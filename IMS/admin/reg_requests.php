<?php 
session_start();
if ($_SESSION["user_role"] != 'Admin') {
  header("Location: ../logout.php");
  exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');

if (isset($_POST['selection'])) {
  $target = mysqli_real_escape_string($conn, $_POST['selection']); 

  if (isset($_POST['approve'])) {
    $sql = "SELECT * FROM registrations WHERE email_address = '$target'";
    $email_result = mysqli_query($conn, $sql);
    $user_data = mysqli_fetch_assoc($email_result);
    
    $insert_sql = "INSERT INTO users (first_name, surname, email_address, password, street_address, post_code, user_role) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssss", $user_data['first_name'], $user_data['surname'], $user_data['email_address'], 
                      $user_data['password'], $user_data['street_address'], $user_data['post_code'], 
                      $user_data['user_role']);
    if ($stmt->execute()) {
        $delete_sql = "DELETE FROM registrations WHERE email_address = ?";
        $stmt_delete = $conn->prepare($delete_sql);
        $stmt_delete->bind_param("s", $target);
        $stmt_delete->execute();
        $stmt_delete->close();
    } 
    $stmt->close();
} 
  elseif (isset($_POST['deny'])) {
    $sql = "DELETE FROM registrations WHERE email_address = '$target'"; 
    $conn->query($sql);
    $conn->close();
  } 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>HMS :: Admin Dashboard</title>
  <?php include('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>
<body>
  <div class="dashboard">
    <header>
      <div class="btn_logout"><a href="a_home.php">Back</a></div>
      <h1>Registration Requests</h1>
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
            <h3>New Requests</h3>
            <form action="reg_requests.php" class="sql_results" method="post">
              <table>
                  <tr>
                    <th class="checkboxRow"></th> 
                    <th>Name</th>
                    <th>Email Address</th>
                    <th>Role</th>
                    
                  </tr>
                    <?php 
                      $sql="SELECT first_name, surname, email_address, user_role FROM registrations";
                      $result=$conn->query($sql);
                      if ($result -> num_rows >0){
                        while($row = $result-> fetch_assoc()){
                          echo "
                          <tr><td><input type='checkbox' name='selection' value='".$row['email_address']."' class='larger_check_box'></td>
                          <td>".$row['first_name']." ".$row['surname']."</td>
                          <td>".$row['email_address']."</td>
                          <td>".$row['user_role']."</td>
                          </tr>";
                        }
                      }
                      else{
                        
                        echo "<tr><td colspan='4'>No requests found</td></tr>";
                      }
                      echo "</table>";
                      
                    ?>
              </table>
              <input type="submit" value="Approve" name="approve">
              <input type="submit" value="Deny" name="deny">
            </form>
        </div>
    </main>
  </div>
</body>
</html>
