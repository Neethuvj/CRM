<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="description" content="">
<meta name="author" content="">
<title>Welcome to Salessupport 360</title>

<!-- Bootstrap Core CSS -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap-customized.min.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/plugins.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/font-awesome.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<!-- <script src="<?php echo base_url(); ?>assets/js/moment.js"></script> -->
<!-- <script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script> -->
<!-- Custom CSS -->
<link href="<?php echo base_url(); ?>assets/css/modern-business.css" rel="stylesheet">

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



<link href="<?php echo base_url(); ?>assets/css/chosen.css" rel="stylesheet">

</head>

<body id="signup">
<div class="taskloader">
<div class="loader">

   <img src="<?php echo base_url(); ?>assets/images/loader.gif" alt="">
</div>
 </div>
<!-- Navigation -->
<header id="signup-header">
  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid"> 
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header text-center">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
        
        <button type="button" class="navbar-toggle sidebar-toggle collapsed" data-toggle="collapse" data-target="#left-sidebar">
            <span class="fa fa-align-left"></span>
          </button>

          <?php if($role_id == 1 ||$role_id == 8): ?>
        <a class="navbar-brand" href="<?php echo base_url()."admin/configuration/index"; ?>"><img src="<?php echo base_url(); ?>assets/images/logo.svg" width="243px" alt=""></a> </div>
      <?php else: ?>
      <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/logo.svg" width="243px" alt=""></a> </div>
      <?php endif; ?>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <?php if($role_id == 2 || $role_id == 9): ?>
        <ul class="nav navbar-nav ">
   <!--  -->
          <li class="<?php if($this->uri->segment(1) == "task" && $this->uri->segment(2) == "inprogress"){echo "active";}?>"> <a href="<?php echo base_url(); ?>task/inprogress">In Progress Task</a> </li>
         <li class="<?php if($this->uri->segment(1) == "task" && $this->uri->segment(2) == "completed_tasks"){echo "active";}?>"> <a href="<?php echo base_url(); ?>task/completed_tasks">Completed Task</a> </li>
           <li class="<?php if($this->uri->segment(1) == "task" && $this->uri->segment(2) == "deleted_tasks"){echo "active";}?>"> <a href="<?php echo base_url(); ?>task/deleted_tasks">Deleted Task</a> </li>
          <li class="<?php if($this->uri->segment(1) == "task" && $this->uri->segment(2) == "summary"){echo "active";}?>"> <a href="<?php echo base_url(); ?>task/summary">Summary</a> </li>




         
          <?php if($company == TRUE ): ?>
          
        <li class="<?php if($this->uri->segment(1) == "team" ){echo "active";}?>"> <a href="<?php echo base_url(); ?>team/active">Team Members</a> </li>
          <?php endif; ?>
          <!-- <li> <a href="#">Login History</a> </li> -->
        </ul>
      <?php endif; ?>

        <?php if($role_id == 3): ?>
        <ul class="nav navbar-nav ">
   <!--  -->
         
           <li class="<?php if($this->uri->segment(1) == "task" && $this->uri->segment(2) == "inprogress"){echo "active";}?>"> <a href="<?php echo base_url(); ?>task/inprogress">Task</a> </li>

            <li class="<?php if($this->uri->segment(1) == "customer" && $this->uri->segment(2) == "index"){echo "active";}?>"> <a href="<?php echo base_url(); ?>customer/index">Customer</a> </li>

    

           <li class="<?php if($this->uri->segment(1) == "user" && $this->uri->segment(2) == "bda_profile"){echo "active";}?>"> <a href="<?php echo base_url(); ?>user/bda_profile">My Profile</a> </li>



          
          <!-- <li> <a href="#">Login History</a> </li> -->
        </ul>

      <?php endif; ?>
        <ul class="nav navbar-nav navbar-right">
         <?php if($role_id == 2 || $role_id == 9): ?>
          <li>

          <ul>
 <li style="margin-right: 8px;"><a class="btn btn-primary email-confirmation">Ambassador Program</a></li>
              </ul>
          </li>
          <li>

          <ul>
 <li style="margin-right: 15px;"><a class="btn btn-primary add-referals">Refer & Earn</a></li>
              </ul>
          </li>
        <?php endif; ?>
          <li class="dropdown-toggle"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="admin-name"> <?php if(isset($first_letter) && $first_letter) echo strtoupper($first_letter); ?></span><b class="caret"></b></a>
            <ul class="dropdown-menu">

              <?php if($role_id == 3): ?>
              <li> <a href="/user/bda_profile">Edit Profile</a> </li>
            <?php else: ?>
               <li> <a href="/user/edit">Edit Profile</a> </li>
            <?php endif; ?>
              <?php if(!empty($switch_from)) : ?>  
              <li> <a href="/user/switch_as_admin">Switch Back</a> </li>

            <?php endif; ?>
              
              <li> <a href="/user/logout">Logout</a> </li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- /.navbar-collapse --> 
    </div>
  </nav>
</header>


<div class="modal fade add-referals" id="padd-referals" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content do-nicescroll">
            <div class="modal-header">
                <a href="#" dismiss="modal" class="close class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6 col-sm-12 col-xs-12 product_view-bg refer">
 
                    </div>

                    <div class="col-md-6 col-sm-12 col-xs-12 refer">  
                    <h4><b>Refer a Colleague and Earn</b></h4>
                     <h5>$50 for every Sign Up!</h5>

                      <?php 
                      $attributes = array('id' => 'referral_form');
                      echo form_open('user/add_referrals',$attributes);?>
                  <div class="input_fields_wrap">
                      <div class="wrapper-to-add">
                        <div><input type="text" placeholder="Name*" name="name[]" class="name referral"></div>

                        <div><input type="text" placeholder="Email" name="email[]" class="email"></div>

                        <div><input type="text" placeholder="Phone Number*" name="phone_number[]" class="phone_number" ></div>

                        <br><a class="add-fields-referals">Add</a>
                    </div>
                  </div>
                        <div class="row refer-button">
                          <button type="button" class="btn btn-primary save">Submit</button>
                          <a href="/task/<?php echo $this->uri->segment(2);?>" class="btn btn-default cancel-popup">Cancel</a>


                           
                        </div>
                         <?php echo form_close(); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div id="add-link-page" class="modal fade email-confirmation" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" data-keyboard="false" data-backdrop="static" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-hide="modal" aria-hidden="true">×</button>
         <?php 
            //$attributes = array('class' => 'confirmation_form');
            //echo form_open('user/email_confirmation');?>
        <h4 class="modal-title">Ambassador Program</h4>
      </div>
      <div class="modal-body">
      
      You will receive an email about how our Ambassador Program works.
      A simple referral system with tremendous monthly upside!

      </div>

      <div class="modal-footer">
        <input class="btn btn-default save-btn" type="submit" value="SEND">
           <a href="/task/<?php echo $this->uri->segment(2);?>" class="btn btn-default btn-no">DON'T SEND</a>
      </div>
    </div>
  </div>
  <?php //echo form_close(); ?>
</div>