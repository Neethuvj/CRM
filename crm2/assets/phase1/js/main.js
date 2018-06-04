function is_touch_device() {
  return !!('ontouchstart' in window);
}

$(document).ready(function() { 



  // $(".activate_user #selected_plan_id_3").on("click",function(e){
  //   var old_plan_id = $(".activate_user .old_plan_id").val();
  //   if(old_plan_id !== 3){

  //    	$(".activate_user .cc-details").hide();
  //   }

  // });
    /* If mobile browser, prevent click on parent nav item from redirecting to URL */
  if(!is_touch_device()) { 
		

	// 	$("#bs-example-navbar-collapse-1 ul.nav li.dropdown").hover(function(){
	// 		$(this).find("ul.dropdown-menu").slideDown("slow");
	// 	}, function(){
	// 		$(this).find("ul.dropdown-menu").slideUp("slow");
	// 	})

		// $("#bs-example-navbar-collapse-1 ul.nav li.dropdown").hover(function(){
		// 	$(this).find("ul.dropdown-menu").slideDown("slow");
		// }, function(){
		// 	$(this).find("ul.dropdown-menu").slideUp("slow");
		// })

	}

		 $(".form-control").each(function(e){
 		 	$(this).keyup(function(e) {
 		 		$(this).parent().removeClass("has-error");
 		 		$(this).parent().find(".error-msg").html(" ");

 		 	});
 		 });
 		 if($(".company-name-open").prop('checked')==false){
 		 	$(".company-name input").val(' ');
            $(".company-name").hide();
 		 }
		
	$(".company-name-open").change(function() {
		 if($(this).is(":checked")) {
		$(".company-name").show();
			
 		 }
 		 else{

 		 	$(".company-name").parent().removeClass("has-error");
				$(".company-name").parent().find(".error-msg").html(" ");
 		 	$(".company-name input").val(' ');
$(".company-name").hide();

 		 }

 
});

	$(".copy-billing-address").change(function() {
		 if($(this).is(":checked")) {
		
			if($(".address").val() !== ""){
				$(".billing-address").val($(".address").val());
				$(".billing-address").parent().removeClass("has-error");
				$(".billing-address").parent().find(".error-msg").html(" ");
			}

			if($(".state").val() !== ""){
				$(".billing-state").val($(".state").val())
				$(".billing-state").parent().removeClass("has-error");
				$(".billing-state").parent().find(".error-msg").html(" ");
			}

			if($(".city").val() !== ""){
				$(".billing-city").val($(".city").val());
				$(".billing-city").parent().removeClass("has-error");
				$(".billing-city").parent().find(".error-msg").html(" ");
			}
			if($(".zip").val() !== ""){
				$(".billing-zip").val($(".zip").val());

				$(".billing-zip").parent().removeClass("has-error");
				$(".billing-zip").parent().find(".error-msg").html(" ");
			}
 		 }
 		 else{
		$(".billing-address").val(" ");
			$(".billing-state").val(" ");
			$(".billing-city").val(" ");
			$(".billing-zip").val(" ");

 		 }

 
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

	$('#forget-pass-email').on('hidden.bs.modal', function (e) {
		  // do something...

		   $(".form-control").each(function(e){
		        $(this).val("");
		        $("#forget-pass-email-form .dynamic-message").html(" ");
		        $(this).parent().removeClass("has-error");
		        $(this).parent().find(".error-msg").html(" ");


		     });
		})
		
		$('.forgotpass').click(function(e){
		e.preventDefault();
		$('#myModal12').modal('hide');
		$('#forget-pass-email').modal('show');
		
	})
	  // $(".register-page .rs-timepicker").datetimepicker({
   //    pickDate: false
   //  });
});	