<?php

// Get  All Item Form Database v2.0
/* Functio To Get All Record  From Database

*/

function getALlFrom($feild , $table, $where = NULL , $and = NULL , $orderfeild , $orddering = "DESC") {
  global $con ;
  $getAll = $con->prepare("SELECT $feild FROM $table $where $and ORDER BY $orderfeild DESC  ");
  $getAll->execute();
  // fetchAll The Cat
  $all = $getAll->fetchAll();
  // return The Data
  return $all;
}





   ///*** Title Page Function V1.0
   //* Title Function That Echo The Page In Case The Page Has Variable
   // $pagetitle  And Echo Default Title For Other Page
   /*
   /*
   */

   function getTitle() {

     global $pageTitle;

     if (isset($pageTitle)) {
       echo $pageTitle;
     }else {
       echo 'Default';
     }
   }

   //
   /*
   ** Redirect Function v1.0
   **Home Redirect Function [This Function Accept Parameter]
   ** $errorMsg = Echo the Error Message
   ** $second   = SecontDefore Redirect
   */
   function redirectHome($thMsg , $url = null ,$second =3) {
     if ($url === null) {
       $url = "Index.php";
       $link = "Homepage";
     }else {
       if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){
         $url = $_SERVER['HTTP_REFERER'];
         $link = "Previous Page";
       }else {
         $url = "Index.php";
       }

     }
     echo $thMsg;
     echo "<div class='alert alert-info'>You Will BE Redirect To $link After $second Seconds</div>";
     header("refresh:$second;url=$url");
     exit();
   }

    /*
    ** Check Item  Function V1.0
    ** Function To check Item In database [Function Accep Parameter]
    ** $select = The Item To Select [ example: User , item , category]
    ** $From = the Table To  Select from [ Example: Users , items, Categories ]
    ** $value = The of Select from  [Example: almgdad , Box , Electronic]
    */
    function CheckItem($select , $from , $value ) {
      global $con;
      $stmt2 = $con->prepare("SELECT $select FROM $from WHERE $select = ?");
      $stmt2->execute(array($value));
      $cout = $stmt2->rowCount();
      return $cout;
    }

    /*
    ** Count Number OF Item Function v1.0
    ** Function To Count Number OF Item Rows Accept Parameter
    ** $Item To count It
    ** $table The Tabe To Choose From
    */
    function Countitems($item , $table) {
      global $con;
      $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table WHERE GroupID !=1");
      $stmt2->execute();
      return $stmt2->fetchColumn();
    }

    /*
    ** Count Number OF Item Function v2.0
    ** Function To Count Number OF Item Rows Accept Parameter
    ** $Item To count It
    ** $table The Tabe To Choose From
    */
    function Countitem($item , $table) {
      global $con;
      $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table ");
      $stmt2->execute();
      return $stmt2->fetchColumn();
    }

    // Get Latest Record Form Database
    /* Functio Tp Get Latest items From Database
    ** $select = Feild To Select
    ** $table = tabe To Choosen
    ** $limit = Number Of Record To get
    */
    function getlatest($select , $table , $order , $limit = 5) {
      global $con;
      $getstmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
      $getstmt->execute();
      $rows = $getstmt->fetchAll();
      return $rows;
    }
 ?>
