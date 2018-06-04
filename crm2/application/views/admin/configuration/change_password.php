

<article class="rs-content-wrapper" >
<!-- <div class="container"> -->
<div class="rs-content" >
<div class="rs-inner">
    <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
      <?php


      //echo validation_errors();

      echo form_open('admin/configuration/updatepassword');
      ?>

<div class="row">
       <h2 class="col-md-12 form-signin-heading">Reset Password</h2>
       </div>
        <div class="row">

        <div class="col-md-6">

        <div class="row">


            



           <div class="col-md-12">
            <div class="<?php  if(form_error('current_password')) { echo $error_class; } else{ echo $error_less_class;} ?>"> 
                <input type="password" name="current_password" value="" id="current_password" class="form-control first" placeholder="Current Password" autocomplete="off">
                <span class="error-msg"><?php echo form_error('current_password'); ?></span>
            </div>
        </div>
         <div class="col-md-12">
            <div class="<?php  if(form_error('new_password')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                <input type="password" name="new_password" value="" id="password" class="form-control password first" placeholder="New Password" autocomplete="off">
                <span class="error-msg"><?php echo form_error('new_password'); ?></span>
            </div>

             <div class="progress progress-striped active">
          <div id="jak_pstrength" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
        </div>
        </div>
         <div class="col-md-12">
           <div class="<?php  if(form_error('confirm_password')) { echo $error_class; } else{ echo $error_less_class;} ?>">
              <input type="password" name="confirm_password" class="form-control last" placeholder="Confirm New Password" autocomplete="off">

                  <span class="error-msg"><?php echo form_error('confirm_password'); ?></span>
            </div>
        </div>

         
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Save Password</button>
        </div>

        </div>

       

      <?php echo form_close(); ?>
      
      <hr>
      </div>
      </div>
      </div>
</div>
    </div> <!-- /container -->
    <div id="push"></div>
    <article class="rs-content-wrapper" >
    
    <!-- get the function -->
    
    
   <script type="text/javascript">
    $(document).ready(function(){

        $("#password").keyup(function() {
          //alert("here");
          passwordStrength($(this).val());
        });
    });
    </script>