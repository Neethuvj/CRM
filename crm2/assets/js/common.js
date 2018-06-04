// // JavaScript Document
// var nice = false;
// $(document).ready(function() {  
//     //nice = $("html").niceScroll();
// });
//   $(document).ready(function() {    
// 	$(".meeting-table").niceScroll();
//   });
  
//   		(function($){
// 			$(window).on("load",function(){
				
// 				$("a[rel='load-content']").click(function(e){
// 					e.preventDefault();
// 					var url=$(this).attr("href");
// 					$.get(url,function(data){
// 						$(".scrollcustom .mCSB_container").append(data); //load new content inside .mCSB_container
// 						//scroll-to appended content 
// 						$(".scrollcustom").mCustomScrollbar("scrollTo","h2:last");
// 					});
// 				});
				
// 				$(".scrollcustom").delegate("a[href='top']","click",function(e){
// 					e.preventDefault();
// 					$(".scrollcustom").mCustomScrollbar("scrollTo",$(this).attr("href"));
// 				});
				
// 			});
// 		})(jQuery);
  
  