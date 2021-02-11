<?php
session_start();
$pageTitle = 'New Product';
include 'init.php';
if (isset($_SESSION['user'])) {
    // Get Information Of zthis user
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $imageName  = $_FILES['image']['name'];
        $imageTmp   = $_FILES['image']['tmp_name'];
        $imageType  = $_FILES['image']['type'];
        $imageSize  = $_FILES['image']['size'];

        // List Of Allowed Exetenion
        $imageAllowedExetenion = array("jpeg" ,"jpg" ,"png", "gif");
        // Exentenion Allowed
        $imageExetenion = @ strtolower(end(explode('.', $imageName)));
        // Get Variable OF Adding Item
        $name     =  filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $desc     =  filter_var($_POST['description'], FILTER_SANITIZE_STRING);
        $price    =  filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
        $country  =  filter_var($_POST['country'], FILTER_SANITIZE_STRING);
        $status   =  filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
        $rating   =  filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT);
        $category =  filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
        $tags     =  filter_var($_POST['tags'], FILTER_SANITIZE_STRING);
        // For  Erroe For Holding all Error
        $formErrors = array();
        if (strlen($name) < 4) {
          $formErrors[] = "Name Of Product Must Be Larger Then 4 Character";
        }
        if (strlen($desc) < 10) {
          $formErrors[] = "Description Of Product Must Be Larger Then 10 Character";
        }
        if (empty($price)) {
          $formErrors[] = "Price Of Product Must Be Not Empty";
        }
        if (strlen($country) < 2) {
          $formErrors[] = "Country Of Product Must Be Larger Then 2 Character";
        }
        if (empty($status) ) {
          $formErrors[] = "The Status Of Product Must Be Not Empty";
        }
        if (empty($category)) {
          $formErrors[] = "The Category Of Product Must Be Not Empty";
        }
        if (! empty($imageName) && ! in_array($imageExetenion, $imageAllowedExetenion)) {
          $formErrors[] = "The Exetenion is Not Allowed";
        }
        if ($imageSize > 4194304) {
          $formErrors[] = "The Item Image Can't  Be Larger Than 4MB";
        }
        if (empty($imageName)) {
          $formErrors[] = "The Item Image Can't Be Empty";
        }
        // Looping The $formErrors If Empty Proceed The Next Command
        if (empty($formErrors)) {
          // Great The Variable Name Of Profile Image
          $image = rand(0, 100000000000) . "_" . $imageName;
          // Upload The Profilr Image
          move_uploaded_file($imageTmp , "Admin/Uploads/" . $image);
          // Inser The Item To database
          // Get THe UserID Form Database THrougth The User Name Session
          $onerecord = $con->prepare("SELECT UserID FROM users WHERE Username = ?");
          $onerecord->execute(array($_SESSION['user']));
          $record = $onerecord->fetch();
          $_SESSION['uid']  = $record['UserID'];
          $stmt = $con->prepare("INSERT INTO
            items(Name , Description, Price , Country_Made , Status, Rating , Add_Date , Cat_ID  ,Member_ID, Tags, Images)
            VALUES(:zname, :zdesc, :zprice , :zcountry ,:zstat, :zrat ,now() , :zcat ,:zmem, :ztags, :zimage)");
            // Here I Used Associative $arrayName = array('' => , ); Because The Child Row Cant Add
            // Without IT IN Database
          $stmt->execute(array(
            'zname'     => $name,
            'zdesc'     => $desc,
            'zprice'    => $price,
            'zcountry'  => $country,
            'zstat'     => $status,
            'zrat'      => $rating,
            'zcat'      => $category,
            'zmem'      => $_SESSION['uid'],
            'ztags'     => $tags,
            'zimage'    => $image
          ));
          // If Statment Successful Get The Message
          if ($stmt) {
            echo "<div class='alert alert-success'> Product Has Been Added Successful </div>";
          }
      } // End If Of REQUEST_METHOD

    } // End If Of Session
    $do = isset($_GET['do'])? $_GET['do'] : 'Manage';

    if ($do == 'Manage') { //  Get The Main Info ?>
        <div class="container block">
          <div class="card" >
            <div class="card-header">
              create New Product
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-8">
                  <form class="form-horizotal" action="<?php $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
                    <!-- start Field Name -->
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Name</label>
                      <div class="col-sm-10 col-md-10">
                        <input
                        pattern=".{4,}"
                        title="This Feild Required At Least 4 Charcatar"
                        type="text"
                        name="name"
                        class="form-control live" placeholder="type Name Of Product "
                         required data-class=".live-title">
                      </div>
                    </div>
                    <!-- End Field Name -->
                    <!-- start Field Description -->
                    <div class="form-group">
                      <label class="col-sm-3 control-label" >Description</label>
                      <div class="col-sm-10 col-md-10">
                        <input
                        pattern=".{10,}"
                        title="THis Feild Required At Least 10 Charcatar"
                        type="text"
                         name="description"
                         class="form-control live" placeholder="Type description Of Product"
                          required data-class=".live-desc">
                      </div>
                    </div>
                    <!-- End Field Description -->
                    <!-- start Field Price -->
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Price</label>
                      <div class="col-sm-10 col-md-10">
                        <input type="text" required name="price"
                        class="form-control live" placeholder="Enter Price Of This Product"
                        data-class=".live-price" >
                      </div>
                    </div>
                    <!-- End Field Price -->
                    <!-- start Field Country -->
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Country</label>
                      <div class="col-sm-10 col-md-10">
                        <input type="text" required name="country"
                         class="form-control " placeholder="type Country Of Made ">
                      </div>
                    </div>
                    <!-- End Field Country -->
                    <!-- start Field Status -->
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Status</label>
                      <div class="col-sm-10 col-md-10">
                        <select class="form-control" required name="status">
                          <option value="0">...</option>
                          <option value="1">New</option>
                          <option value="2">Like New</option>
                          <option value="3">Used</option>
                          <option value="4">Very Old</option>
                        </select>
                      </div>
                    </div>
                    <!-- End Field Status -->
                    <!-- start Field Rating -->
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Rating</label>
                      <div class="col-sm-10 col-md-10">
                        <select class="form-control" name="rating">
                          <option value="0">...</option>
                          <option value="1">*</option>
                          <option value="2">**</option>
                          <option value="3">***</option>
                          <option value="4">****</option>
                          <option value="4">*****</option>
                        </select>
                      </div>
                    </div>
                    <!-- End Field Rating -->
                    <!-- start Field Category -->
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Category</label>
                      <div class="col-sm-10 col-md-10">
                        <select class="form-control" required name="category">
                          <option value="0">...</option>
                          <?php
                          $cats = getALlFrom('*', 'categories', '', '', 'ID');
                          foreach ($cats as $cat) {
                            echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                          }
                           ?>
                        </select>
                      </div>
                    </div>
                    <!-- End Field Category -->
                    <!--Start Profile Feild-->
                      <div class="form-group">
                        <label class="col-sm-2 control-label">Item Image</label>
                        <div class="col-sm-10 col-md-10">
                          <input type="file" name="image" class="form-control"   required="required">
                        </div>
                      </div>
                    <!--End Profile Feild-->
                    <!--Start Tags Field-->
                    <div class="form-group">
                      <label class="col-sm-3 control-label" >Tags</label>
                      <div class="col-sm-10 col-md-10">
                        <input type="text" name="tags" class="form-control" autocomplete="off"
                        placeholder="Separete Tage With Comma (,)" >
                      </div>
                    </div>
                    <!-- End Tags Field -->
                    <!-- start Field Submit -->
                    <div class="form-group">
                      <div class="col-sm-10 col-md-10 ">
                        <input type="submit"  class="btn btn-primary " value="Add Product">
                      </div>
                    </div>
                    <!-- End Field Submit -->
                  </form>
                </div>
                <div class="col-md-4">
                  <div class='thumbnail item-box live-preview'>
                    <span class='price-tag'>
                      $<span class="live-price">0</span>
                    </span>
                     <img src='Layout/Images/one.jpg' class='card-img-top' />
                      <div class='caption'>
                        <h3 class='live-title'>title </h3>
                         <p class='live-desc'>Description</p>
                      </div>
                  </div>
               </div>
          </div>
          <?php
           if (!empty($formErrors)) {
             foreach ($formErrors as $error) {
               echo "<div class='alert alert-danger Error'>" . $error . "</div>";
             }
           }

           ?>
        </div>
      </div>
    </div>
<?php }elseif ($do == 'Edit') {
      // code...
    }elseif ($do == 'Update') {
      // code...
    }
}else {
  header("Location:Index.php");
  exit();
}


include $tpl . 'footer.php'; ?>
