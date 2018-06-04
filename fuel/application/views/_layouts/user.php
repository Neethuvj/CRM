<?php $this->load->view('_blocks/profile_header');
$pagetitle= fuel_var('page_title');
?>
		<div class="container admin-panel">
 <div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12 text-center"><button class="admin-block-btn">
    <div class="btn-left">order report</div><div class="btn-right"></div>
    
    </button></div>
    <div class="col-md-4 col-sm-6 col-xs-12 text-center"><!-- <button class="admin-block-btn"> -->
    <div class="btn-edit"><a href="<?php echo site_url('user/editprofile');?>">Edit Profile </a></div><div class="btn-right"></div>
    
   <!--  </button> --></div>
    <div class="col-md-4 col-sm-6 col-xs-12 text-center"><button class="admin-block-btn">
    <div class="btn-password"><a href="<?php echo site_url('user/change-password');?>">Change<br/>Password</a></div><div class="btn-right"></div>
    
    </button></div>
    </div></div>

<?php $this->load->view('_blocks/footer')?>
