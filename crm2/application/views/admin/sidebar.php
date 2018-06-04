<div class="container-fluid admin-panel-signup">

  <?php

$controller = $this->uri->segment(2); 

$action = $this->uri->segment(3); 
?>
    

<aside id="left-sidebar" class="rs-sidebar left-sidebar <?php echo $sidebar_class; ?> " style="height:500px;"> 
     <div class="sidebar-minified js-toggle-minified ">
<?php if($controller !== "customer" && $controller !== "referral"): ?>

                <i class="gcon gcon-exchng rs-icon-menu <?php echo $controller; ?>" data-toggle="tooltip" data-placement="right" title="Click here to Expand/Collapse Sidebar"><span class="gcon gcon-exchng rs-icon-menu"></span></i>
              <?php endif; ?>
            </div>
    <!-- Sidebar menu -->
             <!-- main-nav -->
            <div class="sidebar-scroll">
               
                <nav class="main-nav">
    <ul class="rs-sidebar-nav default-sidebar-nav">
  
      <li class="rs-user-sidebar">
        <ul>
<?php if($controller == "configuration"): ?>


  <li  class=<?php echo ($controller == "configuration" && $action == "index") ? "active" : ""; ?>><a href="<?php echo base_url(); ?>admin/configuration/index" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Configuration"></span>Configuration</a></li>
    <li  class=<?php echo ($controller == "configuration" && $action == "plan") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/configuration/plan" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Plan"></span>Plan</a></li>
     <li  class=<?php echo ($controller == "configuration" && $action == "change_password") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/configuration/change_password" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Change Password"></span>Change Password</a></li>
       <?php endif; ?> 
       <?php if($controller == "customer"): ?>
  <li  class=<?php echo ($controller == "customer" && $action == "index") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/customer/index" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Active Customers"></span>Active Customers</a></li>

   <li  class=<?php echo ($controller == "customer" && $action == "pending_activation") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/customer/pending_activation" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Pending Activation"></span>Pending Activation</a></li>

    <li  class=<?php echo ($controller == "customer" && $action == "old_customer") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/customer/old_customer" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Old Customer"></span>Old Customer</a></li>

    <li  class=<?php echo ($controller == "customer" && $action == "in_active") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/customer/in_active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Inactive Customers"></span>Inactive Customers</a></li>

      <li  class=<?php echo ($controller == "customer" && $action == "customer_sign_up") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/customer/customer_sign_up" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Customer Sign Up"></span>Customer Sign Up </a></li>

       <?php endif; ?> 

        <?php if($controller == "task"): ?>

    <li  class=<?php echo ($controller == "task" && $action == "order_report") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/task/order_report" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Order Report"></span>Order Report</a></li>
  <li  class=<?php echo ($controller == "task" && $action == "inprogress") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/task/inprogress" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="In progress Task"></span>In progress Task</a></li>
      <li  class=<?php echo ($controller == "task" && $action == "completed") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/task/completed" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Completed Task"></span>Completed Task</a></li>
       <li  class=<?php echo ($controller == "task" && $action == "summary") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/task/summary" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Summary"></span>Summary</a></li>
             <li  class=<?php echo ($controller == "task" && $action == "deleted") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/task/deleted" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Deleted Tasks"></span>Deleted Tasks</a></li>
        
         <li  class=<?php echo ($controller == "task" && $action == "all_reports") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/task/all_reports" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Report Info"></span>Report Info</a></li>
        

       <?php endif; ?> 


       <?php if($controller == "support"): ?>
  <li  class=<?php echo ($controller == "support" && $action == "bda_list") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/support/bda_list" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="View BDA List"></span>View BDA List</a></li>
   <li  class=<?php echo ($controller == "support" && $action == "analyst_list") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/support/analyst_list" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="View Analyst List"></span>View Analyst List</a></li>

      <li  class=<?php echo ($controller == "support" && $action == "account_manager_list") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/support/account_manager_list" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="View Account Manager List"></span>View Account Manager List</a></li>
    

       <?php endif; ?> 


  <?php if($controller == "company"): ?>
    <?php if($this->uri->segment(3)  == "team_members"): ?>
       <li  class=<?php echo ($controller == "company" && $action == "active") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/company/team_members/<?php echo $this->uri->segment(4); ?>/active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Active"></span>Active</a></li>


         <li  class=<?php echo ($controller == "company" && $action == "_active") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/company/team_members/<?php echo $this->uri->segment(4); ?>/in_active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="In Active"></span>In Active</a></li>
    <?php else: ?>
  <li  class=<?php echo ($controller == "company" && $action == "active") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/company/active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Active"></span>Active</a></li>


  <li  class=<?php echo ($controller == "company" && $action == "in_active") ? "active" : ""; ?>><a href="<?php echo base_url(); ?>admin/company/in_active" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="Inactive"></span>Inactive</a></li>
<?php endif; ?>
  
    

       <?php endif; ?> 
   


              <?php if($controller == "team"): ?>


                   <li  class=<?php echo ($controller == "team" && $action == "approved") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/team/approved" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="View Approved Team Requests"></span>Approved Team Requests</a></li>
  <li  class=<?php echo ($controller == "team" && $action == "pending") ? "active" : ""; ?>><a  href="<?php echo base_url(); ?>admin/team/pending" ><span class="gcon gcon-customer rs-icon-menu" data-toggle="tooltip" data-placement="right" title="View Pending Team Requests"></span>Pending Team Requests</a></li>

    
   

   
    

       <?php endif; ?> 

   
    </ul>
    </li>
    </ul>
    </nav>


                <!-- /main-nav -->
            </div>
    <!-- End sidebar menu --> 
  </aside>

