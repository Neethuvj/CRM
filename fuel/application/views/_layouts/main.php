<?php 
$this->load->library('session');
$this->load->view('_blocks/header');
$pagetitle= fuel_var('page_title');
//echo fuel_block('prospecting360'); 
//typeof($pagetitle);
$pagetitle = strip_tags(str_replace(array("\r", "\n"), "", $pagetitle));
echo fuel_block($pagetitle);
$success_message =$this->session->flashdata('success_message');
if($success_message){
echo '<div class="alert alert-success">';
echo $success_message;
echo '</div>';
}
$error_message =$this->session->flashdata('error_message');
if($error_message){
echo '<div class="alert alert-danger">';
echo $error_message;
echo '</div>';

}
if($pagetitle == "Contact" || $pagetitle == "getstarted"){ ?>
<div class="">
	<section id="" style="padding-right: 0;margin-right: -17px; overflow-x: hidden;">

	<?php echo fuel_var('body', 'This is a default layout. To change this layout go to the fuel/application/views/_layouts/main.php file.'); ?>
	 </section>
</div>
<?php }else{
?>
<div class="container products-detail <?php echo $pagetitle; ?>">

	<section id="main_inner">
		<?php echo fuel_var('body', 'This is a default layout. To change this layout go to the fuel/application/views/_layouts/main.php file.'); ?>
	</section>
</div>	
<?php } $this->load->view('_blocks/footer')?>
