<?php
  ob_start();
    session_start();
    $pageTitle = 'Home';
    include 'init.php';
    ?>
    <div class=" bg-blue ">
      <div class="overlay">
        <span class="heading">You Are <span class="head1">In A Big Computer</span> Shoping</span>
        <h5 class="title"></h5>
        <p class="text"></p>
        <svg class="wave" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
          <path fill="#007bff" fill-opacity="1" d="M0,288L21.8,288C43.6,288,87,288,131,245.3C174.5,203,218,117,262,112C305.5,107,349,181,393,213.3C436.4,245,480,235,524,208C567.3,181,611,139,655,106.7C698.2,75,742,53,785,85.3C829.1,117,873,203,916,240C960,277,1004,267,1047,256C1090.9,245,1135,235,1178,213.3C1221.8,192,1265,160,1309,160C1352.7,160,1396,192,1418,208L1440,224L1440,320L1418.2,320C1396.4,320,1353,320,1309,320C1265.5,320,1222,320,1178,320C1134.5,320,1091,320,1047,320C1003.6,320,960,320,916,320C872.7,320,829,320,785,320C741.8,320,698,320,655,320C610.9,320,567,320,524,320C480,320,436,320,393,320C349.1,320,305,320,262,320C218.2,320,175,320,131,320C87.3,320,44,320,22,320L0,320Z"></path></svg>

      </div>
    </div>
    <div class="container">
      <h3 class="header text-center">
        Latest Product
        <span class="text-center"></span>
      </h3>
      <div class="row">
        <?php
        $allItem = getALlFrom('*', 'items' ,  'WHERE Approve = 1' , '', 'ID');
        if (! empty($allItem)) {
          foreach ($allItem as $item) {
            echo "<div class='col-sm-6 col-md-3 '>";
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
          $text =  "<div class='alert alert-info'> Sorry We have  No Items TO Show ..! </div>";
        }
         ?>
      </div>
      <?php if (isset($text)) {
        echo $text;
      } ?>
    </div>
  <?php
 include $tpl . 'footer.php';
 ob_end_flush();
  ?>
