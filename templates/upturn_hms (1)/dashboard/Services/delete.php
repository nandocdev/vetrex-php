<?php 
include("../inc/connect.php") ;
if(isset($_GET['id']))
      {
      	$sql="DELETE FROM mainservices WHERE  id=".$_GET['id']."";
      	//exit;
      	$write =mysqli_query($conn,$sql) or die(mysqli_error($db_connect));
            
              header("location:addservices.php");
      }
      else
      	echo "Not Sucess";
   ?>