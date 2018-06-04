<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>
		<?php 
		$this->load->library('session');
			if (!empty($is_blog)) :
				echo $CI->fuel->blog->page_title($page_title, ' : ', 'right');
			else:
				echo fuel_var('page_title', '');
			endif;
		?>
</title>
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/modern-business.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/animate.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>assets/css/hover.css" rel="stylesheet" type="text/css" />

<!--<script type="text/javascript" src="<?php //echo base_url();?>assets/js/parallex-jquery.min"></script>
<script type="text/javascript" src="<?php //echo base_url();?>assets/js/video-jquery.min"></script>-->

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.parallax-1.1.3.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.localscroll-1.2.7-min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.scrollTo-1.4.2-min.js"></script>
<script type="text/javascript">
//$(document).ready(function(){
	//$('#nav').localScroll(800);
	
	//.parallax(xPosition, speedFactor, outerHeight) options:
	//xPosition - Horizontal position of the element
	//inertia - speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling
	//outerHeight (true/false) - Whether or not jQuery should use it's outerHeight option to determine when a section is in the viewport
	//$('#banner').parallax("80%", 0.1);
	//$('.mobile').parallax("80%", 1.2);
	//$('.mug').parallax("80%", 1.4);
	//$('.tab').parallax("50%", 1.5);
	//$('.object-notebook').parallax("0%", 1.5);/*$('.laptop').parallax("90%", 0.5);
	//$('.play').parallax("90%", 0.5);*/
//});
</script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url();?>assets/js/bootstrap.min.js"></script>
<!--new-->
<!--new-->

</head>

<body>
	

  <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-inverse-home navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle navbar-toggle-home" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/logo.png" alt=""></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav ">
                     <?php $topnav= $CI->fuel->navigation->data();
                        
                        foreach($topnav as $top){
                        if(isset($top['children']) && count($top['children']) > 0){
                          
                            ?>
                        <li class="dropdown"><a href="<?php echo base_url().$top['location']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $top['label']; ?> <b class="caret"></b></a> 
                            <ul class="dropdown-menu">
                            <?php foreach($top['children'] as $subtop){ ?>
                         <li><a href="<?php echo base_url().$subtop['location']; ?>" ><?php echo $subtop['label']; ?></a> </li>       
                            <?php } echo "</ul></li>"; } else {?>
                          <li>
                                <a href="<?php echo base_url().$top['location']; ?>"><?php echo $top['label']; ?></a>
                            </li>  

                       <?php  }} ?>
                       
                     
                </ul>
                <ul class="nav navbar-nav navbar-right">
          			<li><a class="log-in" href="#" data-toggle="modal" data-target="#myModal12">Log In</a></li>
          			<li><a class="Sign-up" href="<?php echo base_url(); ?>signup1">Sign Up</a></li>
        		</ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    
