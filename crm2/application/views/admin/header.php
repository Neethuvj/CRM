<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="description" content="">
<meta name="author" content="">
<title>Welcome to Salessupport 360</title>


<link href="<?php echo base_url(); ?>assets/css/jquery-ui.css" rel="stylesheet">
<!-- Bootstrap Core CSS -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap-customized.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/plugins.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet">

<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment-timezone.js"></script>


<!-- <script src="<?php echo base_url(); ?>assets/js/moment.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script> -->
<!-- Custom CSS -->
<link href="<?php echo base_url(); ?>assets/css/modern-business.css" rel="stylesheet">

<link href="<?php echo base_url(); ?>assets/css/chosen.css" rel="stylesheet">




<!-- Custom Fonts -->
<!-- <link href="<?php echo base_url(); ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
 <!--script>
//     $('.carousel').carousel({
//         interval: 5000 //changes the speed
//     })
//     </script-->


</head>

<body id="signup">
<div class="taskloader">
<div class="loader">
   <img src="<?php echo base_url(). "admin/configuration/index"; ?>assets/images/loader.gif" alt="">
</div>
 </div>
<!-- Navigation -->
<header id="signup-header">
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid"> 
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header text-center">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        
        <button type="button" class="navbar-toggle sidebar-toggle collapsed" data-toggle="collapse" data-target="#rssidebar" aria-expanded="false">
            <span class="fa fa-align-left"></span>
          </button>
            <?php if($role_id == 1 ||$role_id == 8): ?>
        <a class="navbar-brand" href="<?php echo base_url()."admin/configuration/index"; ?>"><img src="<?php echo base_url(); ?>assets/images/logo.svg" width="243px" alt=""></a> </div>
      <?php else: ?>
      <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/logo.svg" width="243px" alt=""></a> </div>
      <?php endif; ?>
  
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    
        <ul class="nav navbar-nav ">
   <!--  -->
          <li class=<?php if($this->uri->segment(2) == "configuration" && $this->uri->segment(3) == 'index'){echo "active";}?>> <a href="<?php echo base_url(); ?>admin/configuration/index">Home</a> </li>
         <li class=<?php if($this->uri->segment(2) == "customer"){echo "active";}?>> <a href="<?php echo base_url(); ?>admin/customer/index">Customer</a> </li>
           <li class=<?php if($this->uri->segment(2) == "support"){echo "active";}?>> <a href="/admin/support/bda_list">Support Team</a> </li>

           <li class=<?php if($this->uri->segment(2) == "company"){echo "active";}?>> <a href="/admin/company/active">Company</a></li>


            <li class=<?php if($this->uri->segment(2) == "task"){echo "active";}?>> <a href="<?php echo base_url();?>admin/task/inprogress">Task</a> </li>
              <li class=<?php if($this->uri->segment(2) == "configuration" && $this->uri->segment(3) == "login_history"){echo "active";}?>> <a href="/admin/configuration/login_history">Login History</a> </li>
                     <li class=<?php if($this->uri->segment(2) == "team"){echo "active";}?>> <a href="/admin/team/pending">Team Requests</a> </li>
                     <li class=<?php if($this->uri->segment(2) == "referral"){echo "active";}?>> <a href="/admin/referral/index">Referral Data</a> </li>
          <!-- <li> <a href="#">Login History</a> </li> -->
        </ul>
     
        <ul class="nav navbar-nav navbar-right">
   
          <li class="dropdown-toggle"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="admin-name"> <?php if(isset($first_letter) && $first_letter) echo strtoupper($first_letter); ?></span><b class="caret"></b></a>
            <ul class="dropdown-menu">
              <li> <a href="/user/edit">Edit Profile</a> </li>


            

              <li> <a href="/user/logout">Logout</a> </li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- /.navbar-collapse --> 
    </div>
  </nav>
</header>


