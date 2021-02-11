<?php
  /*
  ==============================
  == Manage Categories Page    =
  ==============================
  */
  ob_start(); // Output Buufering Start

  session_start();
  $pageTitle = 'Categories';
    if (isset($_SESSION['Username'])) {
      include 'init.php';
      $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
      if ($do == "Manage") {
        $sort = 'ASC';
        $sort_array = array('ASC' , 'DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'] , $sort_array))  {
          $sort = $_GET['sort'];
        }
        $stmt = $con->prepare("SELECT * FROM categories WHERE Parent = 0 ORDER BY Ordering $sort ");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        if (! empty($cats)) {
        ?>
            <h1 class="text-center">Manage Categories</h1>
            <div class="container categories">
              <div class="card">
                <div class="card-header">
                  <i class="fa fa-edit "></i> Manage Categories
                  <div class="option pull-right">
                     Ordering <i class="fa fa-sort"></i> : [
                    <a class="<?php if ($sort == 'ASC') { echo 'active'; } ?>" href="?sort=ASC">Asc</a> |
                    <a class="<?php if ($sort == 'DESC') { echo 'active'; } ?>" href="?sort=DESC">Desc</a> ]
                     View  <i class="fa fa-eye"></i>: [
                    <span class="active" data-view="full">Full</span> |
                    <span data-view="Classic">Classic</span> ]
                  </div>
                </div>
                <div class="card-body">
                  <?php
                  foreach ($cats as $cat) {
                    echo "<div class='cat'>";
                        echo "<div class='hidden-buttons'>";
                        echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-success'><i class='fa fa-edit'></i></a>";
                          echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='btn btn-xs btn-danger confirm'><i class='fa fa-close'></i></a>";
                        echo "</div>";
                        echo "<h3>" .  $cat['Name'] . "</h3>";
                        echo "<div class='full-view'>";
                            echo "<p> " . $cat['Description'] . " </p>";
                            if ($cat['Visibility'] == 1 ) { echo "<span class='visibility'><i class='fa fa-eye'></i> Hidden</span>";}
                            if ($cat['Allow_Comment'] == 1 ) { echo "<span class='commenting'><i class='fa fa-close'></i> Comment Disabled</span>";}
                            if ($cat['Allow_Ads'] == 1 ) { echo "<span class='advertises'><i class='fa fa-close'></i> Ads Disabled</span>";}
                        echo "</div>";
                        // get The Sub Categories Form Database
                        $Childcat = getALlFrom("*" , "categories", " where  Parent = {$cat['ID']}", "" , "ID");

                          if (! empty($Childcat)) {
                            echo "<h4 class='child-head'> Child Categories</h4>";
                              echo "<ul class='list-unstyled child-cats'>";
                                foreach ($Childcat as $c) {
                                  echo "<li class='child-link'>
                                  <a href='categories.php?do=Edit&catid=" . $c['ID'] . "'>" . $c['Name'] . "</a>
                                  <a href='categories.php?do=Delete&catid=" . $c['ID'] . "' class='confirm Show-delete'>Delete</a>
                                  </li>";
                                  }
                                echo "</ul>";
                              }
                    echo "</div>";
                    echo "<hr>";

                  }
               ?>
            </div>
          </div>
          <a href="categories.php?do=Add" class="btn btn-primary add-button"><i class="fa fa-plus fa-fx"></i> Add Category</a>
        </div>
      <?php }else {
          echo "<div class='container'>";
            echo "<div class='custome-message'>There's No Categories To Show</div>";
            echo '<a href="categories.php?do=Add" class="btn btn-primary add-button">
            <i class="fa fa-plus fa-fx"></i> Add Category</a>';
          echo "</div>";
            }?>
<?php }elseif ($do == "Add") { // Start Add New Categories ?>
        <h1 class="text-center">Add New Category</h1>
        <div class="container">
          <form class="form-horizontal Form-center" action="?do=Insert" method="POST">
            <div class="form-group">
              <!--Start Name --->
              <label class="col-sm-3 control-label">Category Name</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="name" class="form-control" autocomplete="off"
                required = "required" placeholder="Name Of The  Category">
              </div>
              <!--End Name --->
            </div>
            <div class="form-group">
              <!--Start Description --->
              <label class="col-sm-2 control-label"> Description</label>
              <div class="col-sm-10 col-md-6">
                <input type="text" name="description" class="form-control" autocomplete="off"
                required = "required" placeholder=" Type Description  Of Category">
              </div>
              <!--End Description --->
            </div>
            <div class="form-group">
              <!--Start Ordering --->
              <label class="col-sm-2 control-label">Ordering</label>
              <div class="col-sm-10 col-md-6">
                <input type="number" name="ordering" class="form-control" autocomplete="off"
                 placeholder="Number To Sort The Categories">
              </div>
              <!--End Ordering --->
            </div>
            <!--Start Category Type --->
            <div class="form-group">
              <label class="col-sm-2 control-label">Choose Parent</label>
              <div class="col-sm-10 col-md-6">
                <select  class="form-control"  name="parent">
                  <option value="0">None...</option>
                  <?php
                    $ALLcat = getALlFrom("*" , "categories", " where  Parent = 0", "" , "ID");
                    foreach ($ALLcat as  $ty) {
                      echo "<option value='" . $ty['ID'] . "'>" . $ty['Name'] . "</option>";
                    }
                   ?>
                </select>
              </div>
            </div>
            <!--End Category Type --->
            <!--Start Visibility --->
            <div class="form-group">
              <label class="col-sm-2 control-label">Visibility</label>
              <div class="col-sm-10 col-md-6">
                <div >
                  <input id="vis-yes" type="radio" name="visibility" value="0" checked>
                  <label for="vis-yes">Yes</label>
                </div>
                <div >
                  <input id="vis-no" type="radio" name="visibility" value="1" >
                  <label for="vis-no">No</label>
                </div>
              </div>
              <!--End Visibility --->
            </div>
            <div class="form-group">
              <!--Start Comments --->
              <label class="col-sm-2 control-label">Allow Commenting</label>
              <div class="col-sm-10 col-md-6">
                <div >
                  <input id="com-yes" type="radio" name="commenting" value="0" checked>
                  <label for="com-yes">Yes</label>
                </div>
                <div >
                  <input id="com-no" type="radio" name="commenting" value="1" >
                  <label for="com-no">No</label>
                </div>
              </div>
              <!--End Comments --->
            </div>
            <div class="form-group">
              <!--Start Advertising --->
              <label class="col-sm-2 control-label">Allow Advertising</label>
              <div class="col-sm-10 col-md-6">
                <div >
                  <input id="ads-yes" type="radio" name="ads" value="0" checked>
                  <label for="ads-yes">Yes</label>
                </div>
                <div >
                  <input id="ads-no" type="radio" name="ads" value="1" >
                  <label for="ads-no">No</label>
                </div>
              </div>
              <!--End Advertising --->
            </div>
            <!--Start submit --->
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <input type="submit"  class="btn btn-primary " value="Add Category"
                 >
              </div>
            </div>
            <!--End submit --->
          </form>
        </div>

<?php }elseif ($do == "Insert") {  // Insert Page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          echo "<h1 class='text-center'>Insert Category</h1>";
          echo "<div class='container'>";
          $name = $_POST['name'];
          $desc = $_POST['description'];
          $parent = $_POST['parent'];
          $order = $_POST['ordering'];
          $visible = $_POST['visibility'];
          $comment = $_POST['commenting'];
          $ads = $_POST['ads'];

          $formErrors = array();
          if (strlen($name) < 4) {
            $formErrors[] = "Name Of Category Cam't Be Less Than 4 Charactar";
          }
          if (strlen($desc) < 10 ) {
            $formErrors[] = " The Description Can't Be Less Than 10 charactar ";
          }
          foreach ($formErrors as $error) {
            $thMsg =  "<div class='alert alert-danger'>" . $error . "</div>";
            redirectHome($thMsg, 'back');
          }
          if (empty($formErrors)) { //  Her I Can Use  ! Empty Name Instead of $formErrors
            // Check Items If Exist
            $check = CheckItem("Name" , "categories" , $name);
            if ($check > 0) {
              $thMsg = "<div class='alert alert-danger'>sorry This Category  Name  Is Exist </div>";
              redirectHome($thMsg,'back');
            } else {
              // insert The Data To database
              $stmt = $con->prepare("INSERT INTO categories (Name , Description , Parent , Ordering ,
                Visibility , Allow_comment, Allow_ads)
                VALUES(?,?,?,?,?,?,?)");
                $stmt->execute(array($name,$desc, $parent , $order, $visible ,$comment, $ads));

                // Echo Success Message
                $thMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Category Inserted</div>";
                redirectHome($thMsg , 'back');
            }
          } // End If Of Empty Error
          echo "</div>";
      } else {
        $thMsg = "<div class='alert alert-danger'>Sorry You can't Browse This Page Directly";
        redirectHome($thMsg);
      }
      }elseif ($do == "Edit") { // Edit Categories Page
        # Check if get Request catid Is is_numeric and Get The Integer Value
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;
        # Select All Data Depend On This Cat Id
        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? ");
        # Execut  The Statment
        $stmt->execute(array($catid));
        # Fetch The data
        $cats = $stmt->fetch();
        # Row Count
        $count = $stmt->rowCount();
        # Check  If Count > 0
        if ($count > 0) { ?>
          <h1 class="text-center">Edit Categories</h1>
          <div class="container">
            <form class="form-horizontal" action="?do=Update" method="POST">
              <input type="hidden" name="catid" value="<?php echo $cats['ID'] ?>">
              <!--Start Name Field -->
              <div class="form-group">
                <label class="col-sm-2 control-label">Category Name</label>
                <div class="col-sm-10 col-md-6">
                  <input type="text" name="name" class="form-control"
                   required = "required" value="<?php echo $cats['Name'] ?>">
                </div>
              </div>
              <!--End Name Field -->
              <!--Start Description Field -->
              <div class="form-group">
                <label class="col-sm-2 control-label">Description</label>
                <div class="col-sm-10 col-md-6">
                  <input type="text" name="description" class="form-control"
                   required = "required" value="<?php echo $cats['Description'] ?>">
                </div>
              </div>
              <!--End Description Field -->
              <!--Start Ordering Field -->
              <div class="form-group">
                <label class="col-sm-2 control-label">Ordering</label>
                <div class="col-sm-10 col-md-6">
                  <input type="number" name="ordering" class="form-control"
                  autocomplete="off" value="<?php echo $cats['Ordering'] ?>">
                </div>
              </div>
              <!-- End Ordering Feild -->
              <!--Start Category Type --->
              <div class="form-group">
                <label class="col-sm-2 control-label">Choose Parent</label>
                <div class="col-sm-10 col-md-6">
                  <select  class="form-control"  name="parent">
                    <option value="0">None...</option>
                    <?php
                      $ALLcat = getALlFrom("*" , "categories", " where  Parent = 0", "" , "ID");
                      foreach ($ALLcat as  $ty) {
                        echo "<option value='" . $ty['ID'] . "'";
                        if ($cats['Parent'] == $ty['ID']) {
                          echo "selected";
                        }
                        echo ">" . $ty['Name'] . "</option>";
                      }
                     ?>
                  </select>
                </div>
              </div>
              <!--End Category Type --->
              <!--start  Visibility Field -->
              <div class="form-group">
                <label class="col-sm-2 control-label">Visibility</label>
                <div class="col-sm-10 col-md-6">
                  <div >
                    <input id="vis-yes" type="radio" name="visibility" value="0" <?php if ($cats['Visibility'] == 0) {echo 'checked';} ?> >
                    <label for="vis-yes">Yes</label>
                  </div>
                  <div >
                    <input id="vis-no" type="radio" name="visibility" value="1" <?php if ($cats['Visibility'] == 1) {echo 'checked';} ?> >
                    <label for="vis-no">No</label>
                  </div>
                </div>
              </div>
              <!--End Visibility Field -->
              <!--End Comment Field -->
              <div class="form-group">
                <label class="col-sm-2 control-label">Allow_Comment</label>
                <div class="col-sm-10 col-md-6">
                  <div >
                    <input id="com-yes" type="radio" name="commenting" value="0" <?php if ($cats['Allow_Comment'] == 0) {echo 'checked';} ?> >
                    <label for="com-yes">Yes</label>
                  </div>
                  <div >
                    <input id="com-no" type="radio" name="commenting" value="1" <?php if ($cats['Allow_Comment'] == 1)  {echo 'checked';}  ?> >
                    <label for="com-no">No</label>
                  </div>
                </div>
              </div>
              <!--End Ads Field -->
              <div class="form-group">
                <label class="col-sm-2 control-label">Allow_Ads</label>
                <div class="col-sm-10 col-md-6">
                  <div >
                    <input id="ads-yes" type="radio" name="ads" value="0" <?php if ($cats['Allow_Ads'] == 0) {echo 'checked';} ?> >
                    <label for="ads-yes">Yes</label>
                  </div>
                  <div >
                    <input id="ads-no" type="radio" name="ads" value="1" <?php if ($cats['Allow_Ads'] == 1) {echo 'checked';}  ?> >
                    <label for="ads-no">No</label>
                  </div>
                </div>
              </div>
              <!--End Ads Field -->
              <!--Start submit -->
              <div class="form-group">
                <div class="col-sm-offset-2 col-md-10">
                  <input type="submit" class="btn btn-primary" value="Save">
                </div>
              </div>
              <!-- End  submit -->
            </form>
          </div>
          <?php
          // There's No Such ID To Show ...
        }else {
          echo "<div class='container'";
          $thMsg = "<div class='alert alert-danger'>There's No such ID To Show </div>";
          redirectHome($thMsg);
          echo "</div>";
        }
      }elseif ($do == "Update") {
        echo "<h1 class='text-center'> Update Category</h1>";
        echo "<div class='container'>";
        // Update category Page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          $catid     = $_POST['catid'];
          $name      = $_POST['name'];
          $desc      = $_POST['description'];
          $order     = $_POST['ordering'];
          $parent    = $_POST['parent'];
          $visible   = $_POST['visibility'];
          $comment   = $_POST['commenting'];
          $ads       = $_POST['ads'];

          $formErrors = array();
          if (strlen($name) < 4 ) {
            $formErrors[] = "Name Of Category Can't Be less Than 45 Charactar";
          }
          if (strlen($desc) < 10) {
            $formErrors[] = "The Description Can't Be Less Than 10 Charactar";
          }
          // Handle The Error If There
          foreach ($formErrors as $error) {
            echo "<div class='container'>";
            $thMsg = "<div class='alert alert-danger'>" . $error . "</div>";
            redirectHome($thMsg , 'back');
            echo "</div>";
          }
          if (empty($formErrors) && !empty($name)) {

              // Set The Data Here
              $stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Parent = ?,
              Ordering = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? WHERE ID = ?");
              // Execute The statment
              $stmt->execute(array($name , $desc, $order, $parent ,$visible, $comment, $ads, $catid));

              # Echo The Success message
                $thMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Category Updated</div>";
                redirectHome($thMsg , 'back');

          } // End if Of Error
        }else {
          echo "<div class='container'>";
            $thMsg = "<div class='alert alert-danger'>You can't Browse This Page Directly</div>";
            redirectHome($thMsg);
        }
        echo "</div>";
      }elseif ($do == "Delete") { // Delete Category Page
        echo "<div class='container'>";
        echo "<h1 class='text-center'>Delete Category</h1>";
        # Check If Request Catid And Get Integer Value Of It
        $catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0 ;
        # Check If The Item Is Exist
        $check = CheckItem("ID" , "categories" , $catid);
        if ($check > 0) {
          $stmt2 = $con->prepare("DELETE FROM categories WHERE ID = ? ");
          $stmt2->execute(array($catid));
          # Echo Success MEssage
          $thMsg =  "<div class='alert alert-success'>" . $stmt2->rowCount() . " Category Deleted</div>";
          redirectHome($thMsg , 'back');
        }else {
          $thMsg = "<div class='alert alert-danger'>There's  No Such ID Not Exist </div>";
          redirectHome($thMsg);
        }
         echo "</div>";
      }elseif ($do == "Apprave") {
        // code...
      }?>

      <?php
      include $tpl . 'footer.php';
    }else {
      header('Location: Index.php');
      exit();
    } // End If OF Sesion User

    ob_end_flush();
 ?>
