<!-- Footer -->
      <div class="dlank-area"></div>
  <div id="footer">
    <div class="footer-top">
      <div class="container">
  		<div class="row">
                <div class="col-lg-12">
                	<div class="footer-questions">
                    <p>Questions? Visit the Support Center, or get in touch:</p>
                    </div>
                    
                	<div class="footer-contact">
                    <ul class="footer-contact-list">
                    	<li><a><img src="<?php echo base_url(); ?>assets/images/phone.png" alt="">1-888-428-9482</a></li>
                    	<li><a><img src="<?php echo base_url(); ?>assets/images/mail.png" alt="">support@ss360.com</a></li>
                    	<li><a><img src="<?php echo base_url(); ?>assets/images/chat.png" alt="">Live Chat <br><span>(offline)</span></a></li>
                    </ul>
                    </div>


                </div>
        </div>
      </div>
    </div>
    <div class="footer-main">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-12 footer-main-list">
                <ul class="footer-menu">
                   <h1>ABOUT US</h1>
                 <?php echo fuel_nav(array('group_id' => 3))?>
                </ul>
                <ul class="footer-menu">
                   <h1>WHY SS360</h1>
                   <?php echo fuel_nav(array('group_id' => 4))?>
                </ul>
                <ul class="footer-menu">
                   <h1>SERVICES</h1>
                   <?php echo fuel_nav(array('group_id' => 5))?>
                </ul>
                <ul class="footer-menu">
                   <h1>RESOURCES</h1>
                   <?php echo fuel_nav(array('group_id' => 6))?>
                </ul>
                <ul class="footer-menu">
                   <h1>PRICING</h1>
                   <li></li>
                </ul>
                <ul class="footer-menu">
                   <h1>FAQS</h1>
                   <li></li>
                </ul>
                </div>
            </div> 
        	<div class="row">
            	<div class="footer-social-main">
            		<div class="col-md-3 col-md-3 col-sm-4">
                    <ul class="social-list">
                    	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/twitter.png" alt=""></a></li>
                    	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/facebook.png" alt=""></a></li>
                    	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/plus.png" alt=""></a></li>
                    	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/linkedin.png" alt=""></a></li>
                    </ul>
                	</div>
            		<div class="col-md-6 col-md-6 col-sm-4 ss360logo">
                    <img src="<?php echo base_url();?>assets/images/ss360.png" alt="">
                    </div>
            		<div class="col-md-3 col-md-3 col-sm-4 back-to-top">
                    <a class="back-to-top-link" href="">BACK TO TOP <img src="<?php echo base_url();?>assets/images/back-to-top.png" alt=""></a>
                    </div>
                
                </div>
            </div> 
        	<div class="row footer-main-content">
            		<div class="col-md-3 col-md-3 col-sm-12 ">
                    
                        <?php echo fuel_block('Footer_left'); ?>
                        </div>
            		<div class="col-md-6 col-md-6 col-sm-12">
                    <?php echo fuel_block('Footer_middle'); ?>
                    </div>
            		<div class="col-md-3 col-md-3 col-sm-12">
                    <?php echo fuel_block('Footer_right'); ?>
                    </div>
            </div>
        	<div class="row footer-icons">
            <ul class="footer-icon-list">
            	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/app-store.png" alt=""></a></li>
            	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/play-store.png" alt=""></a></li>
            	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/mcafee.png" alt=""></a></li>
            	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/digicert.png" alt=""></a></li>
            	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/ny.png" alt=""></a></li>
            	<li><a href=""><img src="<?php echo base_url(); ?>assets/images/mixpanel.png" alt=""></a></li>
            </ul>
        </div>
    </div>
   </div>
   </div>
<div class="modal fade" id="myModal12" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog login-main" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Sign in</h4>
      </div>
      <div class="modal-body">
        <div class="login-form">
          <form action="<?php echo base_url();?>login" method="post" id="header_login_form">
            <div class="form-group">
              <label for="">Username</label>
              <input type="text" name="user_name" class="form-control" id="" placeholder="Username">
            </div>
           
            <div class="checkbox">
           
            <button type="submit" class="btn btn-default"><span>Login</span></button>
          
            <div class="clearfix"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
    <!-- jQuery -->
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.js"></script>
  
    <!-- Bootstrap Core JavaScript -->
        <script type="text/javascript" src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>


    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>

</body>

</html>
















