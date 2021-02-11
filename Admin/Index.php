<?php
  session_start();
  $noNavbar = '';
  $pageTitle = 'Login';
  if (isset($_SESSION['Username'])) {
    header('location:dashboard.php'); // Directory To Dashboard
  }
  include 'init.php';


    // Check If user Comming Form HTTP POST Request

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username  = $_POST['user'];
    $password  = $_POST['pass'];
    $hashedPass = sha1($password);

    // Check If the  UserExist I n Database

    $stmt = $con->prepare("SELECT UserID, Username , Password FROM users WHERE Username = ? AND Password = ?
    And GroupID = 1 LIMIT 1");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    // Is count > 0 This Mean The Batabase Contain Record boyt This Username

    if ($count  > 0 ) {
      $_SESSION['Username'] = $username; // Regester Session Name
      $_SESSION['ID'] = $row['UserID'];  // Regester Session ID
      header('location: dashboard.php'); // Directory Tp Dashboard ;

      exit();
    }
  }
 ?>

    <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
      <h4 class="text-center">Admin Login</h4>
      <input class="form-control input" type="text" name="user" placeholder="Username" autocomplete="off">
      <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
      <input class="btn btn-primary btn-block" type="submit"  value="Login">
    </form>

 <?php
   include $tpl . 'footer.php';
  ?>
