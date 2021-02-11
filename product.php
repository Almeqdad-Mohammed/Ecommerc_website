<?php
ob_start();
session_start();
$pageTitle = 'Product';
include 'init.php';
  // Check If GEt Request Is Product And Get The Integer Value OF it
  $productid = isset($_GET['productid']) && is_numeric($_GET['productid'])? intval($_GET['productid']) : 0;
  // SELECT Items From Database Depended On This Id
  $stmt2 = $con->prepare("SELECT items.*,
                             users.Username FROM items
                            INNER JOIN users ON users.UserID = items.Member_ID
                                 WHERE ID = ?");
  $stmt2->execute(array($productid));
  $count = $stmt2->rowCount();

  if ($count > 0) {

    $products = $stmt2->fetch();


?>

    <h1 class="text-center"><?php echo  $products['Name']; ?></h1>
<div class="container">
    <div class="row">
      <div class="col-md-3">
        <img class="img-responsive img-thumbnail center-block "src="Layout/Images/one.jpg" alt="">
      </div>
      <div class="col-md-9  product-box">
        <h3><?php echo $products['Name']; ?></h3>
        <ul class="list-unstyled">
        <li><span>
          <i class="fas fa-audio-description"></i>
           Description </span>: <?php echo  $products['Description'] ?></li>
        <li>
          <span>
          <i class="fa fa-calendar fa-fw"></i>
            Added Date </span>: <?php echo  $products['Add_Date'] ?>
        </li>
        <li>
          <span>
            <i class="fa fa-money fa-fw"></i>
            Price </span>:$ <?php echo  $products['Price'] ?>
        </li>
        <li>
          <span>
            <i class="fa fa-map fa-fw"></i>
            Made In </span>: <?php echo  $products['Country_Made'] ?>
        </li>
        <li>
          <span>
            <i class="fa fa-user fa-fw"></i>
            Added By </span>: <?php echo  $products['Username'] ?>
        </li>
        <li class="tags-item">
          <span>
            <i class="fa fa-tags fa-fw"></i>
            Tags </span>:
             <?php
              $alltags = explode(',' , $products['Tags']);
            foreach ($alltags as $tags) {
              $tags = str_replace(' ', '', $tags);
              $lowertags = strtolower($tags);
              if(!empty($tags)) {
                echo "<a href='tags.php?name={$lowertags}'>" . $tags . "</a> ";
              }
            }

             ?>
        </li>
      </ul>
      </div>
    </div>
    <hr class="custom-hr">
    <div class="row">
      <?php if (isset($_SESSION['user'])) { ?>
        <!-- Start Add Comment -->
      <div class="col-md-offset-3">
        <div class="Add-comment">
          <h3>Add Your Comment</h3>
        <form class="" action="<?php echo $_SERVER['PHP_SELF'] . '?productid=' . $products['ID'] . '' ?>" method="post">
          <textarea name="comment" required rows="5" cols="40" ></textarea>
          <input type="submit" class="btn btn-primary btn-block" value="Add Comment">
        </form>
      </div>
      </div>
      <!-- End Add Comment -->
    <?php }  else{ echo "<div class='alert alert-info' ><a data-toggle='modal' data-target='#mymodal' href='Login.php'>Login</a>
                      Or <a data-toggle='modal' data-target='#mymodal' href='Login.php'>Register </a>To Add Your Comment</div>";
              }?>
            <?php
              //echo "<a class='nav-link' href='Login.php'>Sign In</a>";
              // echo "<div class='btn btn-default'  data-toggle='modal' data-target='#mymodal' style='color:#FFF;cursor:pointer'>Sign In | Sign Up</div>";

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
    </div>
    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
      $userid = $_SESSION['uid'];
      $prodid = $products['ID'];
      if (!empty($comment) &&  strlen($comment) >= 5) {
        $stmt2 = $con->prepare("INSERT INTO comments(Comment ,Status, Comment_date , Item_ID , User_ID)
                              VALUES(:zcomment, 0 , now() , :zprodid , :zuid)");
        $stmt2->execute(array(
          'zcomment' => $comment ,
          'zprodid'  => $prodid,
          'zuid'    =>  $userid ));
          if ($stmt2) {
            echo "<div class='alert alert-success'>Your Comment Has Been Added It's Need To Activate By Admin</div>";

          }

      }else {
        echo "<div class='alert alert-danger'>The Comment Can't Be Empty</div>";
      }
    } ?>
    <hr class="custom-hr">
    <?php
    // Select All Comment In Database
    $stmt2 = $con->prepare("SELECT comments.* , users.Username As Member FROM comments
                            INNER JOIN users ON users.UserID = comments.User_ID
                            WHERE Item_ID = ? AND Status = 1
                            ORDER BY C_ID DESC ");
    $stmt2->execute(array($products['ID']));
    $comments =  $stmt2->fetchAll();
     ?>
<?php
  foreach ($comments as $comment) {
    echo "<div class='row'>";
      echo "<div class='col-md-3'>" . $comment['Member'] . "</div>";
      echo "<div class='col-md-9'>" . $comment['Comment'] . "</div>";
    echo "</div>";
  }
 ?>
</div>

<?php
}else {
  echo "<div class='alert alert-danger'>There's No Such ID </div>";
}

 include $tpl . 'footer.php';
ob_end_flush();
?>
