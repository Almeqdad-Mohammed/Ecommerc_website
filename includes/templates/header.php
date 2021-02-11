
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo getTitle(); ?></title>
    <link rel="stylesheet" href="<?php echo $css . 'bootstrap.css' ?>">
    <link rel="stylesheet" href="<?php echo $css . 'font-awesome.css' ?>">
    <link rel="stylesheet" href="<?php echo $css . 'front.css' ?>">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container " style="width:">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#App-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="App-nav">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link brand " href="Index.php">Home </a>
          </li>
          <?php
          $getALLcat = getALlFrom("*" , "categories", " where  Parent = 0", "" , "ID");
          foreach ($getALLcat as $cat) {
            echo "<li>
                      <a class='nav-link' href='categories.php?pageid=" . $cat['ID'] . "&pagename=" . str_replace(' ', '-', $cat['Name']) . "'>
                      " .  $cat['Name'] . "
                      </a>
                  </li>";
          }
           ?>

        </ul>

        <?php
          if (isset($_SESSION['user'])) {?>
            <?php
            $userStatus = CheckUserStatus($sessionUser);
            if ($userStatus == 1) {
            //  echo $SetStatus =  "<span class='Asteriks'>*</span>";
            }
          ?>
            <div class="nav-item dropdown pull ProfileImage">
              <a class="nav-link  dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php echo $sessionUser ?>
                <?php
                // Get THe UserID Form Database THrougth The User Name Session
                $onerecord = $con->prepare("SELECT UserID FROM users WHERE Username = ?");
                $onerecord->execute(array($sessionUser));
                $record = $onerecord->fetch();
                $uid  = $record['UserID'];
                // select User PRofile THrougth THe UserName And UserID
                $getImage = getALlFrom("*" ,"users" , "WHERE Username =  '$sessionUser' "  , "And UserID = '$uid'" , "UserID");
                    foreach ($getImage as $img) {
                      if (empty($img['Profile'])){
                        echo '<img src="Admin/Uploads/Profile/Default.jpg" alt="Profile Image"/>';

                      }else{
                      echo "<img src='Admin/Uploads/Profile/" . $img['Profile'] . "' alt='ProfileImage'/>";
                    }
                  }
                  ?>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="profile.php?do=Profile"<?php  echo $sessionUser?>> Profile</a>
                <a class="dropdown-item" href="newAds.php?do=Manage">New Item</a>
                <a class="dropdown-item" href="#my_ads">My Item</a>
                <a class="dropdown-item" href="logout.php">Logout</a>
              </div>
            </div>
      <?php
    }else {
        //echo "<a class='nav-link' href='Login.php'>Sign In</a>";
        echo "<div class='btn btn-default'  data-toggle='modal' data-target='#mymodal' style='color:#FFF;cursor:pointer'>Sign In | Sign Up</div>";
      }
         ?>
         <!-- Form -->
         <div class=" login-page modal fade" id="mymodal">
        <div class="modal-dialog">
          <div class="modal-content">
           <h1 class="text-center">
             <span class="selected" data-class="signin">Sign In</span> |
             <span data-class="signup">Sign Up</span>
           </h1>
           <form class="signin" action="Login.php" method="POST">
             <div class="form-group">
               <!-- Start UserName Field -->
               <input type="text" name="username" class="form-control" autocomplete="off" required placeholder="Type Your Username ">
               <!-- End Useername -->
             </div>
             <div class="form-group">
               <!-- Start password Field -->
               <input type="password" name="password" class="form-control" autocomplete="new-password" required placeholder="Type Your Password ">
               <!-- End password -->
             </div>
             <div class="form-group">
               <!-- Start submit Field -->
               <input type="submit"  class="btn btn-primary btn-block" name="login"    value="Login" >
               <!-- End submit -->
             </div>
           </form>
           <!--start another form for signup -->
           <form class="signup" action="Login.php" method="post">
             <!--Start Username -->
             <div class="form-group">
                 <input
                 pattern=".{4,}"
                 type="text" name="username" class="form-control" autocomplete="off" placeholder="Type Your Username "  required>
             </div>
             <!-- End Username -->
             <!--Start password -->
             <div class="form-group">
                 <input
                 minlength="4"
                 type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Type Your Complexity Password " required >
             </div>
             <!-- End password -->
             <!--Start Username -->
             <div class="form-group">
                 <input
                 minlength="4"
                 type="password" name="password2" class="form-control" autocomplete="new-password" placeholder="Type Your Complexity Password " required >
             </div>
             <!-- End Username -->
             <!--Start Username -->
             <div class="form-group">
                 <input type="email" name="email" class="form-control" autocomplete="off" placeholder="Type Your Valid Email " required >
             </div>
             <!-- End Username -->
             <!--Start submit -->
             <div class="form-group">
                 <input type="submit" class="btn btn-success btn-block" name="signup" value="Sign Up" >
             </div>
             <!-- End submit -->

           </form>
           <span class="btn btn-default" data-dismiss="modal"> <i class="fa fa-close" ></i> </span>
         </div>
       </div>
     </div>
         <!-- End Form-->

    </div> <!--Close The container -->
    </nav>
