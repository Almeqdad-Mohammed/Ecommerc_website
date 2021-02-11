<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container " style="width:">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#App-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="App-nav">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link brand " href="dashboard.php">Home </a>
      </li>
      <?php
      foreach (GetCat() as $cat) {
        echo "<li><a href='#'>" .  $cat['Name'] . "</a></li>";
      }
       ?>
      </li>
    </ul>
    <?php
      if (isset($_SESSION['User'])) {?>
        <div class="nav-item dropdown pull">
          <a class="nav-link  dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Almgdad
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
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="members.php?do=Edit&userid=<?php  echo $_SESSION['ID']?>">Edit Profile</a>
            <a class="dropdown-item" href="#">Setting</a>
            <a class="dropdown-item" href="logout.php">Logout</a>
          </div>
  <?php }else {
    echo "<a href='#'>Sign In</a>";
  }
     ?>

      </div>
      </div>
  </div>
</div> <!--Close The container -->
</nav>
