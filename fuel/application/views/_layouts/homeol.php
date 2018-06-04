<?php $this->load->view('_blocks/home_header');
$pagetitle= fuel_var('page_title');
//echo fuel_block('home banner'); 
$error_message =$this->session->flashdata('error_message');
if($error_message){
echo '<div class="alert alert-danger">';
echo $error_message;
echo '</div>';
}
?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="../../../ss360/assets/js/jquery.parallax-1.1.3.js"></script>
<script type="text/javascript" src="../../../ss360/assets/js/jquery.localscroll-1.2.7-min.js"></script>
<script type="text/javascript" src="../../../ss360/assets/js/jquery.scrollTo-1.4.2-min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#nav').localScroll(800);
	
	//.parallax(xPosition, speedFactor, outerHeight) options:
	//xPosition - Horizontal position of the element
	//inertia - speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling
	//outerHeight (true/false) - Whether or not jQuery should use it's outerHeight option to determine when a section is in the viewport
	$('#banner').parallax("80%", 0.1);
	$('.mobile').parallax("80%", 1.2);
	$('.mug').parallax("80%", 1.4);
	$('.tab').parallax("50%", 1.5);
	$('.object-notebook').parallax("0%", 1.5);/*$('.laptop').parallax("90%", 0.5);
	$('.play').parallax("90%", 0.5);*/
	
	
	
	
	

})





</script>

<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../../../ss360/assets/js/bootstrap.min.js"></script>
<header id="banner" >

<div class="container">
    <div class="row">
      <div class="content">
        <h1>Like having an extra hour in your day</h1>
        <p>Imagine; an army of personal Business Development Research Assistants, all working to connect
          you with your next client. And all for <span>$10 an hour</span>.</p>
        <button class="btn-get-start">Get started</button>
      
         
        
       
      </div>
    </div>
  </div>
 

  <div class="tab span3 wow  bounceInLeft"></div>
  <div class="mobile span3 wow bounceInLeft" data-speed="30"></div>
  <div class="laptop wow span3 wow flipInX center ">     <a href="#" data-toggle="modal" data-target=".bs-example-modal-lg"><button class="play"></button></a></div>
        <div class="mug span3 wow bounceInRight" data-speed="15"></div>
<div class="object-notebook span3 wow bounceInRight pull-right"></div>
</header>

	 <div id="banner-mob" >
<div class="container-mob">
    <div class="row">
      <div class="content-mob">
        <h1>Like having an extra hour in your day</h1>
        <p>Imagine; an army of personal Business Development Research Assistants, all working to connect
          you with your next client. And all for <span>$10 an hour</span>.</p>
        <button class="btn-get-start-mob">Get started</button>
      
         
         </div>
       
      </div>
    </div>
    
    </div>
    
<div class="main-info">
  <div class="container">
    <h1 class="text-center"> <span>We build your business awareness,</span> one connection at a time.</h1>
  </div>
</div>
<section class="features" >
  <div class="container">
    <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12 text-center"><div data-wow-delay="0.5s" class="features-block span3 wow bounceInLeft" >  <div class="increase-sale"></div>
          <h1>INCREASE YOUR SALES</h1>
          <div class="sep"><img src="../../ss/assets/images/seprator.png" alt="">
            <p>Your SS360 Business
              Development Assistant helps
            you leverage your referral base</p>
          </div>
        </div> </div>
      <div class="col-md-4 col-sm-6 col-xs-12 text-center">  <div data-wow-delay="0.5s" class="features-block span3 wow bounceInUp center">  <div class="notebook"></div>
          <h1>RIGHT TOOLS, MEET RIGHT JOB</h1>
          <div class="sep"><img src="./../ss/assets/images/seprator.png"  alt=""/>
            <p>Who do you know? Who do <u>they</u>
know? Get connected with the 
highest-quality referreals in your network</p>
          </div>
        </div> </div>
      <div class="col-md-4 col-sm-6 col-xs-12 text-center">  <div data-wow-delay="0.5s" class="features-block span3 wow bounceInRight">
      <div class="report"></div>
          <h1>DATA-RICH REPORTS</h1>
          <div class="sep"><img src="./../ss/assets/images/seprator.png"  alt=""/>
            <p>Don&rsquo;t go in cold; get detailed
reports on your referrals and contacts
that give <u>you</u> the edge</p>
          </div>
        </div>
 </div>
    </div>
    <div class="feature-thumb-section"> <!--<?php echo fuel_block('feature icons'); ?>--> 
  
    
    
    <div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12 text-center span3 wow bounceInRight center" data-wow-delay="0.4s"><a href="#" class="hvr-push "><img class="img-responsive feature-thumb" src="../ss/assets/images/feature-category1.png "  alt=""/></a></div>
     <div class="col-md-4 col-sm-6 col-xs-12 text-center span3 wow bounceInRight center" data-wow-delay="0.8s"><a href="#" class="hvr-push"><img class="img-responsive feature-thumb" src="../ss/assets/images/proactive.png"  alt=""/></a></div>
      <div class="col-md-4 col-sm-6 col-xs-12 text-center span3 wow bounceInRight center" data-wow-delay="1.2s"><a href="#" class="hvr-push"><img class="img-responsive feature-thumb" src="../ss/assets/images/feature-alumini.png"  alt=""/></a></div>
     <div class="col-md-4 col-sm-6 col-xs-12 text-center span3 wow bounceInRight center" data-wow-delay="0.4s"><a href="#" class="hvr-push"><img class="img-responsive feature-thumb" src="../ss/assets/images/social-media-work.png"  alt=""/></a></div>
     <div class="col-md-4 col-sm-6 col-xs-12 text-center span3 wow bounceInRight center" data-wow-delay="0.8s"><a href="#" class="hvr-push"><img class="img-responsive feature-thumb" src="../ss/assets/images/dictate.png" width="297" height="112" alt=""/></a></div>
      <div class="col-md-4 col-sm-6 col-xs-12 text-center span3 wow bounceInRight center" data-wow-delay="1.2s"><a href="#" class="hvr-push"><img class="img-responsive feature-thumb" src="../ss/assets/images/custom.png"  alt=""/></a></div>
    </div>
    </div>
    
  </div>
</section>
<section class="feature-bg">
  <div class="container">
    <div class="row">
      <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12 ">
        <h1 class="span3 wow bounceInLeft" data-wow-delay="0.2s">Increase your <span>sales </span>by leveraging your <span>referral</span> base.</h1>
        <p class="span3 wow bounceInLeft" data-wow-delay="0.7s">Be more prepared when you meet with prospects. Your sales Support 360 Business Development Assistant will help to Drive Your Calender.  We are the "Extra Hour a Day you don't have."</p>
        <button class="know-more span3 wow bounceInLeft" data-wow-delay="1s">Know More</button>
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
        <div class="item active">
          <div class="row">
            <div class="col-xs-12">
              <div class="thumbnail adjust1">
                <div class="col-md-2 col-sm-12 col-xs-12"> <img class="media-object img-circle img-responsive" src="../../ss/assets/images/testimonial-man.png" width="144" height="112" alt=""/> </div>
                <div class="col-md-10 col-sm-12 col-xs-12">
                  <div class="caption">
                    <p class="text-info lead adjust2"><blockquote>SalesSupport360 delivers for me! I’m a big believer in their referral generation services. Not only do they prepare data rich Names to Feed reports but they handle all my LinkedIn activities so I know my connections are always up to date. I also leverage their ability to data mine, research, and generate new individual and institutional leads for me to call on.</blockquote></p>
                    <small><span class="authur"> <strong>James Lynch DiNardo, ChFC</strong> Wealth Management Advisor</span></small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="row">
            <div class="col-xs-12">
              <div class="thumbnail adjust1">
                <div class="col-md-2 col-sm-12 col-xs-12"> <img class="media-object img-circle img-responsive" src="../../ss/assets/images/testimonial-man.png" width="144" height="112" alt=""/> </div>
                <div class="col-md-10 col-sm-12 col-xs-12">
                  <div class="caption">
                    <p class="text-info lead adjust2"><blockquote> SalesSupport360 delivers for me! I’m a big believer in their referral generation services. Not only do they prepare data rich Names to Feed reports but they handle all my LinkedIn activities so I know my connections are always up to date. I also leverage their ability to data mine, research, and generate new individual and institutional leads for me to call on.</blockquote></p>
                    <small><span class="authur"> <strong>James Lynch DiNardo, ChFC</strong> Wealth Management Advisor</span></small>
                  
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="row">
            <div class="col-xs-12">
              <div class="thumbnail adjust1">
                <div class="col-md-2 col-sm-12 col-xs-12 "> <img class="media-object img-circle img-responsive " src="../../ss/assets/images/testimonial-man.png" width="144" height="112" alt=""/> </div>
                <div class="col-md-10 col-sm-12 col-xs-12">
                  <div class="caption">
                    <p class="text-info lead adjust2"> <blockquote>SalesSupport360 delivers for me! I’m a big believer in their referral generation services. Not only do they prepare data rich Names to Feed reports but they handle all my LinkedIn activities so I know my connections are always up to date. I also leverage their ability to data mine, research, and generate new individual and institutional leads for me to call on.</blockquote></p>
                    <small><span class="authur"> <strong>James Lynch DiNardo, ChFC</strong> Wealth Management Advisor</span></small>
                 
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
      <!-- Controls --> <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev"> <div class="left-arrow"></div> </a> <a class="right carousel-control" href="#carousel-example-generic" data-slide="next"> <div class="right-arrow"></div> </a>
    </div>
  </div>
</section>
<section class="quote quote-last text-center " > <div class="quote-inner">
    <div data-wow-iteration="2" class="left span3 wow swing center" >
        <h1>See how happy these people are?</h1>
        <h2><b>Be one of them.</b></h2>
    </div>
    <div  data-wow-delay="0.2s" class="right span3 wow bounceInDown center" style="visibility: visible; animation-delay: 0.5s; animation-name: bounceInDown;"> <img class="arrow" src="../ss/assets/images/arrow.png" alt=""></div>
<button type="button" class="btn-get-start-bottom">Get started</button>
</div> </section>
<!--<script src="../ss/assets/js/jquery.scrollTo-1.4.2-min.js"></script>

<script src="../ss/assets/js/parallax.js"></script>

<script src="../ss/assets/js/common.js"></script>
-->

<div class="modal fade bs-example-modal-lg modal-video" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-lg" id="myModal">
  
      <div class="modal-header">
        <button type="button" class="close btn" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><img src="../ss/assets/images/close-button.png" width="32" height="32"></span></button>
      </div>
      
    <div class="modal-content">
      
        <video width="100%"  controls autoplay>
          <source src="../ss/assets/videos/SalesSupport360 - Your Quest To Close Guide-SD.mp4" type="video/mp4">
         
       </video>
    </div>
    
  </div>
</div>
<script src="../ss/assets/js/wow.min.js"></script>

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
