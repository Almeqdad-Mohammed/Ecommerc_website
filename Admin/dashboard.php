<?php
ob_start("ob_gzhandler"); // Output Buffering Start
session_start();
if (isset($_SESSION['Username'])) {
  $pageTitle = 'Dashboard';

  include 'init.php';
  # Start Dashboard page
  $numUser = 5 ; // Number Of latest user
  $latestUser = getlatest("*", "users" , "UserID" , $numUser); // Latest User Array
  $numProduct = 5; // Number Of Latest Items
  $latestItem  = getlatest("*" , "items" , "ID", $numProduct); // Latest Items Array
  $numComments = 5; // Number Of Latest Comments
   ?>
  <div class="container home-stats text-center ">
    <div class="dash">
            <h1 class="text-center">Dashboard</h1>
    </div>
      <div class="row content">
        <div class="col-md-3">
          <div class="stats st-members">
            <a href="members.php"><i class="fa fa-users"></i></a>
            <span>All Members</span>
            <span>
                <a href="members.php">
                  <?php echo Countitems('UserID', 'users')?>
                </a>
            </span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stats st-pending">
            <a href="members.php"><i class="fa fa-user-plus"></i></a>
            <span>Pending Members</span>
             <span>
              <a href="members.php?do=Manage&page=Pending">
              <?php echo CheckItem("RegStatus" , "users" , 0); ?>
            </a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stats st-items">
            <a href="members.php"><i class="fa fa-shopping-bag"></i></a>
            <span>All Products</span>
             <span>
              <a href="items.php">
                <?php echo Countitem('ID', 'items')?></a></span>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stats st-comments">
            <a href="members.php"><i class="fa fa-comments"></i></a>
            <span>All Comments</span>
            <span>
              <a href="comments.php">
              <?php echo Countitem("C_ID" , "comments")  ?></a></span>
          </div>
        </div>
      </div>
  </div>
  <div class="container latest">
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-users"></i> <span>Latest <mark><?php echo $numUser ?></mark> Regester Users</span>
            <span class="toggle-info pull-right">
              <i class="fa fa-plus fa-lg"></i>
            </span>
          </div>
          <div class="card-body">
            <ul class="list-unstyled latest-users">
              <?php
              if (! empty($latestUser)) {
                  foreach ($latestUser as $user) {
                        echo "<li>";
                        echo "<span>";
                          if (empty($user['Profile'])) {
                          echo "<img src='Uploads/Profile/Default.jpg' alt='No Iamge'/>";
                        }else {
                          echo "<img src='Uploads/Profile/" . $user['Profile'] . "' alt='Profile Image' / >";
                        }
                        echo "</span>";
                          echo $user['Username'] ;
                          if ($user['RegStatus'] == 0) {
                            echo "<a href='members.php?do=Approve&userid=" . $user['UserID'] . "' class='btn btn-info pull-right'><i class='fa fa-check'></i></a>";
                          }
                          echo "<a href='members.php?do=Edit&userid=" . $user['UserID'] . "'>";
                          echo "<span class='btn btn-success pull-right'>";
                          echo  "<i class='fa fa-edit'></i>";
                               "</span>";
                             echo "</a>";
                        echo "</li>";

                  }
                }else {
                  echo "<div class='custome-message'>There's No Members To Show</div>";
                }

              ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-shopping-bag"></i> <span>Latest  <mark><?php echo $numProduct ?></mark> Products</span>
            <span class=" toggle-info pull-right">
              <i class="fa fa-plus fa-lg"></i>
            </span>
          </div>
          <div class="card-body">
            <ul class="list-unstyled latest-items">
              <?php
              if (! empty(@$latestItem)) {
                  foreach ($latestItem as $item) {
                    echo "<li>";
                    echo "<span>";
                      if (empty($item['Image'])) {
                      echo "<img src='Uploads/Profile/desktop.jpg' alt='No Iamge'/>";
                    }else {
                      echo "<img src='Uploads/Profile/" . $item['Image'] . "' alt=' Image Item' / >";
                    }
                    echo "</span>";
                        echo $item['Name'];
                        if ($item['Approve'] == 0) {
                          echo "<a href='items.php?do=Approve&itemid=" . $item['ID'] . "' class='btn btn-info pull-right'><i class='fa fa-check'></i></a>";
                        }
                        echo "<a href='items.php?do=Edit&itemid=" . $item['ID'] . "' class='btn btn-success pull-right'><i class='fa fa-edit'></i></a>";
                    echo "</li>";
                  }
                }else {
                  echo "<div class='custome-message'>There's No Products To Show</div>";
                }
               ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header">
            <i class="fa fa-comments"></i><span>Latest <mark><?php echo $numComments ;?></mark> comments</span>
            <span class="toggle-info pull-right">
              <i class="fa fa-plus"></i>
            </span>
          </div>
          <div class="card-body">
            <?php
            // select comment
            $stmt2 = $con->prepare("SELECT comments.*, users.Username AS Member ,users.UserID , users.Profile FROM comments
                                    INNER JOIN users ON users.UserID = comments.User_ID
                                      ORDER BY C_ID DESC LIMIT $numComments");
            $stmt2->execute();
            // Fetch The Data
            $comments = $stmt2->fetchAll();
            if (! empty($comments)) {
                foreach ($comments as $comment) {
                      echo "<div class='comment-box'>";
                      if (empty($comment['Profile'])) {
                        echo "<img src='Uploads/Profile/desktop.jpg' alt='No Profile'/>";
                        }else {
                        echo "<img src='Uploads/Profile/" . $comment['Profile'] . "' alt='Profile Image ' / >";
                        }
                      echo '<span class="member-n">';
                        echo  '<a href="members.php?do=Edit&userid=' . $comment['UserID'] .'">' . $comment['Member'] . '</a>';
                      echo'</span>';
                      echo '<p class="member-c">' . $comment['Comment'] . '</p>';
                      if ($comment['Status'] == 0) {
                        echo "<a href='comments.php?do=Approve&cid=" . $comment['C_ID'] . "' class='btn btn-info pull-right'><i class='fa fa-check'></i></a>";
                      }
                      echo "<a href='comments.php?do=Edit&cid=" . $comment['C_ID'] . "' class='btn btn-success pull-right'><i class='fa fa-edit'></i></a>";
                  echo "</div>";
                }
              }else {
                echo "<div class='custome-message'>There's No Comments To Show</div>";
              }
             ?>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php  # End Dashboard Page
  include $tpl . 'footer.php';
}else {
  header('location: index.php');
  exit();
}
 ob_end_flush();
 ?>
