function is_touch_device() {
  return !!('ontouchstart' in window);
}

$(document).ready(function() { 

    /* If mobile browser, prevent click on parent nav item from redirecting to URL */
    if(!is_touch_device()) { 
		
		// $("#bs-example-navbar-collapse-1 ul.nav li.dropdown").hover(function(){
		// 	$(this).find("ul.dropdown-menu").slideDown("slow");
		// }, function(){
		// 	$(this).find("ul.dropdown-menu").slideUp("slow");
		// })
	}


$('.bs-example-modal-lg.modal-video').on('shown.bs.modal', function() {
  
  $('.modal-video #myModal video')[0].play();
});


 $(".form-control").each(function(e){
 		 	$(this).keyup(function(e) {
 		 		$("#header_login_form .dynamic-message").html(" ");
 		 		$(this).parent().removeClass("has-error");
 		 		$(this).parent().find(".error-msg").html(" ");

 		 	});
 		 });



	$("#header_login_form .btn").on("click", function(e){
		e.preventDefault();

		var form_variable = $('form#header_login_form').serialize();
		var form_submit_url = $("#header_login_form .phase2_url").val();
		var phase2_url=
		$.ajax({
			type: "POST",
			url: form_submit_url,
			data:  form_variable,
	
			success: function(data_response)
           	{
               
               

               	if(data_response.indexOf("ok") > -1){
               			$("#header_login_form").submit();
               	}
               	else{


               		$("#header_login_form .form-group").each(function(e){
               			$(this).addClass("has-error");

               		});
               		$("#header_login_form .dynamic-message").html(" ");
               		
               		if(data_response.indexOf("username_password_emtpy") > -1){
$("#header_login_form .dynamic-message").append("<div class='alert alert-danger'>Username and Password is required</div>");
              
               			
               		}
               		else{
               			$("#header_login_form .dynamic-message").append("<div class='alert alert-danger'>Username or password is wrong</div>");
               	
               		}
               		}
           	}

		});

		
	});
	
	$('.forgotpass').click(function(e){
		e.preventDefault();
		$('#myModal12').modal('hide');
		$('#forget-pass-email').modal('show');
		
	})
	
// Forget Password popup email validation and send reset link email
	
	$("#forget-pass-email-form .btn").on("click", function(e){
		e.preventDefault();

		var form_variable = $('form#forget-pass-email-form').serialize();
		var form_submit_url = $("#forget-pass-email-form .phase2_url").val();
		var phase2_url=
		$.ajax({
			type: "POST",
			url: form_submit_url,
			data:  form_variable,
			
			success: function(data_response)
           	{
				$('.loader').css("display", "block");
				var form_action_url = $("#forget-pass-email-form .form_action_url").val();
               	if(data_response.indexOf("ok") > -1){
               			//$("#forget-pass-email-form").submit();
               		$.ajax({
            			type: "POST",
            			url: form_action_url,
            			data:  form_variable,
            			
            			success: function(response)
                       	{
            				$('.loader').css("display", "none");
            				$("#forget-pass-email-form .dynamic-message").html(" ");
            				if(response.indexOf("success") > -1){
            					$("#forget-pass-email-form .dynamic-message").append("<div class='alert alert-success'>Please check your email to reset password.</div>");
            				}else if(response.indexOf("failed") > -1){
            					$("#forget-pass-email-form .dynamic-message").append("<div class='alert alert-danger'>Something went wrong while sending email.</div>");
            				}
                       	
                       	}
               		})
               	}
               	else{

               		$('.loader').css("display", "none");
               		$("#forget-pass-email-form .form-group").each(function(e){
               			$(this).addClass("has-error");

               		});
               		$("#forget-pass-email-form .dynamic-message").html(" ");
               		
               		if(data_response.indexOf("username_emtpy") > -1){
               			$("#forget-pass-email-form .dynamic-message").append("<div class='alert alert-danger'>Username is required</div>");
              
               			
               		}
               		else{
               			$("#forget-pass-email-form .dynamic-message").append("<div class='alert alert-danger'>Username is wrong</div>");
               	
               		}
               		}
           	}

		});

		
	});

  $('#open_supersubmenu').mouseover(function(e){
    e.preventDefault();
    //$('ul.dropdown-menu').show();
    $('.supersubmenu').toggle();
  })
  $('.supersubmenu').mouseleave(function(e){
    e.preventDefault();
    $('.supersubmenu').hide();
  })
  $('li.sublist-item').mouseover(function(e){
    e.preventDefault();
    $('.supersubmenu').hide();
  })
});	