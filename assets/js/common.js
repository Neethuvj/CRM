$( document ).ready(function() {

	$('.password-reset-form #submit').click(function(event){
       	data = $('#password').val();
        var len = data.length;
        if(len < 1) {
            $('.password-reset .message').html('Password cannot be blank');
            event.preventDefault();
        }
        if($('#password').val() != $('#password_confirm').val()) {
        	$('.password-reset .message').html("Password and Confirm Password don't match");
            event.preventDefault();
        }
    });
	
    $('#signup').submit(function(e){
   		if($('#signup #username').attr('data-user') == 1){
   			e.preventDefault();
   		}
		if($('#signup #email').attr('email-user') == 1){
   			e.preventDefault();
   		}
   		localStorage.setItem('signOk', true);
    });
});

function checkCusomerExits(customer){
            $.ajax({
		                type: "POST",
		                data: {customerName: customer},
		                url: 'signup/checkusername/',
		                success: function(count)
		                { 
		                	if(count > 0){
		                		$('#signup .UserNameValid').html('User Already Exists');
		                		$('#signup #username').attr('data-user','1');
		                	} else {
		                		$('#signup .UserNameValid').html('');
		                		$('#signup #username').attr('data-user','0');
		                	}
		               	}
		          });
        }

function checkemailCusomerExits(customers){
            $.ajax({
		                type: "POST",
		                data: {customeremail: customers},
		                url: 'signup/checkemail/',
		                success: function(count)
		                { 
		                	if(count > 0){
		                		$('#signup .email').html('Email Already Exists');
		                		$('#signup #email').attr('email-user','1');
		                	} else {
		                		$('#signup .email').html('');
		                		$('#signup #email').attr('email-user','0');
		                	}
		               	}
		          });
            
        }        

$(function(){$(".openpanel").on("click",function(){$("#panel3").collapse("show")}),$(".closepanel").on("click",function(){$("#panel3").collapse("hide")}),$("#accordion").on("show.bs.collapse",function(){$("#accordion .in").collapse("hide")}),$(this).find('.career-view[aria-expanded="true"]').html("Close"),$("input").keypress(function(i){i.charCode?i.charCode:i.which})}),$(document).ready(function(){function i(){var i=$(window).height();$(".slider, .intro, .video-wrapper").css("min-height",i)}function e(){var i=$("body").find('[data-toggle="modal"]');i.click(function(){var i=$(this).data("target"),e=$(this).attr("data-theVideo"),n=e+"?autoplay=1&loop=1&playlist=bqy-ypSR7bI";$(i+" iframe").attr("src",n),$(i+" button.close").click(function(){$(i+" iframe").attr("src",e)})})}$(".loader").ClassyLoader({speed:10,diameter:35,fontSize:"18px",fontWeight:"bold",fontFamily:"Agenda Medium",boxShadow:"10px 10px 10px #000",fontColor:"rgb(0,0,0)",lineColor:"rgb(249,105,20)",percentage:100,lineWidth:10,start:"top",remainingLineColor:"rgb(0,41,109)"}),setTimeout(function(){$("#preloader").fadeIn("fast").fadeOut()},4e3),setTimeout(function(){$(".loader-block img").fadeIn("slow")},3e3),i(),$(window).resize(function(){i()}),$("#accordion ul li").on("click",function(){setTimeout(function(){var i=$(this).find("a.wrapper").attr("class");alert(i),"true"==i&&$(this).find(".text-right span").text("close>>"),"false"==i&&$(this).find(".text-right span").text("view>>")},10)}),$(".object-first").parallax("0%",.4),$(".bg").parallax("0%",.4),$(".obj-2.bg").parallax("0%",.4),$(".obj-3.bg").parallax("100%",.4);var n=$(".top-menu").offset().top,a=function(){var i=$(window).scrollTop();i>n?$(".top-menu").addClass("sticky").animate("linear"):$(".top-menu").removeClass("sticky").animate("linear")};a(),$(window).scroll(function(){a()}),(new WOW).init(),$(".more-btn a").click(function(){var i=$("#customization-content").offset().top-80;$("html, body").animate({scrollTop:i},2e3);var e=$(window).height();$("#customization-content").css("min-height",e)}),e(),$("#showdiv1").click(function(){$("#hiddendiv1").show(),$("#hiddendiv2").hide(),$("#hiddendiv3").hide()}),$("#showdiv2").click(function(){$("#hiddendiv2").show(),$("#hiddendiv1").hide(),$("#hiddendiv3").hide()}),$("#showdiv3").click(function(){$("#hiddendiv3").show(),$("#hiddendiv1").hide(),$("#hiddendiv2").hide()}),$(".baw-months").is(":visible"),$(".baw-year a.first").hover(function(){$(this).find("span").remove(),setTimeout(function(){$(".baw-year").each(function(){if("none"==$(this).find(".baw-months").css("display")){var i=$(this).find("a.first").html();i.indexOf("View")<=0&&$(this).find("a.first").html(i+'<span class="archive_more">View...</span>')}})},500)}),$(window).width()<767&&($(".wow").removeClass("wow"),$(".animated-header").removeClass("animated-header"),$(".animated").removeClass("animated"),$(".magazineTitolo").removeClass("magazineTitolo"),$(".magazineImmagineRight").removeClass("magazineImmagineRight"),$(".magazineImmagineLeft").removeClass("magazineImmagineLeft"))});

