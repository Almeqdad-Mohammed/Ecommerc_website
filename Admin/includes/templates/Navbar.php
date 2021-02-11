<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container " style="width:">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#App-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="App-nav">
      <ul class="navbar-nav mr-auto">
      <li class="nav-item active link">
        <a class="nav-link brand " href="dashboard.php"><i class="fa fa-home"></i>
        <span >Home</span></a>

      </li>
      <li class="nav-item  link">
        <a class="nav-link" href="categories.php"><i class="fa fa-list-alt"></i>
          <?php //echo lang('SECTIONS')?><span >Categories</span> </a>

      </li>
      <li class="nav-item link">
        <a class="nav-link" href="items.php"><i class="fa fa-shopping-bag"></i>
          <span >items</span>
         </a>

      </li>
      <li class="nav-item link">
        <a class="nav-link" href="members.php"><i class="fa fa-users"></i>
            <span >Members</span>
        </a>

      </li>
      <li class="nav-item link">
        <a class="nav-link" href="comments.php"><i class="fa fa-comments-o"></i>
            <span >Comments</span>
        </a>
      </li>
      <li class="nav-item link">
        <a class="nav-link" href="#"><i class="fa fa-line-chart" ></i>
        <span>  Statistics</span> </a>
      </li>
      <li class="nav-item link">
        <a class="nav-link" href="#"><i class="fa fa-sign-in" ></i>
        <span>Logs </span></a>
      </li>
    </ul>
      <div class="nav-item dropdown link Profile_Image">
        <a class="nav-link  dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php
          $getImage = getALlFrom("*" ,"users" , "WHERE UserID = {$_SESSION['ID']}" , "" , "UserID");
              foreach ($getImage as $img) {
                if (empty($img['Profile'])): ?>
                <i class="fa fa-user-o"></i>

              <?php else:
                echo "<img src='Uploads/Profile/" . $img['Profile'] . "' alt='ProfileImage'/>";

              endif;
            }
            ?>


          <span> <?php  echo $_SESSION['Username']?> </span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="members.php?do=Edit&userid=<?php  echo $_SESSION['ID']?>">Edit Profile</a>
          <a class="dropdown-item" href="#">Setting</a>
          <a class="dropdown-item" href="logout.php">Logout</a>
        </div>
      </div>
      </div>
  </div>
</div> <!--Close The container -->
</nav>
