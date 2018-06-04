<?php 
$this->load->library('session');
$this->load->view('_blocks/home_header');
$pagetitle= fuel_var('page_title');
$error_message =$this->session->flashdata('error_message');
if($error_message){
echo '<div class="alert alert-danger">';
echo $error_message;
echo '</div>';
}
$success_message =$this->session->flashdata('success_message');
if($success_message){
echo '<div class="alert alert-success">';
echo $success_message;
echo '</div>';
}
echo fuel_block('home banner'); 
?>

<section class="features" >
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12 text-center"> <?php echo fuel_block('feature block left'); ?> </div>
      <div class="col-md-4 col-sm-6 col-xs-12 text-center"> <?php echo fuel_block('feature block middle'); ?> </div>
      <div class="col-md-4 col-sm-6 col-xs-12 text-center"> <?php echo fuel_block('feature block right'); ?> </div>
    </div>
    <!-- <div class="feature-thumb-section">  </div> -->
  </div>
</section>
<?php echo fuel_block('feature icons'); ?>
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

<script src="../ss360/assets/js/wow.min.js"></script>

<script>
   new WOW().init();
</script>
<script>
		(function() {
			[].slice.call(document.querySelectorAll('.menu')).forEach(function(menu) {
				var menuItems = menu.querySelectorAll('.menu__link'),
					setCurrent = function(ev) {
						ev.preventDefault();

						var item = ev.target.parentNode; // li

						// return if already current
						if (classie.has(item, 'menu__item--current')) {
							return false;
						}
						// remove current
						classie.remove(menu.querySelector('.menu__item--current'), 'menu__item--current');
						// set current
						classie.add(item, 'menu__item--current');
					};

				[].slice.call(menuItems).forEach(function(el) {
					el.addEventListener('click', setCurrent);
				});
			});

			[].slice.call(document.querySelectorAll('.link-copy')).forEach(function(link) {
				link.setAttribute('data-clipboard-text', location.protocol + '//' + location.host + location.pathname + '#' + link.parentNode.id);
				new Clipboard(link);
				link.addEventListener('click', function() {
					classie.add(link, 'link-copy--animate');
					setTimeout(function() {
						classie.remove(link, 'link-copy--animate');
					}, 300);
				});
			});
		})(window);
		
		</script>

<!-- /.container -->
<?php $this->load->view('_blocks/footer')?>
