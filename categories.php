<?php
session_start();
$pageTitle = 'Categories';
include 'init.php';?>
<h1 class="text-center"><?php
if (isset($_GET['pagename']) && ! is_numeric($_GET['pagename'])) {
echo str_replace('-', '' , $_GET['pagename']);
}else {
  echo "<div class='alert alert-danger'>Sory... Please Enter Valide Name .</div>";
}
?> </h1>
<div class="container">
  <div class="row">
    <?php
    if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
      $catid = $_GET['pageid'];
      if (! empty(getALlFrom("*", "items", "WHERE Cat_ID = {$catid}" , "" , "Cat_ID"))) {
        foreach (getItem("Cat_ID" , $catid) as $item) {
          echo "<div class='col-sm-6 col-md-3'>";
            echo "<div class='card Box'>";
              echo "<div class='thumbnail item-box'>";
                echo "<span class='price-tag'>$" . $item['Price'] . "</span>";
                // if ($item['Approve'] == 0) {
                //   echo "<span class='price-tag' >Not Approved</span>";
                // }
                  echo "<img src='Layout/Images/one.jpg' class='card-img-top' alt='...' />";
                  echo "<div class='caption'>";
                      echo "<h4 class='card-title'><a href='product.php?productid=" . $item['ID'] . "'>" . $item['Name'] . "</a></h4>";
                      echo "<p class='card-text'>" . $item['Description'] . "</p>";
                      echo "<div class='Date'>" . $item['Add_Date'] . "</div>";
                  echo "</div>";
              echo "</div>";
            echo "</div>";
          echo "</div>";
        }

      } else {
        $text =  "<div class='alert alert-info'> This Category Has No Items TO Show ..! </div>";
      }
    }

     ?>
  </div>
  <?php if (isset($text)) {
    echo $text;
  } ?>
</div>
<?php include $tpl . 'footer.php';
 ?>
