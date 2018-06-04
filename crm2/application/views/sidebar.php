<div class="container-fluid admin-panel-signup">
<div class="table-row">

<aside id="left-sidebar" class="rs-sidebar left-sidebar <?php echo $sidebar_class; ?> " style="height:500px;"> 
     <div class="sidebar-minified js-toggle-minified">
                <i class="gcon gcon-exchng rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Click here to Expand/Collapse Sidebar"><span class="gcon gcon-exchng rs-icon-menu"></span></i>
            </div>
    <!-- Sidebar menu -->
             <!-- main-nav -->
            <div class="sidebar-scroll">
               
                <nav class="main-nav">
    <ul class="rs-sidebar-nav default-sidebar-nav">
    <?php

$controller = $this->uri->segment(1); 
$action = $this->uri->segment(2); 

?>
    <?php if($role_id == 2 || $role_id == 9): ?>
      <li class="rs-user-sidebar">
        <ul>
        <?php if($company == TRUE && $controller == 'team'): ?>
            <li  class="<?php echo ($controller == "team" && $action == "active") ? "active" : ""; ?>"><a  href="<?php echo base_url(); ?>team/active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Active Team Members"></span>Active Team Members</a></li>

            <li  class="<?php echo ($controller == "team" && $action == "in_active") ? "active" : ""; ?>"><a  href="<?php echo base_url(); ?>team/in_active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Inactive Team Members"></span>Inactive Team Members</a></li>
        <?php else: ?>


  <?php if($role_id == 2): ?>
    
    
  <li  class="<?php echo ($controller == "user" && $action == "reports") ? "active" : ""; ?>"><a href="<?php echo base_url(); ?>user/reports" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Order Report"></span>Order Report</a></li>
   
  <li  class="<?php echo ($controller == "user" && $action == "update_credit_card") ? "active" : ""; ?>"><a  href="<?php echo base_url(); ?>user/update_credit_card" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Update Credit Card"></span>Update Credit Card</a></li>
  <?php endif; ?>

<?php if($role_id != 2): ?>

         <li  class="<?php echo ($controller == "user" && $action == "reports") ? "active" : ""; ?>"><a href="<?php echo base_url(); ?>user/reports" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Order Report"></span>Order Report</a></li>

  <?php endif; ?>
  
          <li class="<?php echo ($controller == "user" && $action == "edit") ? "active" : ""; ?>"><a href="<?php echo base_url(); ?>user/edit"><span class="gcon gcon-config rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Edit Profile"></span>Edit Profile</a></li>
          <!-- <li><a href="javascript:void(0);"><span class="gcon gcon-changeplan rs-icon-menu"></span>Change plan</a></li> -->
          <li class="<?php echo ($controller == "user" && $action == "changepassword") ? "active" : ""; ?>"><a href="<?php echo base_url(); ?>user/changepassword"><span class="gcon gcon-pass rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Change Password"></span>Change Password</a></li>

          
        <?php endif; ?>
        </ul>
      </li>
        <?php endif; ?>
        <?php if($role_id == 4): ?>
  <li class="<?php echo ($controller == "user" && $action == "dashboard") ? "active selected" : " menu"; ?>"><a href="<?php echo base_url(); ?>user/dashboard"><span class="gcon gcon-home rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Home"></span>Home</a></li>
          <li class="<?php echo ($controller == "task" && $action == "index") ? "active selected" : " menu"; ?>"><a href="<?php echo base_url(); ?>task/index"><span class="gcon gcon-changeplan rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Tasks"></span>Tasks</a></li>
          <li class="<?php echo ($controller == "user" && $action == "edit") ? "active selected" : " menu"; ?>"><a href="<?php echo base_url(); ?>user/edit"><span class="gcon gcon-profile rs-icon-menu"  data-toggle="tooltip" data-placement="right" title="My Profile"></span>My Profile</a></li>
        <?php endif; ?>


         <?php if(($controller == "task") && ($role_id == 3 )): ?>

  <li  class=<?php echo ($controller == "task" && $action == "order_report") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>task/reports" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Order Report"></span>Order Report</a></li>

  <li  class=<?php echo ($controller == "task" && $action == "inprogress") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>task/inprogress" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="In progress Task"></span>In progress Task</a></li>
  <li  class=<?php echo ($controller == "task" && ($action == "completed" || $action == "completed_tasks")) ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>task/completed_tasks" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Completed Task"></span>Completed Task</a></li>
    <li  class=<?php echo ($controller == "task" && $action == "summary") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>task/summary" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Summary"></span>Summary</a></li>
        
   
       <?php endif; ?> 



       <?php if(($controller == "customer") && ($role_id == 3 )): ?>
         <?php if($this->uri->segment(2)  == "team_members"): ?>

            <li><a href="/customer/team_members/<?php echo $this->uri->segment(3); ?>/active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Active"></span>Active</a></li>
 
        
        <li><a href="/customer/team_members/<?php echo $this->uri->segment(3); ?>/in_active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Active"></span>In Active</a></li>
         <?php else: ?>
  <li  class=<?php echo ($controller == "customer" && $action == "index") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>customer/index" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Active"></span>Active</a></li>
 
        
    <li  class=<?php echo ($controller == "customer" && $action == "in_active") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>customer/in_active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Inactive"></span>Inactive</a></li>
         <?php endif; ?>


       <?php endif; ?> 




  <?php if(($controller == "user") && ($role_id == 3 )): ?>

     <li  class=<?php echo ($controller == "user" && $action == "bda_profile") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>user/bda_profile" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Active"></span>Edit Profile</a></li>
    

       <?php endif; ?> 
   
    </ul>
    </nav>

                <!-- /main-nav -->
            </div>
    <!-- End sidebar menu --> 
  </aside>

