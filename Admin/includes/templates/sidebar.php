<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

      <ul class="sidebar">
        <li >
          <a class=" brand " href="dashboard.php"><i class="fa fa-home"></i>  </a>
        </li>
        <li >
          <a  href="categories.php"> <i class="fa fa-cogs"></i>  </a>
        </li>
        <li >
          <a  href="items.php"><i class="fa fa-tags"></i>  </a>
        </li>
        <li >
          <a  href="members.php"><i class="fa fa-users"></i>  </a>
        </li>
        <li >
          <a  href="comments.php"><i class="fa fa-comments-o"></i>  </a>
        </li>
        <li >
          <a  href="#"><i class="fa fa-line-chart" aria-hidden="true"></i>  </a>
        </li>
        <li >
          <a  href="#"><i class="fa fa-sign-in" aria-hidden="true"></i>  </a>
        </li>
      </ul>

          <a class="dropdown-item" href="logout.php">Logout</a>
          <div class="clear">

          </div>
    </div>

    <?php
    include $tpl .'footer.php';
     ?>
  </body>
</html>
