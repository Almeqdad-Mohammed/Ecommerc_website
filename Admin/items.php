<?php

  //==========================
  //= Manage Products Page       =
  //==========================
  ob_start();
  session_start();
  $pageTitle = 'Products';
  if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    if ($do == 'Manage') { // Managen IRems Page...
      // Select All Productss On table Productss;
      $stmt = $con->prepare("SELECT items.*, categories.Name AS Cat_Name , users.Username
                               FROM items
                              INNER JOIN categories ON categories.ID = items.Cat_ID
                              INNER JOIN users ON users.UserID = items.Member_ID
                              ORDER BY ID DESC
                              ");
      // execute the statment
      $stmt->execute();
      // fetch All Data
      $items = $stmt->fetchAll();
      if (! empty($items)) {
      ?>
          <h1 class="text-center">Manage Products</h1>
          <div class="container " style="width:1100px">
              <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                  <tr>
                    <td> => ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Adding Date</td>
                    <td>Category</td>
                    <td>Username</td>
                    <td>Made In</td>
                    <td>Control</td>
                  </tr>
                  <tr>
                    <?php
                    foreach ($items as $item) {
                      echo "<tr>";
                          echo "<td>" . $item['ID'] . "</td>";
                          echo "<td>" . $item['Name'] . "</td>";
                          echo "<td style='max-width:200px'>" . $item['Description'] . "</td>";
                          echo "<td>" . $item['Price'] . "</td>";
                          echo "<td>" . $item['Add_Date'] . "</td>";
                          echo "<td>" . $item['Cat_Name'] . "</td>";
                          echo "<td>" . $item['Username'] . "</td>";
                          echo "<td>" . $item['Country_Made'] . "</td>";
                          echo "<td>";
                              echo "<a href='items.php?do=Edit&itemid=" . $item['ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i>";
                              echo "<a href='items.php?do=Show&cid=" . $item['ID'] . "' class='btn btn-primary'><i class='fa fa-comment'></i>";
                              echo "<a href='items.php?do=Delete&itemid=" . $item['ID'] . "' class='btn btn-danger confirm' ><i class='fa fa-close'></i>";
                              if ($item['Approve'] == 0) {
                                echo "<a href='items.php?do=Approve&itemid=" . $item['ID'] ."' class='btn btn-info '><i class='fa fa-check'></i>";
                              }


                               echo "</td>";
                      echo "</tr>";
                    }
                     ?>
                  </tr>
                </table>
              </div>
              <a href="items.php?do=Add" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Product</a>
          </div>
      <?php }else {
          echo "<div class='container'>";
            echo "<div class='custome-message'>There's No Products To Show</div>";
            echo '<a href="items.php?do=Add" class="btn btn-primary add-button">
            <i class="fa fa-plus fa-fx"></i> Add Item</a>';
          echo "</div>";
            }?>
<?php }elseif ($do == 'Add') { // Add New Items ?>
        <h1 class="text-center">Add New Products</h1>
        <div class="container">
          <form class="form-horizotal" action="?do=Insert" method="POST">
            <!--Start Name Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Name</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="name" class="form-control" autocomplete="off"
                placeholder="Name Of The Product" required="required">
              </div>
            </div>
            <!-- End Name Field -->
            <!--Start Description Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Description</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="description" class="form-control" autocomplete="off"
                placeholder="Enter The Description Of This Product" required="required">
              </div>
            </div>
            <!-- End Name Field -->
            <!--Start Price Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Price</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="price" class="form-control" autocomplete="off"
                placeholder="Enter Price Of This  Product And Add $ marker " required="required">
              </div>
            </div>
            <!-- End Price Field -->
            <!--Start Country Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Country Made</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="country" class="form-control" autocomplete="off"
                placeholder="Country Made Of This Product" required="required">
              </div>
            </div>
            <!-- End Country Field -->
            <!--Start Status Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Status</label>
                <div class="col-sm-10 col-md-6">
                  <select class="form-control" name="status">
                    <option value="0">...</option>
                    <option value="1">New</option>
                    <option value="2">Like New</option>
                    <option value="3">Used</option>
                    <option value="4">Very Old</option>
                  </select>
                </div>
              </div>
            <!-- End Status Field -->
            <!--Start Members Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Member</label>
                <div class="col-sm-10 col-md-6">
                  <select class="form-control" name="member">
                    <option value="0">...</option>
                    <?php
                    $AllMember = getALlFrom("*" , "users", "" , "", "userID");
                    foreach ($AllMember as $mem) {
                      echo "<option value='" . $mem['UserID'] . "'>" . $mem['Username'] . "</option>";
                    }
                     ?>
                  </select>
                </div>
              </div>
            <!-- End Members Field -->
            <!--Start categories Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Category</label>
                <div class="col-sm-10 col-md-6">
                  <select class="form-control" name="category">
                    <option value="0">...</option>
                    <?php
                    $cats_type  =  getALlFrom("*" , "categories", "WHERE Parent = 0" , "", "ID");
                    foreach ($cats_type as $cat) {
                      echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                      $childcat  =  getALlFrom("*" , "categories", "where Parent = {$cat['ID']}" , "", "ID");
                      foreach ($childcat as $child) {
                        echo "<option value='" . $child['ID'] . "'> ->  " . $child['Name'] . "</option>";
                      }
                    }
                     ?>
                  </select>
                </div>
              </div>
            <!-- End categories Field -->
            <!--Start Rating Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Rating</label>
                <div class="col-sm-10 col-md-6">
                  <select class="form-control" name="rating">
                    <option value="0">...</option>
                    <option value="1">*</option>
                    <option value="2">**</option>
                    <option value="3">***</option>
                    <option value="4">****</option>
                    <option value="5">*****</option>
                  </select>
                </div>
              </div>
            <!-- End Rating Field -->
            <!--Start Tags Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Tags</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="tags" class="form-control" autocomplete="off"
                placeholder="Separete Tage With Comma (,)" required="required">
              </div>
            </div>
            <!-- End Tags Field -->
            <div class="form-group">
              <div class="col-sm-offset-2 col-md-10" >
                <input type="submit" class="btn btn-primary" value="Add Product">
              </div>
            </div>
            <!-- End Status Field -->
          </form>
        </div>
<?php }elseif ($do == 'Insert') { // Start Inseert Page
  echo "<h1 class='text-center'>Insert Product</h1>";
  echo "<div class='container'>";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      // Get The Data  from The Form
      $name     = $_POST['name'];
      $desc     = $_POST['description'];
      $price    = $_POST['price'];
      $country  = $_POST['country'];
      $status   = $_POST['status'];
      $rating   = $_POST['rating'];
      $member   = $_POST['member'];
      $cat      = $_POST['category'];
      $tags      = $_POST['tags'];
      # Creat The Array Of The Error
    $formErrors = array();
    if (empty($name)) {
        $formErrors[] = "Name OF Product Can't Be <strong>Empty</strong>";
    }
    if (empty($desc)) {
      $formErrors[] = "Description Can't Be <srtong> Empty</strong>";
    }
    if (empty($price)) {
      $formErrors[] = "Price Can't Be Empty";
    }
    if (empty($country)) {
      $formErrors[] = "Country Name Can't Be Empty";
    }
    if ($status == 0) {
      $formErrors[] = "YOu Must Choose The Status Of Your Product";
    }
    if ($rating == 0) {
      $formErrors[] = "You Must Rate This Product At least <strong>One Start *</strong>";
    }
    if ($member == 0) {
      $formErrors[] = "You Must Choose The <strong> Member </strong>";
    }
    if ($cat == 0) {
      $formErrors[] = "You Must Choose the <strong>One Category</strong>";
    }
    # Looping The Error for each
    foreach ($formErrors as $error) {
       $thMsg = "<div class='alert alert-danger'>" . $error . "</div>";
       redirectHome($thMsg, 'back');
    }
    // Check If $formErrors If Empty
    if (empty($formErrors)) {
      // Check If Item Is Exist Or not
      $check = CheckItem("Name" , "items" , $name);
      if ($check > 0) {
        $thMsg = "Sorry This Product Is Exist ";
        redirectHome($thMsg,'back');
      }else {
        // Insert The Data here To Database
        $stmt = $con->prepare("INSERT INTO items (Name, Description, Price, Add_Date, Country_Made, Status, Rating, Cat_ID, Member_ID, Tags)
         VALUES(:zname,:zdesc,:zprice, now(),:zcountry,:zstat,:zrat,:zcat,:zmem, :ztags)");
        // Execute the Statment
        /* Here I Used Different Way  Used Normal Array()
         Not Associative array instead OF below That Given The The Errors
        can't add or update a child row failed constraint
        I Used This $stmt->execute(array($name, $desc, $price,......elc));
        After I Discovered The problem I Changed The Query Way With this
        Associative Array $arrayName = array('' => , );

        */
        $stmt->execute(array(
          'zname' => $name,
          'zdesc' => $desc,
          'zprice' => $price,
          'zcountry' => $country,
          'zstat' => $status,
          'zrat' => $rating,
          'zcat' => $cat,
          'zmem' => $member,
          'ztags' => $tags
        ));
        # Echo The Success Message
        $thMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Product Inserted </div>";
        redirectHome($thMsg , 'back');
      }

    }// end if of error
    }else {
      $thMsg = "<div class='alert alert-danger'>sorry You Can't Browse The Page Directly</div>";
      redirectHome($thMsg);
    }
  echo "</div>";

    }elseif ($do == 'Edit') { // Welcome to Edit Page
      // check if Get rquest Is itemid And Get The Integer Value Of it
      $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
      // select All Data Items Depended On This ID
      $stmt2 = $con->prepare("SELECT * FROM items WHERE ID = ? LIMIT 1");
      // Execute The Statment
      $stmt2->execute(array($itemid));
      // Fetch The Data On
      $item = $stmt2->fetch();
      $count = $stmt2->rowCount();
      if ($count > 0) { ?>
        <h1 class="text-center">Edit Product</h1>
        <div class="container">
          <form class="form-horizotal" action="?do=Update" method="POSt">
            <input type="hidden" name="itemid" value="<?php echo $item['ID'] ?>">
            <!--start Name field-->
              <div class="form-group">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10 col-md-8">
                  <input type="text" name="name" class="form-control" value="<?php echo $item['Name'] ?>"
                  autocomplete="off"  required="required" placeholder="">
                </div>
              </div>
            <!--End Name field-->
            <!--start Description field-->
              <div class="form-group">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10 col-md-8">
                  <input type="text" name="description" class="form-control" value="<?php echo $item['Description'] ?>"
                  autocomplete="off"  required="required" placeholder="">
                </div>
              </div>
            <!--End Description field-->
            <!--start Price field-->
              <div class="form-group">
                <label class="col-sm-2 control-label">Price</label>
                <div class="col-sm-10 col-md-8">
                  <input type="text" name="price" class="form-control" value="<?php echo $item['Price'] ?>"
                  autocomplete="off"  required="required" placeholder="">
                </div>
              </div>
            <!--End Price field-->
            <!--start Country_Made field-->
              <div class="form-group">
                <label class="col-sm-2 control-label">Country Made</label>
                <div class="col-sm-10 col-md-8">
                  <input type="text" name="country" class="form-control" value="<?php echo $item['Country_Made'] ?>"
                  autocomplete="off"  required="required" placeholder="">
                </div>
              </div>
            <!--End Price field-->
            <!--Start Status Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Status</label>
                <div class="col-sm-10 col-md-8">
                  <select class="form-control" name="status">
                    <option value="1" <?php if ($item['Status'] == 1) {echo "selected";}?> >New</option>
                    <option value="2" <?php if ($item['Status'] == 2) {echo "selected";}?> >Like New</option>
                    <option value="3" <?php if ($item['Status'] == 3) {echo "selected";}?> >Used</option>
                    <option value="4" <?php if ($item['Status'] == 4) {echo "selected";}?> >Very Old</option>
                  </select>
                </div>
              </div>
            <!-- End Status Field -->
            <!--Start Members Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Member</label>
                <div class="col-sm-10 col-md-8">
                  <select class="form-control" name="member">
                    <?php
                    $stmtment = $con->prepare("SELECT * FROM users ");
                    $stmtment->execute();
                    $members = $stmtment->fetchAll();
                    foreach ($members as $mem) {
                      echo "<option value='" . $mem['UserID'] . "'";
                      if ($item['Member_ID'] ==   $mem['UserID']) { echo "selected";}
                      echo ">" . $mem['Username'] . "</option>";
                    }
                     ?>
                  </select>
                </div>
              </div>
            <!-- End Members Field -->
            <!--Start Category Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Category</label>
                <div class="col-sm-10 col-md-8">
                  <select class="form-control" name="category">
                    <?php
                    $stmtment = $con->prepare("SELECT * FROM categories ");
                    $stmtment->execute();
                    $cats = $stmtment->fetchAll();
                    foreach ($cats as $cat) {
                      echo "<option value='" . $cat['ID'] . "'";
                      if ($item['Cat_ID'] ==   $cat['ID']) { echo "selected";}
                      echo ">" . $cat['Name'] . "</option>";
                    }
                     ?>
                  </select>
                </div>
              </div>
            <!-- End Category Field -->
            <!--Start Status Field-->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Status</label>
                <div class="col-sm-10 col-md-8">
                  <select class="form-control" name="rating">
                    <option value="1" <?php if ($item['Status'] == 1) {echo "selected";}?> >*</option>
                    <option value="2" <?php if ($item['Status'] == 2) {echo "selected";}?> >**</option>
                    <option value="3" <?php if ($item['Status'] == 3) {echo "selected";}?> >***</option>
                    <option value="4" <?php if ($item['Status'] == 4) {echo "selected";}?> >****</option>
                    <option value="5" <?php if ($item['Status'] == 5) {echo "selected";}?> >*****</option>
                  </select>
                </div>
              </div>
            <!-- End Status Field -->
            <div class="form-group">
              <label class="col-sm-2 control-label" >Tags</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="tags" class="form-control" autocomplete="off"
                placeholder="Separete Tage With Comma (,)" value="<?php echo $item['Tags'] ?>">
              </div>
            </div>
            <!-- End Tags Field -->
            <div class="form-group">
              <div class="col-sm-10 colmd-8">
                <input type="submit"  value="Update Product" class="btn btn-primary">
              </div>
            </div>
          </form>
        </div>
      <?php }else {
        // code...
      }
    }elseif ($do == 'Update') { // Welcome To Update page
      echo "<h1 class='text-center'>Edit Product</h1>";
      echo "<div class='container'>";
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $itemid   = $_POST['itemid'];
        $name     = $_POST['name'];
        $desc     = $_POST['description'];
        $price    = $_POST['price'];
        $country  = $_POST['country'];
        $status   = $_POST['status'];
        $rating   = $_POST['rating'];
        $member   = $_POST['member'];
        $cat      = $_POST['category'];
        $tags      = $_POST['tags'];

        $formErrors = array();
        if (empty($name)) {
            $formErrors[] = "Name OF Product Can't Be <strong>Empty</strong>";
        }
        if (empty($desc)) {
          $formErrors[] = "Description Can't Be <srtong> Empty</strong>";
        }
        if (empty($price)) {
          $formErrors[] = "Price Can't Be Empty";
        }
        if (empty($country)) {
          $formErrors[] = "Country Name Can't Be Empty";
        }
        if ($status == 0) {
          $formErrors[] = "YOu Must Choose The Status Of Your Product";
        }
        if ($rating == 0) {
          $formErrors[] = "You Must Rate This Product At least <strong>One Start *</strong>";
        }
        if ($member == 0) {
          $formErrors[] = "You Must Choose <strong>One Member </strong>";
        }
        if ($cat == 0) {
          $formErrors[] = "You Must choose <strong>One Category</strong>";
        }
        # Looping The Error for each
        foreach ($formErrors as $key => $error) {
          $thMsg =  "<div class='alert alert-danger'>" . $error . "</div>";
          redirectHome($thMsg , 'back' , 5);
        }
        if (empty($formErrors)) {
          // here You Can Update The Item Column
          $stmt = $con->prepare("UPDATE items SET Name = ?, Description = ?, Price = ?,
          Country_Made = ?, Status = ?,Rating = ?, Cat_ID = ?, Member_ID = ? , Tags = ? WHERE ID = ?");
          $stmt->execute(array($name, $desc, $price, $country, $status, $rating, $cat, $member , $tags ,$itemid));

          // Echo  The Success Message
          $thMsg  = "<div class='alert alert-success'>" . $stmt->rowCount() . " Product Updated</div>";
          redirectHome($thMsg,'back');
        }
      }else {
        $thMsg = "<div class='alert alert-danger'>Sorry You Can't Browse This Page Directly</div>";
        redirectHome($thMsg);
      }
      echo "</div>";
    }elseif ($do == 'Delete') { // Welcome To Delete item Page
      echo "<h1 class='text-center'>Delete Product</h1>";
      echo "<div class='container'>";
      // Check If Get Request Is itemid And Get The Integer Value Of It
      $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']): 0;
      // Check If Item IS Exist Or Not
      $check = CheckItem("ID" , "items" , $itemid);
      if ($check > 0 ) {
        $stmt = $con->prepare("DELETE FROM items WHERE ID = :ZID");
        $stmt->bindParam(":ZID" , $itemid);
        $stmt->execute();
        // Echo The Success Message
        $thMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Product Deleted</div>";
        redirectHome($thMsg , 'back');
      }else {
        $thMsg = "<div class='alert alert-danger'>there Is No Such ID Or This Id Is Not Exist</div>";
        redirectHome($thMsg, 'back' , 11);
      }
      echo "</div>";
    }elseif ($do == 'Approve') { // Welcome To Approve Item Page
      echo "<h1 class='text-center'>Approve Product</h1>";
      echo "<div class='container'>";
      $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']): 0;
      // Select All Data Depended On This Id
      $check = CheckItem("ID" , "items" , $itemid);
      if ($check > 0) {
        // Approve  The Item Now
        $stmtment = $con->prepare("UPDATE items SET Approve = 1 WHERE ID = ?");
        // Execute The Statment
        $stmtment->execute(array($itemid));
        // Echo  The Success Message
        $thMsg = "<div class='alert alert-success'>" . $stmtment->rowCount() . " Product Approved</div>";
        redirectHome($thMsg , 'back');
      }else {
        $thMsg = "<div class='alert alert-danger'>Sorry There's No such ID Or This ID Not Exust</div>";
        redirectHome($thMsg);
      }
      echo "</div>";
    }elseif ($do == 'Show') { // Welcome To Show Comment Here

      // check If Request citem And Get The Inter Value Of It
      $cid = isset($_GET['cid']) && is_numeric($_GET['cid'])? intval($_GET['cid']) :0;
      // Check IF Item Is Exist Or Not
      $check = CheckItem("ID" , "items" , $cid);
      if ($check > 0) {
        // Here You can Select The Item That Has Many Comment Depended Of This Id
        $stmt = $con->prepare("SELECT comments.*, users.Username   FROM comments
                                INNER JOIN users ON users.UserID = comments.User_ID WHERE Item_ID = ?");
        $stmt->execute(array($cid));
        $comments = $stmt->fetchAll();

        if (!empty($comments)) {
          // here Will Appear All Comments Of This Item
          ?>
          <h1 class="text-center">Comment Of This Product</h1>
          <div class="container">
            <div class="table-responsive">
              <table class="main-table table table-bordered">
                <tr>
                  <td>Comment</td>
                  <td>User Name</td>
                  <td>Added Date</td>
                  <td>Control</td>
                </tr>
                <?php
                foreach ($comments as $comment) {
                  echo "<tr>";
                      echo "<td style='max-width:400px'>" . $comment['Comment'] . "</td>";
                      echo "<td>" . $comment['Username'] . "</td>";
                      echo "<td>" . $comment['Comment_Date'] . "</td>";
                      echo "<td>";
                          echo "<a href='comments.php?do=Delete&cid=" . $comment['C_ID'] . "' class='btn btn-danger confirm pull-right'><i class='fa fa-close'></i></a>";
                          echo "<a href='comments.php?do=Edit&cid=" . $comment['C_ID'] . "' class='btn btn-success pull-right'><i class='fa fa-edit'></i></a>";
                          if ($comment['Status'] == 0) {
                            echo "<a href='comments.php?do=Approve&cid=" . $comment['C_ID'] . "' class='btn btn-info pull-right'><i class='fa fa-check'></i></a>";
                          }
                      echo "</td>";
                  echo "</tr>";
                }
                 ?>
        <?php }else {
          echo "<div class='container'>";
          echo "<h1>..</h1>";
          $thMsg = "<div class='alert alert-info'> This Products Has Not Any Comment...</div>";
          redirectHome($thMsg, 'back' , 5);
        }?>
            </table>
          </div>
          <!-- <div class="row side-comment">
            <div class="col-sm-3 ">
              <i class="fa fa-users"></i>
            </div>
            <div class="col-sm-3">
              <p class="comment">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
          </div>-->
        </div>
<?php }else {
          echo "<div class='container text-center'>";
          echo "<h1 class='text-center'>";
        $thMsg = "<div class='alert alert-danger'> Sorry You Can;t Browse This Page Directly</div>";
        redirectHome($thMsg);
        echo "</h1>";
              echo "</div>";
      }
    }?>

    <?php
     include $tpl . 'footer.php';
  }else {
    header('Location:Index.php');
    exit();
  }
ob_end_flush()

 ?>
