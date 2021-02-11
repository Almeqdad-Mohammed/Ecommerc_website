<?php
ob_start();
session_start();
$pageTitle = 'Profile';
include 'init.php';
if (isset($_SESSION['user'])) {
    // Get Information Of zthis user
    $getuser = $con->prepare("SELECT * FROM users WHERE Username = ?");
    $getuser->execute(array($sessionUser));
    $info = $getuser->fetch();
    $do = isset($_GET['do'])? $_GET['do'] : 'Profile';

    if ($do == 'Profile') { //  Get The Main Info ?>
      <h1 class="text-center">My Profile</h1>
      <div class="Inform ">
        <div class="container block">
          <div class="profileImage">
            <?php
                  if (empty($info['Profile'])): ?>
                  <img src="Admin/Uploads/Profile/Default.jpg" alt="Profile Image"></i>

                <?php else:
                  echo "<img src='Admin/Uploads/Profile/" . $info['Profile'] . "' alt='ProfileImage'/>";

                endif;
              ?>
              <span><?php echo $info['Username'] ?></span>
          </div>
          <div class="card " >
            <div class="card-header">
              My Main Info
            </div>
            <div class="card-body">
              <ul class="list-unstyled">
              <li>
                <i class="fa fa-unlock fa-fw"></i>
                <span>  UserName </span>: <?php echo $info['Username'] ?>
              </li>
              <li>
                <i class="fa fa-envelope fa-fw"></i>
                <span>E-mail </span>:  <?php echo $info['Email'] ?>
              </li>
              <li>
                <i class="fa fa-user fa-fw"></i>
                  <span>Full Name </span>: <?php echo $info['FullName'] ?>
              </li>
              <li>
                <i class="fa fa-tags"></i>
              <span>Favourite Category </span>:
              </li>
              </ul>
              <button class="btn btn-default ">Edit Your Info</button>
              <?php
              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                //Get The Variable OF Profile Image
                $profileName  = $_FILES['profile']['name'];
                $profileTmp   = $_FILES['profile']['tmp_name'];
                $profileType  = $_FILES['profile']['type'];
                $profileSize  = $_FILES['profile']['size'];

                // List Of Allowed Exetenion
                $profileAllowedExetenion = array("jpeg" ,"jpg" ,"png", "gif");
                // Exentenion Allowed
                $profileExetenion = @ strtolower(end(explode('.', $profileName)));

                // Get The Variable Of Edit Info
                $userid   = $_POST['userid'];
                $username = $_POST['username'];
                $full     = $_POST['full'];
                $email    = $_POST['email'];

                $pass = empty($_POST['password'])? $_POST['oldpassword'] : sha1($_POST['password']);
                // VLaidation The Form
                $formErrors  = array();
                if (strlen($username) < 4 ) {
                  $formErrors[] = "<div class='alert alert-danger'>Username Can't Be Less Than 4 Charactar</div>";
                }
                if (empty($pass)) {
                  $formErrors[] = "The Password Can't Be Empty";
                }
                if (strlen($full) < 20) {
                  $formErrors[] = '<div class="alert alert-danger">FullName Can\'t Be Less Than 20 Charactar</div>';
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
                // Loop The Form Error And Echo It
                foreach ($formErrors as $error) {
                  echo $error;
                }
                // If No Error Update The Info
                if (empty($formErrors)) {
                  // Great The Variable Name Of Profile Image
                  $profile = rand(0, 100000000000) . "_" . $profileName;
                  // Upload The Profilr Image
                  move_uploaded_file($profileTmp , "Admin/Uploads/Profile/" . $profile);
                  //Check THe Item Before Updated
                  $check = CheckItem("Username" , "users" , $username );
                  $stmts = $con->prepare("SELECT * FROM users WHERE Username = ? AND UserID != ? ");
                  $stmts->execute(array($username , $userid));
                  $count = $stmts->rowCount();
                  if ($count > 0) {
                    $Error =  "<div class='alert alert-danger'>Sorry This User Is Exist</div>";
                     redirectHome($Error);

                  }else {
                    $stmt = $con->prepare("UPDATE users SET Username = ?, FullName = ? , Password = ?, Email = ?, Profile = ?");
                    $stmt->execute(array($username, $full, $pass , $email ,$profile));
                    if ($stmt) {
                      $success = "<div class='alert alert-success'> Your Info Has Been Updated</div>";
                       redirectHome($success);
                    }
                  }
                }
              }?>
              <!-- start Edit Info  -->
              <form class="signup" action="<?php echo  $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="userid" value="<?php echo $info['UserID'];?>">
                <!--Start Username -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Username</label>
                  <div class="col-sm-10 col-md-8">
                    <input
                    pattern=".{4,}"
                    type="text" name="username" class="form-control" autocomplete="off" value="<?php echo $info['Username'] ?> " required>
                  </div>
                </div>
                <!-- End Username -->
                <!--Start FullName -->
                <div class="form-group">
                  <label class="col-sm-2 control-label"> Full Name</label>
                  <div class="col-sm-10 col-md-8">
                    <input
                    pattern=".{4,}"
                    type="text" name="full" class="form-control" autocomplete="off" value="<?php echo $info['FullName'] ?> " required>
                  </div>
                </div>
                <!-- End FullName -->
                <!--Start password -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-8">
                      <input
                      minlength="4"
                      type="password" name="password" class="form-control"
                      placeholder="Type A Complex Password" autocomplete="new-password" value=""  >
                      <input hidden type="hidden" name="oldpassword" value="<?php echo $info['Password'];?>">
                    </div>
                </div>
                <!-- End password -->

                <!--Start Email -->
                <div class="form-group">
                  <label class="col-sm-2 control-label"> E-mail</label>
                  <div class="col-sm-10 col-md-8">
                    <input type="email" name="email" class="form-control" autocomplete="off" value="<?php echo $info['Email'] ?>" required >
                  </div>
                </div>
                <!-- End Email -->
                <!--Start Profile Feild-->
                  <div class="form-group">
                    <label class="col-sm-2 control-label">Profile Image</label>
                    <div class="col-sm-10 col-md-8">
                      <input type="file" name="profile" class="form-control"   required="required">
                    </div>
                  </div>
                <!--End Profile Feild-->
                <!--Start submit -->
                <div class="form-group">
                    <input type="submit" class="btn btn-success btn-block" name="signup" value="Edit Info " >
                </div>
                <!-- End submit -->

              </form>
              <!-- End Edit Info  -->
            </div>
          </div>
        </div>
        <div class="container block" id="my_ads">
          <div class="card " >
            <div class="card-header">
              My Advertisment
            </div>
            <div class="card-body">
              <div class="row">
                <?php
                $userid = $info['UserID'];
                $getAll = getALlFrom("*", "items", "WHERE Member_ID = {$userid}" , "" , "ID");
                if (! empty($getAll)) {
                  foreach ($getAll as $ads) {
                    echo "<div class='col-sm-6 col-md-3'>";
                      echo "<div class='thumbnail item-box'>";
                       echo "<span class='price-tag'>$" . $ads['Price'] . "</span>";
                       if ($ads['Approve'] == 0) {
                         echo "<span class='not_approve'>Not Approved</span>";
                       }
                       if (empty($ads['Image'])) {
                         // code...
                         echo "<img src='Layout/Images/one.jpg' class='card-img-top' />";
                       }else {
                         echo "<img src='Admin/Uploads/" . $ads['Image'] . "' class='card-img-top' />";
                       }
                        echo "<div class='caption'>";
                            echo "<h3 class=''><a href='product.php?productid=" . $ads['ID'] . "'>" . $ads['Name'] . "</a></h3>";
                            echo "<p class=''>" . $ads['Description'] . "</p>";
                            echo "<div class='Date'>" . $ads['Add_Date'] . "</div>";
                        echo "</div>";
                      echo "</div>";
                    echo "</div>";
                  }
                } else {
                  echo "<div class='custome-message'>There's Is Not Ads To Show </div>";
                }
                 ?>
              </div>
            </div>
          </div>
        </div>
        <div class="container block">
          <div class="card " >
            <div class="card-header">
              My Latest Comments
            </div>
            <div class="card-body">
              <?php
              $mycomment = getALlFrom("Comment" , "comments", "where User_ID = $userid", "", "C_ID");
              // $stmt2 = $con->prepare("SELECT Comment FROM comments WHERE User_ID = ?");
              // $stmt2->execute(array($info['UserID']));
              // $comments = $stmt2->fetchAll();
              if (! empty($mycomment)) {
                foreach ($mycomment as $comment) {
                  echo "<p >" . $comment['Comment'] . "</p>";
                }
              } else {
                echo "<div class='custome-message'> THere's Is No Comment To Show </div>";
              }
               ?>
            </div>
          </div>
        </div>
      </div>
<?php }elseif ($do == 'Edit') {
      // code...
    }elseif ($do == 'Update') {

      }else {
        echo "<div class=' alert alert-danger'> You Can't Browse This page Directly</div>";
      } // End If Of Request Method
    }else {
  header("Location:Index.php");
  exit();
}


include $tpl . 'footer.php';
ob_end_flush(); ?>
