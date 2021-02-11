<?php
  ob_start();
  session_start();
  $pageTitle = 'Login';
  if (isset($_SESSION['user'])) {
    header('Location: Index.php');
    exit();
  }
  include 'init.php';
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $User = $_POST['username'];
        $Pass = $_POST['password'];
        $hashPass = sha1($Pass);
        ///
        $stmt = $con->prepare("SELECT UserID , Username , Password FROM users
                              WHERE Username = ? AND Password = ?");
        $stmt->execute(array($User , $hashPass));
        $MEM = $stmt->fetchAll();
        $check = $stmt->rowCount();
        if ($check > 0) {
           header("Location:Index.php");
           $_SESSION['user'] = $User; // Here Register Session
           $_SESSION['uid'] = $MEM['UserID'];  // HEre Regisrter User ID Session
          exit();
        }else {
           $error1 = "<div class='alert alert-danger text-center' style='max-width:430px; margin:5px auto'>Incorrect Username Or Password </div>";
        }
    }else {
        $formErrors = array();
      // Assign A Post reques To variable
      $username   = $_POST['username'];
      $password   = $_POST['password'];
      $password2  = $_POST['password2'];
      $email      = $_POST['email'];

      // if Isset UserName Came From Post And Do The Validate With Filter
      if (isset($username)) {
        if (empty($username) && ($username != ' ' || $username != '  ' || $username != '   ')) {
          $formErrors[] = "Username Can't BE Empty";
        }
        $filterUser = filter_var($username , FILTER_SANITIZE_STRING);
        if (strlen($filterUser) < 4) {
          $formErrors[] = "Username Must Be Larger Than 4 Characters";
        }
      }
      // If Isset Password Came Form Post ANd Do Confirmation With Both
      if (isset($password) && isset($password2)) {
        if (empty($password)) {
          $formErrors[] = "Password Can't Be Empty";
        }
        // Hashed The Password TO Sha1 Function
        if (sha1($password) !== sha1($password2)) {
          $formErrors[] = " The Password Dos'nt Match";
        }
      }
      // Isset Email Came From The Post And Do The Validate With Filter email
      if (isset($email)) {
        $filterEmail = filter_var($_POST['email'],  FILTER_SANITIZE_EMAIL);
        if (filter_var($filterEmail, FILTER_VALIDATE_EMAIL) != true) {
          $formErrors[] = "Your Email Is Not Valid";
        }
      }
      // Check If There No Error And Proceedd The Next Step
      if (empty($formErrors)) {
        // If There's No Errors Then Do The Flowing Thing
          $check1 = CheckUser("Username" , "users" , $username);

          if ($check1 == 1) {

            $formErrors[] = "Sorry This User Is Exists ";

          }else {

            // Insert The User Into The Database
            $stmtment = $con->prepare("INSERT INTO users(Username , Password , Email , RegStatus , RegDate)
                                                VALUES(?,?,?,0 , Now())");
            $stmtment->execute(array($username, sha1($password) , $email));
            //  If The Statement Is Executed  Then Give Me A Message Note
            $success = "<div class='alert alert-success'> Congratz You Have Been Regisrtered Successful :)</div>";
            $_SESSION['user'] = $username;

          }
      }// End If Of Errors Looping
    }
  }
 ?>
<div class="container login-page">
  <?php if (isset($error1)): echo $error1; ?>
    <form class="signin" action="Login.php" method="POST">
      <div class="form-group">
        <!-- Start UserName Field -->
        <input type="text" name="username" value="<?php echo $User ;?>" class="form-control" autocomplete="off" required placeholder="Type Your Username ">
        <!-- End Useername -->
      </div>
      <div class="form-group">
        <!-- Start password Field -->
        <input type="password" name="password" value="<?php echo $Pass ;?>" class="form-control" autocomplete="new-password" required placeholder="Type Your Password ">
        <!-- End password -->
      </div>
      <div class="form-group">
        <!-- Start submit Field -->
        <input type="submit"  class="btn btn-primary btn-block" name="login"    value="Login" >
        <!-- End submit -->
      </div>
    </form>
  <?php endif; ?>
  <?php if (!empty($formErrors)): foreach ($formErrors as $error){
    echo "<div class='alert alert-danger' style='max-width:430px ;margin: 10px auto'>" . $error . "</div>";
  }?>
  <form class="" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <!--Start Username -->
    <div class="form-group">
        <input pattern=".{4,}"
        title="Username Must Be More Than 4 Characters"
         type="text" name="username"  value="<?php echo $filterUser ;?>" class="form-control" autocomplete="off" placeholder="Type Your Username "  required>
    </div>
    <!-- End Username -->
    <!--Start password -->
    <div class="form-group">
        <input
        minlength="4"
        type="password" name="password"  value="<?php echo $_POST['password'] ;?>" class="form-control" autocomplete="new-password" placeholder="Type Your Complexity Password "  required>
    </div>
    <!-- End password -->
    <!--Start Username -->
    <div class="form-group">
        <input
        minlength="4"
        type="password" name="password2"  value="<?php echo $_POST['password2'] ;?>" class="form-control" autocomplete="new-password" placeholder="Type Your Complexity Password "  required>
    </div>
    <!-- End Username -->
    <!--Start Username -->
    <div class="form-group">
        <input type="email" name="email"  value="<?php echo $filterEmail ;?>" class="form-control" autocomplete="off" placeholder="Type Your Valid Email " required >
    </div>
    <!-- End Username -->
    <!--Start submit -->
    <div class="form-group">
        <input type="submit" class="btn btn-success btn-block"  value="Sign Up" >
    </div>
    <!-- End submit -->
  </form>
<?php endif;?>
<?php if (isset($success)):
  $thMsg = $success;
  redirectHome($thMsg ,5);
   ?>

<?php endif; ?>
</div>

<?php include $tpl . 'footer.php';
  ob_end_flush();
?>
