<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>
		<?php 
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



</head>

<body>
  <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
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
          			<li><a class="log-detail" ><span class="sign-in">signed in as</span> <span class="name">Admin</span></a></li>
          			<li><a class="Sign-out" href="<?php echo base_url(); ?>admin/logout">Sign Out</a></li>
        		</ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    
