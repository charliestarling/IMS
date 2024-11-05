<?php 
session_start();
if ($_SESSION["user_role"] != 'Admin') {
  header("Location: ../logout.php");
  exit(); 
}

$conn = mysqli_connect('localhost', 'root', '', 'hms');

if (isset($_POST['view'])) {
    $target = mysqli_real_escape_string($conn, $_POST['selection']); 
    $sql = "SELECT * FROM users WHERE email_address = '$target' LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);        
    } else {
        echo "No users found";
    }
}

if (isset($_POST["update"])){
  $email_address = $_POST["email_address"];
  $first_name = $_POST["forename"];
  $surname = $_POST["surname"];
  $street_address = $_POST["address"];
  $postcode = $_POST["postcode"];

  $sql = "UPDATE users SET first_name = ?, surname = ?, street_address = ?, post_code = ? WHERE email_address = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "sssss", $first_name, $surname, $street_address, $postcode, $email_address);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

if (isset($_POST["delete"])){
  $email_address = $_POST["email_address"];
  $sql = "DELETE FROM users WHERE email_address = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $email_address);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}   

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>HMS :: View User Info</title>
  <?php include ('/Applications/XAMPP/xamppfiles/htdocs/HospitalManagementSystem/includes/header.php'); ?>
</head>
<body>
  <div class="dashboard">
    <header>
      <div class="btn_logout"><a href="update_user.php">Back</a></div>
      <h1>View User</h1>
      <div class="icon">
        <img src="/HospitalManagementSystem/images/user_icon.png" 
          alt="User" width="50" height="50">
        <?php 
          echo $_SESSION['user_firstName']." ".$_SESSION['user_surname'];
        ?>
      </div>
    </header>
    <main>
        <div class="login-container" id="registerLogin">
            <h3>Edit / Remove Account</h3>
            <form action="view_user.php" method="post">
                <label for="forename">First Name: </label>
                <input type="text" name="forename" placeholder="First Name" 
                value="<?php echo isset($user['first_name']) ? $user['first_name'] : '' ?>" required>

                <label for="surname">Surname: </label>
                <input type="text" name="surname"  placeholder="Surname" 
                value="<?php echo isset($user['surname']) ? $user['surname'] : '' ?>" required>

                <label for="street_address">Street Address: </label>
                <input type="text" name="address" placeholder="Street Address" 
                value="<?php echo isset($user['street_address']) ? $user['street_address'] : '' ?>" required>

                <label for="postcode">Post Code: </label>
                <input type="text" name="postcode" placeholder="Post Code" 
                value="<?php echo isset($user['post_code']) ? $user['post_code'] : '' ?>" required>

                <input type="hidden" name="email_address" value="<?php echo isset($user['email_address']) ? $user['email_address'] : '' ?>">

                <div class="btn_box">
                    <input type="submit" value="Save" name="update">
                    <input type="submit" class='btn_red' value="Delete Account" name="delete">
                </div>  
            </form>
        </div>
    </main>
  </div>
</body>
</html>