<?php $this->load->view('_blocks/home_header');
$pagetitle= fuel_var('page_title');
echo fuel_block('home banner'); 
$error_message =$this->session->flashdata('error_message');
if($error_message){
echo '<div class="alert alert-danger">';
echo $error_message;
echo '</div>';
}
?>

<section class="features" >
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12 text-center"> <?php echo fuel_block('feature block left'); ?> </div>
      <div class="col-md-4 col-sm-6 col-xs-12 text-center"> <?php echo fuel_block('feature block middle'); ?> </div>
      <div class="col-md-4 col-sm-6 col-xs-12 text-center"> <?php echo fuel_block('feature block right'); ?> </div>
    </div>
    <div class="feature-thumb-section"> <?php echo fuel_block('feature icons'); ?> </div>
  </div>
</section>
<section class="feature-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 ">
      <?php echo fuel_block('feature homebg'); ?>
      </div>
    </div>
  </div>
</section>


<section class="quote text-center">
  <div class="container contentt">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        <li data-target="#carousel-example-generic" data-slide-to="3"></li>
      </ol>
      <!-- Wrapper for slides -->
      <div class="carousel-inner">
     
       <?php echo fuel_block('home slider'); ?>
       </div>
        
      </div>
      <!-- Controls --> <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"> <div class="left-arrow"></div> </a> <a class="right carousel-control" href="#carousel-example-generic" data-slide="next"> <div class="right-arrow"></div> </a>
    </div>
  </div>
</section>
       
<section class="quote quote-last text-center" > <?php echo fuel_block('start here'); ?> </section>

  
<!-- /.container -->
<?php $this->load->view('_blocks/footer')?>
