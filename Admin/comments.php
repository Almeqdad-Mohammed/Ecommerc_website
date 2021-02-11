<?php
  // ==========================
  // = You Can Edit And  Update AndDELETE And Approve Comments Here
  //===========================
  ob_start();
  session_start();
  $pageTitle = "Comments";
  if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do'])? $_GET['do']: 'Manage';
    if ($do == 'Manage') { // welcome To Manage Comments...
      // Select all Comment Form Database
      $stmt2 = $con->prepare("SELECT comments.* , items.Name As Item_Name , users.Username As Username
        FROM comments INNER JOIN items ON items.ID = comments.Item_ID
        INNER JOIN users ON users.UserID = comments.User_ID ");
      // Execute The Statment
      $stmt2->execute();
      // fetch The Data
      $comments = $stmt2->fetchAll();
      if (! empty($comments)) {
      ?>
          <h1 class="text-center">Manage Comments</h1>
          <div class="container">
            <div class="table-responsive">
              <table class="main-table text-center table table-bordered">
                <tr>
                  <td>=> ID</td>
                  <td>Comment</td>
                  <td>Item Name</td>
                  <td>User Name</td>
                  <td>Added Date</td>
                  <td>control</td>
                </tr>
                <?php
                foreach ($comments as $comment) {
                      echo "<tr>";
                      echo "<td>" . $comment['C_ID'] . "</td>";
                      echo "<td>" . $comment['Comment'] . "</td>";
                      echo "<td>" . $comment['Item_Name'] . "</td>";
                      echo "<td>" . $comment['Username'] . "</td>";
                      echo "<td>" . $comment['Comment_Date'] . "</td>";
                          echo "<td>";
                            echo "<a href='comments.php?do=Edit&cid=" . $comment['C_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>";
                            echo "<a href='comments.php?do=Delete&cid=" . $comment['C_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i>";
                            if ($comment['Status'] == 0) {
                              echo "<a href='comments.php?do=Approve&cid=" . $comment['C_ID'] . "' class='btn btn-info'><i class='fa fa-check'></i>";
                            }
                          echo "</td>";

                  echo "</tr>";
                }
                 ?>
              </table>
            </div>
          </div>
      <?php }else {
          echo "<div class='container'>";
            echo "<div class='custome-message'>There's No Comments To Show</div>";
          echo "</div>";
            }?>
<?php }elseif ($do == 'Edit') { // Welcome To Comment Page
  // check if Get  Request Itemid And Get  The Integer Value
  $cid = isset($_GET['cid']) && is_numeric($_GET['cid'])? intval($_GET['cid']): 0;
  //  select All Data form Database Depended On This Id
  $stmt2 = $con->prepare("SELECT * FROM comments WHERE C_ID = ? LIMIT 1");
  // Execute THe Statment
  $stmt2->execute(array($cid));
  // Fetch THe On THis Data
  $comments = $stmt2->fetch();
  $count = $stmt2->rowCount();
  if ($count > 0 ) { ?>
    <h1 class="text-center">Edit Comment</h1>
    <div class="container">
      <form class="form-horizotal" action="?do=Update" method="POST">
        <input type="hidden" name="cid" value="<?php echo $comments['C_ID']; ?>">
        <!-- Start The Comment Field -->
        <div class="form-group">
          <label class="col-sm-2 control-label">The Comment</label>
          <div class="col-sm-10 col-md-8">
            <textarea name="comment" rows="8" cols="80" class="form-control" value=""
            placeholder="Please Write Your  Comment Here"><?php echo $comments['Comment']; ?></textarea>
          </div>
        </div>
        <!-- End The Comment Field -->
        <!-- Start The Submit Field -->
        <div class="form-group">
          <div class="col-sm-offset-10 col-md-8">
            <input type="submit"  value="Update Comment" class="btn btn-primary btn-sm">
          </div>
        </div>
        <!-- End The Submit Field -->
      </form>
    </div>
<?php }else {
    echo "<div class='container'";
      $thMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";
      redirectHome($thMsg);
    echo "</div>";
  }
    }elseif ($do == 'Update') { // Welcome To Update Comments Page
      echo "<h1 class='text-center'>Update Comment</h1>";
      echo "<div class='container'>";
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get THe Data From THe Form
        $cid = $_POST['cid'];
        $comment = $_POST['comment'];

        $formErrors = array();
        if (strlen($comment) < 10) {
          $formErrors[] = "THE Comment Can't Be Less THan <strong>10 Charactar</strong>";
        }
        // Looping THe Array Error If Not Empty
        foreach ($formErrors as $error) {
          $thMsg = "<div class='alert alert-danger'>" . $error . "</div>";
          redirectHome($thMsg, 'back');
        }
        if (empty($formErrors)) {
          // Here You Can Update Your Data Comment
          $stmt2 = $con->prepare("UPDATE comments SET Comment = ? WHERE C_ID = ?");
          $stmt2->execute(array($comment , $cid));
          // Echo THe Success Message
          $thMsg = "<div class='alert alert-success'>" . $stmt2->rowCount() . " Comment Updated</div>";
          redirectHome($thMsg , 'back');
        }
      }else {
        $thMsg = "<div class='alert alert-danger'> Sorry You Can't Browse This PAge Directly</div>";
        redirectHome($thMsg);
      }
      echo "</div>";
    }elseif ($do == 'Delete') { // Welcome To Delete Page
      echo "<h1 class='text-center'>Delete Comment </h1>";
      echo "<div class='container'>";
      // Ceheck If Get Request cid And Get The Integer Value Of It
      $cid = isset($_GET['cid']) && is_numeric($_GET['cid']) ? intval($_GET['cid']): 0;
      // check The Comment If Exist Or Not
      $check = CheckItem("C_ID" , "comments" , $cid);
      if ($check > 0 ) {
        // Now You Can Delete The Comment
        $stmt2 = $con->prepare("DELETE FROM comments WHERE C_ID = :zid");
        $stmt2->bindParam(":zid" , $cid);
        $stmt2->execute();

        // Echo The Success Message
        $thMsg = "<div class='alert alert-success'>" . $stmt2->rowCount() . " Comment Deleted </div>";
        redirectHome($thMsg ,'back');
      }else {
        $thMsg = "<div class='alert alert-danger'> Sorry This ID Is Not Exist </div>";
        redirectHome($thMsg, 'back');
      }
      echo "</div>";
    }elseif ($do == 'Approve') { // Welcome Dear To Approve Your Item Here
      echo "<h1 class='text-center'>Approve Comment</h1>";
      echo "<div class='container'>";
      // check Request cid And Get The Integer Value Of it
      $cid = isset($_GET['cid']) && is_numeric($_GET['cid'])? intval($_GET['cid']): 0;
      // Check Also The Item If Exist Or Not
      $check = CheckItem("C_ID" , "comments" , $cid);
      if ($check > 0) {
        // Here You Can Approve The item
        $stmt2 = $con->prepare("UPDATE comments SET Status = 1 WHERE C_ID = ?");
        $stmt2->execute(array($cid));
        // Echo The Success Message
        $thMsg = "<div class='alert alert-success'>" . $stmt2->rowCount() . " Comment Update</div>";
        redirectHome($thMsg , 'back');
      }else {
        $thMsg = "<div class='alert alert-success'>Sorry There's No Such ID</div>";
        redirectHome($thMsg);
      }
      echo "</div>";
    }
  }else {
    header('location: Index.php');
    exit();
  }?>

  <?php
  include $tpl . 'footer.php';
  ob_end_flush();
 ?>
