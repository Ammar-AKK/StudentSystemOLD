<?php
  require_once './config.php';
  if( isset( $_SESSION["student"] )){
    header("location: ./student.php");
  }else if( isset( $_SESSION["staff"] )){
     header("location: ./staff.php");
  }else{
    header("location: ./login.php");
  }




?>