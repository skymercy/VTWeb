<?php
/* @var $this \biny\lib\Response */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <meta name="keywords" content="<?=$this->encode($this->keywords) ?: "教学管理系统"?>">
    <meta name="description" content="<?=$this->encode($this->descript) ?: "教学管理系统"?>">
    <title><?=$this->encode($this->title) ?: "教学管理系统"?></title>
    <link rel="icon" href="<?=$webRoot?>/assets/images/icon/favicon.ico" type="image/x-icon" />

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?=$webRoot?>/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="<?=$webRoot?>/assets/font-awesome/4.5.0/css/font-awesome.min.css" />

    <!-- page specific plugin styles -->

    <!-- text fonts -->
    <link rel="stylesheet" href="<?=$webRoot?>/assets/css/fonts.googleapis.com.css" />

    <link rel="stylesheet" href="<?=$webRoot?>/assets/css/jquery.toast.min.css" />
    
    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script src="<?=$webRoot?>/assets/js/jquery-2.1.4.min.js"></script>

    <!-- <![endif]-->

    <!--[if IE]>
    <script src="<?=$webRoot?>/assets/js/jquery-1.11.3.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='<?=$webRoot?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
    </script>
    <script src="<?=$webRoot?>/assets/js/bootstrap.min.js"></script>