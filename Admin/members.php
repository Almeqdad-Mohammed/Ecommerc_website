<?php
session_start();
$pageTitle = 'Members';
if (isset($_SESSION['Username'])) {

  include 'init.php';

  $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
  // Start Amange Page
  if ($do == 'Manage') { // Manage Mambers Pages
    $query = '';
    if (isset($_GET['page']) && $_GET['page'] == 'Pending') {
      $query = "AND RegStatus = 0";
    }
    // Select All USer Except  Admin
    $stmt = $con->prepare("SELECT * FROM users WHERE GroupID != 1 $query ORDER BY UserID DESC");
    // Execute The Statement
    $stmt->execute();

    //Assign To Variable
     $rows = $stmt->fetchAll();
      if (! empty($rows)) {
      ?>
          <h1 class="text-center">Manage Members</h1>
          <div class="container">
            <div class="table-responsive">
              <table class="main-table manage-members text-center table table-bordered">
                <tr>
                  <td>UserID</td>
                  <td>Profile</td>
                  <td>Username</td>
                  <td>Email</td>
                  <td>FullName</td>
                  <td>RegesterDate</td>
                  <td>Control</td>
                </tr>
                <?php
                foreach ($rows as $row) {
                  echo "<tr>";
                      echo "<td>" . $row['UserID'] . "</td>";
                      echo "<td>";
                      if (empty($row['Profile'])) {
                        echo "<img src='Uploads/Profile/Default.jpg' alt='No Image'/>";
                      }else {
                      echo  "<img src='Uploads/Profile/" . $row['Profile'] . "' alt= 'Profile' />";
                      }
                      echo "</td>";
                      echo "<td>" . $row['Username'] . "</td>";
                      echo "<td>" . $row['Email'] . "</td>";
                      echo "<td>" . $row['FullName'] . "</td>";
                      echo "<td>" . $row['RegDate'] . "</td>";
                      echo "<td>
                          <a href='members.php?do=Edit&userid=". $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>
                          <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>";
                          if ($row['RegStatus'] == 0) {
                            echo "<a href='members.php?do=Approve&userid=" . $row['UserID'] . "' class='btn btn-info '><i class='fa fa-check'></i></a>";
                          }
                      echo "</td>";
                  echo "</tr>";
                }
                 ?>

              </table>
            </div>
            <a href='members.php?do=Add' class="btn btn-primary btn-bottom"><i class="fa fa-plus"></i>  New Member</a>
          </div>
        <?php }else {
            echo "<div class='container'>";
              echo "<div class='custome-message'>There's No Members To Show</div>";
              echo '<a href="members.php?do=Add" class="btn btn-primary add-button">
              <i class="fa fa-plus fa-fx"></i> Add Member</a>';
            echo "</div>";
              }?>
  <?php }elseif ($do == 'Add') { // Add New Members ?>
    <h1 class="text-center">Add New Member</h1>
    <div class="container">
      <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
        <!--Start Username-->
          <div class="form-group">
            <label class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10 col-md-8">
              <input type="text" name="username" class="form-control" placeholder=" Username To Login Into Shop" autocomplete="off" required="required">
            </div>
          </div>
        <!--End Username-->
        <!--Start Password-->
          <div class="form-group">
            <label class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10 col-md-8">
              <input type="password" name="password" class="password form-control" placeholder=" Password Must Hard And Complex" autocomplete="new-password" required="required">
              <i class="show-pass fa fa-eye fa-lg"></i>
            </div>
          </div>
        <!--End Password-->
        <!--Start email-->
          <div class="form-group">
            <label class="col-sm-2 control-label">Email</label>
            <div class="col-sm-10 col-md-8">
              <input type="email" name="email" class="form-control" placeholder="Email Must Be Valid " autocomplete="off" required="required">
            </div>
          </div>
        <!--End email-->
        <!--Start FullName-->
          <div class="form-group">
            <label class="col-sm-2 control-label">Full Name</label>
            <div class="col-sm-10 col-md-8">
              <input type="text" name="full" class="form-control" placeholder=" Full Name Will Apear In Profile Page" autocomplete="off" required="required">
            </div>
          </div>
        <!--End FullName-->
        <!--Start Profile Feild-->
          <div class="form-group">
            <label class="col-sm-2 control-label">Profile Image</label>
            <div class="col-sm-10 col-md-8">
              <input type="file" name="profile" class="form-control"   required="required">
            </div>
          </div>
        <!--End Profile Feild-->
        <!--Start submit-->
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <input type="submit" value="Add Member" class="btn btn-primary">
            </div>
          </div>
        <!--End submit-->
      </form>
    </div>
  <?php
}elseif ($do == 'Insert') {
    // Insert Page

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          echo "<h1 class='text-center'>Insert Member</h1>";
          echo "<div class='container'>";
          // Files Uploads Variable
          $profileName  = $_FILES['profile']['name'];
          $profileTmp   = $_FILES['profile']['tmp_name'];
          $profileSize  = $_FILES['profile']['size'];
          $profileType  = $_FILES['profile']['type'];

          // List Of Allowewd File To Uploads
          $profileAllowedExetenion  = array("jpeg", "jpg", "png", "gif");

          // Get PRofile Exetenion
          @ $profileExetenion =  strtolower(end(explode('.' , $profileName)));
          // get The Variable from  the Form
          $user = $_POST['username'];
          $pass = $_POST['password'];
          $email = $_POST['email'];
          $name = $_POST['full'];
          // Password Trick
          $hashpass = sha1($_POST['password']);
            // Validate The form
            $formErrors = array();
            if (strlen($user) < 4 ) {
              $formErrors[]= "Username Must Be More Than 4 Charactar";
            }
            if (strlen($user) > 20 ) {
              $formErrors[]= "Username Must Be less Than 20 Charactar";
            }
            if (empty($user)) {
              $formErrors[]= "Username can't Be Empty";
            }
            if (empty($pass)) {
              $formErrors[]= "Password can't Be Empty";
            }
            if (empty($email)) {
              $formErrors[]= "Email can't Be Empty";
            }
            if (empty($name)) {
              $formErrors[]= "FullName can't Be Empty";
            }
            if (! empty($profileName) && ! in_array($profileExetenion, $profileAllowedExetenion)) {
              $formErrors[] = "This Exetenion Is Not Allowewd";
            }
            if (empty($profileName)) {
              $formErrors[] = "Profile Image Can't Be Empty";
            }
            if ($profileSize > 4194304) {
              $formErrors[] = "The Profile Image Can't BE Larger Than 4MB";
            }
            // Loop Into The Errors  Array And Echo  It
            foreach ($formErrors as $error) {
              echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            // check If There's No Error Proceed Insert To database
            if (empty($formErrors)) {

              // Get Random Name OF PRofile Image
              $profile = rand(0, 100000000000) . "_" . $profileName;
              move_uploaded_file($profileTmp, "Uploads/Profile/" . $profile );
              // Check Item From Database

              $check = CheckItem("Username" , "users" , $user );

              if ($check == 1) {
                $thMsg = "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                redirectHome($thMsg , 'back');
              } else {
                // Insert UserInfo Into Database


                    $stmt = $con->prepare("INSERT INTO users(Username , Password, Email, FullName , RegStatus , RegDate , Profile )
                    VALUES(?, ?, ?, ?,1, now() , ?)");
                    // Here I Used nothe Way Set Values Int Database By values(?,?,?,) And Executed It Direc Without Any Associative Array
                    // Instead Of This Values(:zuser, :zpass, :zemail)
                    $stmt->execute(array( $user, $hashpass, $email,$name , $profile
                    ));

                    // Echo  Success Message
                    $thMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted </div>";
                    redirectHome($thMsg, 'back' );
                  }
              }


        echo "</div>"; // End Class  Container
        }else {
          $thMsg = "<div class='alert alert-danger'>Sorry you Can't Browse This PAge Direct</div>";
          redirectHome($thMsg);
        }

     }elseif ($do == 'Edit') {// Welcome To Edit Page
    // Check If Get Request Userid IS Numeric & Get The Integer Value OF It
    $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
    // select All Data Depend O this  Id
    $stmt = $con->prepare("SELECT * FROM users WHERE UserID = ? LIMIT 1");
    // Execute The Query
    $stmt->execute(array($userid));
    // Fetch The  Data
    $row = $stmt->fetch();
    // Check Row Count
    $count = $stmt->rowCount();
    // IF There's ID Show The Form
    if ($count > 0) { ?>
        <h1 class="text-center">Edit Member</h1>
        <div class="container">
          <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <!--Start Username Feild-->
            <div class="form-group ">
              <label class="col-sm-2 control-label">Username</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" name="username" class="form-control" value="<?php echo $row['Username']?>" autocomplete="off" required="required">
              </div>
            </div>
            <!--End Username feild-->
            <!--Start password Feild-->
            <div class="form-group">
              <label class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10 col-md-8">
                <input type="hidden" name="oldpassword"  value="<?php echo $row['Password']?>">
                <input type="password" name="newpassword" class="form-control"  autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change">
              </div>
            </div>
            <!--End password feild-->
            <!--Start Email Feild-->
            <div class="form-group">
              <label class="col-sm-2 control-label">E-mail</label>
              <div class="col-sm-10 col-md-8">
                <input type="email" name="email" class="form-control" value="<?php echo $row['Email']?>" autocomplete="off" required="required">
              </div>
            </div>
            <!--End Email feild-->
            <!--Start FullName Feild-->
            <div class="form-group">
              <label class="col-sm-2 control-label">Full Name</label>
              <div class="col-sm-10 col-md-8">
                <input type="text" name="full" class="form-control" value="<?php echo $row['FullName']?>" autocomplete="off" required="required">
              </div>
            </div>
            <!--End FullName feild-->
    <!--Start Profile Feild-->
      <div class="form-group">
        <label class="col-sm-2 control-label">Profile Image</label>
        <div class="col-sm-10 col-md-8">
          <input type="file" name="profile" class="form-control"   required="required">
        </div>
      </div>
    <!--End Profile Feild-->
            <!--Start submit Feild-->
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="submit" value="Save" class="btn btn-primary">
              </div>
            </div>
            <!--End submit feild-->
          </form>
        </div>
  <?php
  // IF There's No Such ID Show Error Message
      }else {
        echo "<div class='container'>";
            $thMsg = "<div class='alert alert-danger'>There's No Such ID</div>";
            redirectHome($thMsg);
        echo "</div>";
         }

   }elseif ($do == 'Update') { // Update Page
    echo "<h1 class='text-center'>Update Member</h1>";
    echo "<div class='container'>";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

      $profileName  = $_FILES['profile']['name'];
      $profileTmp   = $_FILES['profile']['tmp_name'];
      $profileSize  = $_FILES['profile']['size'];
      $profileType  = $_FILES['profile']['type'];

      // List Of Allowewd File To Uploads
      $profileAllowedExetenion  = array("jpeg", "jpg", "png", "gif");

      // Get PRofile Exetenion
      @ $profileExetenion =  strtolower(end(explode('.' , $profileName)));
      // Get The Variable Form The form
      $id     = $_POST['userid'];
      $user   = $_POST['username'];
      $email  = $_POST['email'];
      $name   = $_POST['full'];

      // Trick PAssword

      $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);
      // Validate The form
      $formErrors = array();
      if (strlen($user) < 4 ) {
        $formErrors[] = "<div class='alert alert-danger'>Username Can't Be Less Than 4 Charactar</div>";
      }
      if (empty($name)) {
        $formErrors[] = "<div class='alert alert-danger'>Full Name Can't Be Empty</div>";
      }
      if (empty($email)) {
        $formErrors[] = "<div class='alert alert-danger'>Email Can't Be Empty</div>";
      }
      if (! empty($profileName) && ! in_array($profileExetenion, $profileAllowedExetenion)) {
        $formErrors[] = "The Exetenion is Not Allowed";
      }
      if ($profileSize > 4194304) {
        $formErrors[] = "The Profile Image Can't  Be Larger Than 4MB";
      }
      if (empty($profileName)) {
        $formErrors[] = "The Profile Image Can't Be Empty";
      }
      // Loop Into Errors Array And Echo It
      foreach ($formErrors as $error) {
        echo $error;
      }
        // If theres No Errors Proceed The Operation
        if (empty($formErrors)) {
          // Get Random Name OF PRofile Image
          $profile = rand(0, 100000000000) . "_" . $profileName;
          move_uploaded_file($profileTmp, "Uploads/Profile/" . $profile );

          //Check THe Item Before Updated
          $check = CheckItem("Username" , "users" , $user );
          $stmts = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ? ");
          $stmts->execute(array($user , $id));
          $count = $stmts->rowCount();
          if ($count > 0) {
            $thMsg = "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
            redirectHome($thMsg, 'back');
          }else {
            //  Update The Database With Info
            $stmt = $con->prepare("UPDATE users SET Username = ?, Email = ?, Password = ?, FullName = ? , Profile = ? WHERE UserID = ? ");
            $stmt->execute(array($user , $email, $pass, $name , $profile , $id));
            // Echo Success Message

            $thMsg = '<div class="alert alert-success">' . $stmt->rowCount() . ' Member Updated</div>';
            redirectHome($thMsg ,'back' );
          }

        } # End OF If For Empty Error


    }else {

      $thMsg =  "<div class='alert alert-danger'>Sorry You  Can Browse This Page Direct </div>";
      redirectHome($thMsg);
    }
    echo '</div >';
  }elseif ($do == 'Delete') { // delete Member Pages
    echo "<h1 class='text-center'>Delete Member</h1>";
    echo "<div class='container'>";
        // Check If  Get Request userid Is Numeric And Get The Intger Value OF It
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
        // select All Depend On This ID
        $check = CheckItem("userid" , "users" , $userid );

        if ($check > 0 ) {
          $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zid");
          $stmt->bindParam(":zid", $userid);
          $stmt->execute();
          // Echo The Success  Message

          $thMsg  = "<div class='alert alert-success'>" . $stmt->rowCount() . "  Member Deleted </div>";
          redirectHome($thMsg, 'back');

        }else {
          echo "<div class='container'>";
            $thMsg =  "<div class='alert alert-danger'>Sorry This ID Not Exist </div>";
            redirectHome($thMsg, 'back');
          echo "</div>";
        }
    echo "</div>";
  }elseif ($do == 'Approve') {
    echo "<h1 class='text-center'>Apprave Member</h1>";
    echo "<div class='container'>";
    // check If Get Request Is userid Is Numeric And GEt The Integer VAlue OF IT
      $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0 ;
    // Select All Data Depend On This Id
    $check  =  CheckItem("userid ", "users" , $userid);
    if ($check > 0) {
      $stmt = $con->prepare("UPDATE  users SET RegStatus = 1 WHERE UserID = ?");
      $stmt->execute(array($userid));

      //Echo Success Message
      $thMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Done Member Approval :)</div>";
      redirectHome($thMsg, 'back');
    }
    echo "</div>";
  }
?>

  <?php
   include $tpl . 'footer.php';
}else {
  header('location: index.php');
  exit();
}





 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

  </body>
</html>
