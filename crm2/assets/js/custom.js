$(document).ready(function() { 

 // moment.tz.setDefault("America/New_York");
 /*$('.meeting-date-timepicker').click(function(e){
    e.preventDefault();
    moment.tz.add(
    'America/New_York|EST EDT|50 40|0101|1Lz50 1zb0 Op0'
);
  moment.tz.setDefault("America/New_York");
  var todayDate = moment().format('MM/D/YYYY HH:MM:00');
  console.log(todayDate);
 });*/
var dt = new Date();
//console.log(dt); // Gives Tue Mar 22 2016 09:30:00 GMT+0530 (IST)

dt.setTime(dt.getTime()+dt.getTimezoneOffset()*60*1000);

 var offset = -120; //Timezone offset for EST in minutes.
var estDate = new Date(dt.getTime() + offset*60*1000);
//console.log(estDate);

  var default_checked = $('.admin-customer-signup  input[name=plan_id_selected]:checked').val();

  if(default_checked == 3){
     $(".credit-card-info").css("display", "none");
  }

  $(".admin-customer-signup .plan_id_selected").each(function(e){
      $(this).change(function(){
          var id = "#" + $(this).attr('id');
          var checked = $(id).is(':checked');
          var selected_value = $(id).val();
           if(checked == true){
            
              $(".credit-card-info").css("display", "block");
              if(selected_value == 3){
                $(".credit-card-info").css("display", "none");

              }


           }
      });
  });

  $(".add-time-popup .btn.log-add").on("click", function(e){
    e.preventDefault();
    var form_variable = $('.add-time-popup form').serialize();
    var form_submit_url = "/task/addlog";
   // console.log(form_variable);
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
              // if(data_response == "Success"){
                  $(".analyst-edit-form").submit();

               //}
            }
      });

    //setTimeout(function(){$(".analyst-edit-form .analyst-email").trigger( "click" ); }, 5000);
     
  });





    $(".add-time-popup .btn.log-add").on("click", function(e){
    e.preventDefault();
    var form_variable = $('.add-time-popup form').serialize();
    var form_submit_url = "admin/task/addlog";
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
              // if(data_response == "Success"){
                  $(".edit").submit();

               //}
            }
      });


    //setTimeout(function(){$(".analyst-edit-form .analyst-email").trigger( "click" ); }, 5000);
     

  });



    $(".email-confirmation .save-btn").on("click", function(e){
    e.preventDefault();
    

   // var form_variable = $('.confirmation_form form').serialize();
    var form_submit_url = "/user/email_confirmation";
    $.ajax({
      type: "POST",
      url: form_submit_url,
  
      success: function(data_response)
            {
              
              $('#add-link-page').hide();
             
                location.reload(); 
               
            }
      });

  });






   $(".add-time-popup .close").on("click", function(e){
  
  

      setTimeout(function(){$(".analyst-edit-form .analyst-email").trigger( "click" ); }, 5000);
     

  });

   
   $(".add-time-popup .cancel-log").on("click", function(e){
  
   
    setTimeout(function(){$(".analyst-edit-form .analyst-email").trigger( "click" ); }, 5000);
     

  });




    $(".update-credit-card-customer .expiry-month").datepicker( {
 format: " mm", // Notice the Extra space at the beginning
    viewMode: "months", 
    minViewMode: "months"
})


$(".update-credit-card-customer .expiry-year").datepicker( {
 format: " yyyy", // Notice the Extra space at the beginning
    viewMode: "years", 
   yearRange: "-1:+25",
    minViewMode: "years"
})
 
  $("#customer" ).chosen({width: '100%'});

  $(".customer-list .filter-panel select" ).chosen({width: '100%'});

  $(".task-list select" ).chosen({width: '100%'});

  $('.member-list select').chosen({width: '100%'});
 if($(".company-name-open").prop('checked')==false){
            $(".company-name input").val('');
            $(".company-name").hide();
     }
    
  $(".company-name-open").change(function() {
     if($(this).is(":checked")) {
      $(".company-name input").val('');
    $(".company-name").show();
      
     }
     else{

      $(".company-name").parent().removeClass("has-error");
        $(".company-name").parent().find(".error-msg").html(" ");
        $(".company-name input").val('');
      $(".company-name").val(' ');

$(".company-name").hide();

     }
     });


$(".remove_upload").each(function(e){
$(this).on('click',function(e){

  e.preventDefault();
  var removed_id = $(this).parent().find('.link').attr('data-upload-id');

  $(this).parent().find(".hidden-removed").val(removed_id);
  $(this).parent().css('display', "none");
});

});


    $(".task-timepicker").datetimepicker({
        weekStart: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        format: 'HH:ii:00 P'

   });



    $(".meeting-date-timepicker").datetimepicker({
      format: 'mm/dd/yyyy HH:ii:00 P',
      startDate : estDate,
        weekStart: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
   });


    $(".only-date").datetimepicker({
      weekStart: 1,
        todayBtn:  0,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        minView: 2,
        pickTime: false,
        //showMeridian: 1,
        format: 'mm/dd/yyyy'

   });




    $(".configuration-edit-popup").on("click",function(e){

         e.preventDefault();
         
         var email_value = $(this).parent("td").parent("tr").find(".email_value").text();
         var email_id = $(this).parent("td").parent("tr").find(".email_id").text();
        $(".edit-configuration-table .popup-email").val(email_value);
        $(".edit-configuration-table .popup-id").val(email_id);
   
   
        $(".edit-configuration-table").modal("show",{backdrop: 'static', keyboard: false});
    });
/*On Click Create BDA Popup*/
 $(".BDA-Create-popup").on("click",function(e){
      //e.preventDefault();
      
        $(".BDA-Create-popup").modal("show" ,{backdrop: 'static', keyboard: false}  );
    });

  /*On Click of Save in Create BDA Model check validations*/

  $(".BDA-Create-popup .btn.save").on("click", function(e){
      e.preventDefault();
    
    var form_variable = $('.BDA-Create-popup form').serialize();
    var form_submit_url = $(".BDA-Create-popup form .validate_bda_url").val();
   // console.log(form_variable);
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
               if(data_response == "Success"){
                  $(".BDA-Create-popup form").submit();

               }
               else{
                var data_response_object =$.parseJSON(data_response);

                if(data_response_object.error_message_first_name !== ""){
                  $(".BDA-Create-popup").find(".first_name").parent().addClass("has-feedback");
                  $(".BDA-Create-popup").find(".first_name").parent().addClass("has-error");
                  $(".BDA-Create-popup").find(".first_name").parent().find('.error-msg').text(" ");
$(".BDA-Create-popup").find(".first_name").parent().find('.error-msg').append(data_response_object.error_message_first_name);
                }
                if(data_response_object.error_message_last_name !== ""){
                  $(".BDA-Create-popup").find(".last_name").parent().addClass("has-feedback");
                  $(".BDA-Create-popup").find(".last_name").parent().addClass("has-error");
                  $(".BDA-Create-popup").find(".last_name").parent().find('.error-msg').text(" ");
$(".BDA-Create-popup").find(".last_name").parent().find('.error-msg').append(data_response_object.error_message_last_name);
             
                  
                }
                if(data_response_object.error_message_email !== ""){

                       $(".BDA-Create-popup").find(".email").parent().addClass("has-feedback");
                       $(".BDA-Create-popup").find(".email").parent().addClass("has-error");
                       $(".BDA-Create-popup").find(".email").parent().find('.error-msg').text(" ");
$(".BDA-Create-popup").find(".email").parent().find('.error-msg').append(data_response_object.error_message_email);
             
                }

                 if(data_response_object.error_message_phone_number !== ""){

                       $(".BDA-Create-popup").find(".phone_number").parent().addClass("has-feedback");
                       $(".BDA-Create-popup").find(".phone_number").parent().addClass("has-error");
                       $(".BDA-Create-popup").find(".phone_number").parent().find('.error-msg').text(" ");
$(".BDA-Create-popup").find(".phone_number").parent().find('.error-msg').append(data_response_object.error_message_phone_number);
             
                }
                 if(data_response_object.error_message_password !== ""){

                       $(".BDA-Create-popup").find(".password").parent().addClass("has-feedback");
                       $(".BDA-Create-popup").find(".password").parent().addClass("has-error");
                       $(".BDA-Create-popup").find(".password").parent().find('.error-msg').text(" ");
$(".BDA-Create-popup").find(".password").parent().find('.error-msg').append(data_response_object.error_message_password);
             
                }
                 if(data_response_object.error_message_confirm_password !== ""){

                       $(".BDA-Create-popup").find(".confirm_password").parent().addClass("has-feedback");
                       $(".BDA-Create-popup").find(".confirm_password").parent().addClass("has-error");
                       $(".BDA-Create-popup").find(".confirm_password").parent().find('.error-msg').text(" ");
$(".BDA-Create-popup").find(".confirm_password").parent().find('.error-msg').append(data_response_object.error_message_confirm_password);
             
                }
               }
              
            }

    });

    
  });
/*Add referals popup*/

 $(".add-referals").on("click",function(e){
      //e.preventDefault();
      
        $(".add-referals").modal("show",{backdrop: 'static', keyboard: false});
    });

/*Email confirmation pop up for customer*/

 $(".email-confirmation").on("click",function(e){
      //e.preventDefault();
      
        $(".email-confirmation").modal("show",{backdrop: 'static', keyboard: false});
    });


/*Validation for add referrals*/

 $(".add-referals .btn.save").on("click", function(e){
    e.preventDefault();
    var err = false;

    $('#referral_form input[name^="name"]').each(function() {
        var name = $(this).val();
        if(name == ''){
          $(this).addClass("has-error"); //highlight empty field
          err = true; //set err true
        }
    });
    $('#referral_form input[name^="phone_number"]').each(function(){
        var phone_number = $(this).val();

        if(phone_number == '' ){
          $(this).addClass("has-error");  //highlight empty field
          err = true; //set err true
        }
    })

    if(err != true){ // if no error, submit form
          $(".add-referals form").submit();

    }
  });

 $('#referral_form input[type="text"]').focus(function(){
  $(this).removeClass('has-error');
 })

/*End of Validation for add teferals*/
 

  /*Create Team Members*/

    $(".create-team .btn.save").on("click", function(e){
      e.preventDefault();
    
    var form_variable = $('.create-team form').serialize();
    var form_submit_url = $(".create-team form .validate_team_url").val();
   // console.log(form_variable);
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
               if(data_response == "Success"){
                  $(".create-team form").submit();

               }
               else{
                var data_response_object =$.parseJSON(data_response);

                if(data_response_object.error_message_first_name !== ""){
                  $(".create-team").find(".first_name").parent().addClass("has-feedback");
                  $(".create-team").find(".first_name").parent().addClass("has-error");
                  $(".create-team").find(".first_name").parent().find('.error-msg').text(" ");
$(".create-team").find(".first_name").parent().find('.error-msg').append(data_response_object.error_message_first_name);
                }
                if(data_response_object.error_message_last_name !== ""){
                  $(".create-team").find(".last_name").parent().addClass("has-feedback");
                  $(".create-team").find(".last_name").parent().addClass("has-error");
                  $(".create-team").find(".last_name").parent().find('.error-msg').text(" ");
$(".create-team").find(".last_name").parent().find('.error-msg').append(data_response_object.error_message_last_name);
             
                  
                }
                if(data_response_object.error_message_email !== ""){

                       $(".create-team").find(".email").parent().addClass("has-feedback");
                       $(".create-team").find(".email").parent().addClass("has-error");
                       $(".create-team").find(".email").parent().find('.error-msg').text(" ");
$(".create-team").find(".email").parent().find('.error-msg').append(data_response_object.error_message_email);
             
                }

                 if(data_response_object.error_message_phone_number !== ""){

                       $(".create-team").find(".phone_number").parent().addClass("has-feedback");
                       $(".create-team").find(".phone_number").parent().addClass("has-error");
                       $(".create-team").find(".phone_number").parent().find('.error-msg').text(" ");
$(".create-team").find(".phone_number").parent().find('.error-msg').append(data_response_object.error_message_phone_number);
             
                }
                 if(data_response_object.error_message_password !== ""){

                       $(".create-team").find(".password").parent().addClass("has-feedback");
                       $(".create-team").find(".password").parent().addClass("has-error");
                       $(".create-team").find(".password").parent().find('.error-msg').text(" ");
$(".create-team").find(".password").parent().find('.error-msg').append(data_response_object.error_message_password);
             
                }
                 if(data_response_object.error_message_confirm_password !== ""){

                       $(".create-team").find(".confirm_password").parent().addClass("has-feedback");
                       $(".create-team").find(".confirm_password").parent().addClass("has-error");
                       $(".create-team").find(".confirm_password").parent().find('.error-msg').text(" ");
                       $(".create-team").find(".confirm_password").parent().find('.error-msg').append(data_response_object.error_message_confirm_password);
             
                }
                   if(data_response_object.error_message_street_address !== ""){

                       $(".create-team").find(".street_address").parent().addClass("has-feedback");
                       $(".create-team").find(".street_address").parent().addClass("has-error");
                       $(".create-team").find(".street_address").parent().find('.error-msg').text(" ");
                       $(".create-team").find(".street_address").parent().find('.error-msg').append(data_response_object.error_message_street_address);
             
                }
                 if(data_response_object.error_message_city !== ""){

                       $(".create-team").find(".city").parent().addClass("has-feedback");
                       $(".create-team").find(".city").parent().addClass("has-error");
                       $(".create-team").find(".city").parent().find('.error-msg').text(" ");
                       $(".create-team").find(".city").parent().find('.error-msg').append(data_response_object.error_message_city);
             
                }
                if(data_response_object.error_message_state !== ""){

                       $(".create-team").find(".state").parent().addClass("has-feedback");
                       $(".create-team").find(".state").parent().addClass("has-error");
                       $(".create-team").find(".state").parent().find('.error-msg').text(" ");
                       $(".create-team").find(".state").parent().find('.error-msg').append(data_response_object.error_message_state);
             
                }
                 if(data_response_object.error_message_zip !== ""){

                       $(".create-team").find(".zip").parent().addClass("has-feedback");
                       $(".create-team").find(".zip").parent().addClass("has-error");
                       $(".create-team").find(".zip").parent().find('.error-msg').text(" ");
                       $(".create-team").find(".zip").parent().find('.error-msg').append(data_response_object.error_message_zip);
             
                }
                 if(data_response_object.error_message_monthly_usage !== ""){

                       $(".create-team").find(".monthly_usage").parent().addClass("has-feedback");
                       $(".create-team").find(".monthly_usage").parent().addClass("has-error");
                       $(".create-team").find(".monthly_usage").parent().find('.error-msg').text(" ");
                       $(".create-team").find(".monthly_usage").parent().find('.error-msg').append(data_response_object.error_message_monthly_usage);
             
                }
                 if(data_response_object.error_message_notification_hour !== ""){

                       $(".create-team").find(".notification_hour").parent().addClass("has-feedback");
                       $(".create-team").find(".notification_hour").parent().addClass("has-error");
                       $(".create-team").find(".notification_hour").parent().find('.error-msg').text(" ");
                       $(".create-team").find(".notification_hour").parent().find('.error-msg').append(data_response_object.error_message_notification_hour);
             
                }
               }
              
            }

    });

    
  });


  $(".btn-delete-team").on('click',function(e){
    e.preventDefault();
    $(".delete-team-confirmation").modal("show");

  });

  $(".delete-yes-popup").on('click',function(e){
    e.preventDefault();
    $('#team_list').submit();

  })
//on click of delete in support
 $(".support-team-list .delete.btn").on("click", function(e){
    e.preventDefault();
$('.delete-confirmation').modal("show");
  
});


 $(".customer-list .set-plan-amount-link").on("click", function(e){
    e.preventDefault();
    var parent_wrapper = $(this).parent().parent();

    if($(parent_wrapper).find('.hourly-rate').length !== 0){
      $('.set-plan-amount-popup .plan-amount').val($(parent_wrapper).find('.hourly-rate').text());
    }

    if($(parent_wrapper).find('.plan-hour').length !== 0){
      $('.set-plan-amount-popup .plan-hours').val($(parent_wrapper).find('.plan-hour').text());
    }
 $(".set-plan-amount-popup .customer_id").val($(this).attr('data-user-id'));
$('.set-plan-amount-popup').modal("show");
  
});





 //Set plan amount

 $(".set-plan-amount-popup .btn-default.save").css('display', "none");
 $(".set-plan-amount-popup .plan-total-details").css('display', "none");
 $(".set-plan-amount-popup .btn-default.calculate-final").on("click", function(e){
    e.preventDefault();

    var form_variable = $('.set-plan-amount-popup form').serialize();
    var form_submit_url = $(".set-plan-amount-popup form .validate_team_plan_url").val();
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
               if(data_response == "Success"){
                var total = ($(".set-plan-amount-popup .plan-hours").val()) * ($(".set-plan-amount-popup .plan-amount").val());
                $(".set-plan-amount-popup .plan-initial-details").hide();
                  $(".set-plan-amount-popup .modal-body .plan-total-details").css('display', 'block');
                 $(".set-plan-amount-popup .btn-default.save").css('display', "block");
                  $(".set-plan-amount-popup .btn-default.calculate-final").css('display', "none");
                $(".set-plan-amount-popup .modal-body .plan-total-details").html('');
                $(".set-plan-amount-popup .modal-body .plan-total-details").html('Monthly Bill: $'+ total +'.');
                  //      $(".set-plan-amount-popup form").submit();

               }
               else{
                var data_response_object =$.parseJSON(data_response);

                if(data_response_object.error_message_plan_hours !== ""){
                  $(".set-plan-amount-popup").find(".plan-hours").parent().addClass("has-feedback");
                  $(".set-plan-amount-popup").find(".plan-hours").parent().addClass("has-error");
                  $(".set-plan-amount-popup").find(".plan-hours").parent().find('.error-msg').text(" ");
$(".set-plan-amount-popup").find(".plan-hours").parent().find('.error-msg').append(data_response_object.error_message_plan_hours);
                }

                  if(data_response_object.error_message_plan_amount !== ""){
                  $(".set-plan-amount-popup").find(".plan-amount").parent().addClass("has-feedback");
                  $(".set-plan-amount-popup").find(".plan-amount").parent().addClass("has-error");
                  $(".set-plan-amount-popup").find(".plan-amount").parent().find('.error-msg').text(" ");
$(".set-plan-amount-popup").find(".plan-amount").parent().find('.error-msg').append(data_response_object.error_message_plan_amount);
                }
                
                
               }
              
            }

    });

    
  });

 $(".set-plan-amount-popup .btn-default.save").on("click",function(e){

      $(".set-plan-amount-popup form").submit();

 });


 //change_analyst

 $(".change-analyst-popup").on("click", function(e){
    e.preventDefault();

var analyst_value = $(this).attr('data-analyst-id');
         var id = $(this).parent("td").parent("tr").find(".id").text();
$(".change_analyst .popup-analyst").val(analyst_value);
$(".change_analyst .popup-id").val(id);
$(".change_analyst").modal("show" ,{backdrop: 'static', keyboard: false}  );

  
});

 //email-confirmation on task completion(analyst)

  $(".analyst-email").on("click",function(e){

      e.preventDefault();

      var previous_status  = $("#previous_status").val(); 

      var current_status  = $(".edit .current_status").val(); 
 

    
    if((previous_status != current_status) && (current_status  == 8)){

       $(".analyst-confirmation-email").modal("show");

    }

    else{

     $(".edit").submit();
    }



   });



  //analyst_email-confirmation btn click



$(".analyst-confirmation-email .btn-yes").on("click", function(e){

  e.preventDefault();

 //    var model_status = $('.analyst-confirmation-email').modal('show');

 //    var btn_yes = $(model_status).find(".btn-yes");


 //    $(btn_yes).on("click",function(e){
    
      $(".edit").submit();

    //  });

   


});    



 //email-confirmation on task completion(admin)

  $(".admin-email").on("click",function(e){

     e.preventDefault();

      var previous_status  = $("#previous_status").val(); 

      var current_status  = $(".edit .current_status").val(); 


    
    if((previous_status != current_status) && (current_status  == 8)){

      $(".admin-confirmation-email").modal("show");

    }

    else{

     $(".edit").submit();
    }



   });




//email-confirmation btn click

$(".admin-confirmation-email .btn-yes").on("click", function(e){

  e.preventDefault();

  //   var model_status = $('.admin-confirmation-email').modal('show');

  //   var btn_yes = $(model_status).find(".btn-yes");


  //  $(btn_yes).on("click",function(e){

    
      $(".edit").submit();

  // });

    


});    





//change analyst validation

 $(".change_analyst .btn1").on("click", function(e){
    e.preventDefault();

    var form_variable = $('.change_analyst form').serialize();
    var form_submit_url = $(".change_analyst form .validate_analyst_url").val();
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
               if(data_response == "Success"){
                  $(".change_analyst form").submit();

               }
               else{
                var data_response_object =$.parseJSON(data_response);

                if(data_response_object.error_message_email !== ""){
                  $(".change_analyst").find(".popup-analyst").parent().addClass("has-feedback");
                  $(".change_analyst").find(".popup-analyst").parent().addClass("has-error");
                  $(".change_analyst").find(".popup-analyst").parent().find('.error-msg').text(" ");
$(".change_analyst").find(".popup-analyst").parent().find('.error-msg').append(data_response_object.error_message_analyst);
                }
                
                
               }
              
            }

    });

    
  });








 /* on click pop yes button*/

 $(".delete-confirmation  .delete.btn").on("click", function(e){
    e.preventDefault();
    $(".support-team-list form").submit();
});

 /*Admin-Task Delete*/

 $(".task-list-admin .btn-delete-task").on("click",function(e){
   e.preventDefault();
   $(".delete-confirmation-task").modal("show");


 });

 $(".delete-confirmation-task .delete.btn").on("click",function(e){
 e.preventDefault();
    $(".task-list-admin form").submit();

 });

/*On Click of Save in Configaration check validation for email*/

     $(".edit-configuration-table .btn.btn-default").on("click", function(e){
    e.preventDefault();

    var form_variable = $('.edit-configuration-table form').serialize();
    var form_submit_url = $(".edit-configuration-table form .validate_email_url").val();
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
               if(data_response == "Success"){
                  $(".edit-configuration-table form").submit();

               }
               else{
                var data_response_object =$.parseJSON(data_response);

                if(data_response_object.error_message_email !== ""){
                  $(".edit-configuration-table").find(".popup-email").parent().addClass("has-feedback");
                  $(".edit-configuration-table").find(".popup-email").parent().addClass("has-error");
                  $(".edit-configuration-table").find(".popup-email").parent().find('.error-msg').text(" ");
$(".edit-configuration-table").find(".popup-email").parent().find('.error-msg').append(data_response_object.error_message_email);
                }
                
                
               }
              
            }

    });

    
  });





    
     /*Edit Plan Model Open*/
     $(".edit-plan-model").on('click', function(e){
        e.preventDefault();
        var plan_id = $(this).parent("td").parent("tr").find('.plan-id').text();
        var plan_name = $(this).parent("td").parent("tr").find('.plan-name').text();
        var plan_amount= $(this).parent("td").parent("tr").find('.plan-amount').text();
        var plan_hour = $(this).parent("td").parent("tr").find('.plan-hour').text();
        $(".update-plan").find(".plan_id").val(plan_id);
        $(".update-plan").find(".plan_name").val(plan_name);
        $(".update-plan").find(".plan_amount").val(plan_amount);
        $(".update-plan").find(".plan_hour").val(plan_hour);
        $(".update-plan").modal("show");  
     });
     /**/

     /*On Click of Save in Edit Plan Model check validations*/
     $(".update-plan .btn.btn-default").on("click", function(e){
    e.preventDefault();

    var form_variable = $('.update-plan form').serialize();
    var form_submit_url = $(".update-plan form .validate_plan_url").val();
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
               if(data_response == "Success"){
                  $(".update-plan form").submit();

               }
               else{
                var data_response_object =$.parseJSON(data_response);

                if(data_response_object.error_message_plan_name !== ""){
                  $(".update-plan").find(".plan_name").parent().addClass("has-feedback");
                  $(".update-plan").find(".plan_name").parent().addClass("has-error");
$(".update-plan").find(".plan_name").parent().find('.error-msg').append(data_response_object.error_message_plan_name);
                }
                if(data_response_object.error_message_plan_hour !== ""){
                  $(".update-plan").find(".plan_hour").parent().addClass("has-feedback");
                  $(".update-plan").find(".plan_hour").parent().addClass("has-error");
$(".update-plan").find(".plan_hour").parent().find('.error-msg').append(data_response_object.error_message_plan_hour);
             
                  
                }
                if(data_response_object.error_message_plan_amount !== ""){

                       $(".update-plan").find(".plan_amount").parent().addClass("has-feedback");

                       $(".update-plan").find(".plan_amount").parent().addClass("has-error");
$(".update-plan").find(".plan_amount").parent().find('.error-msg').append(data_response_object.error_message_plan_amount);
             
                }
               }
              
            }

    });

    
  });
     /**/






$(".support-team table").tablesorter(); 

 $('.support-team-list table').tablesorter({ headers: { 2: { sorter: false}, 3: {sorter: false}, 4: {sorter: false}, 5: {sorter: false} } });
$('.configuration-index table').tablesorter({ headers: { 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false} } });
$('.plan-listing table').tablesorter({ headers: { 0: { sorter: false}, 1: {sorter: false}, 2: {sorter: false}, 3: {sorter: false} } });
$('.customer-list table').tablesorter({ headers: { 
   
      2: {sorter: false}, 
      3: {sorter: false},
      4: {sorter: false},
      5: {sorter: false},
      6: {sorter: false},
      7: {sorter: false},
      8: {sorter: false},
      9: {sorter: false} 
    } 
  });
// $('.task-list table').tablesorter({ headers: { 2: {sorter: false}, 3: {sorter: false},  4: {sorter: false}, 5: {sorter: false}, 6: {sorter: false}, 7: {sorter: false} } });

$('.task-list-admin table').tablesorter({ headers: { 

      1:{ sorter: false}, 
      2: {sorter: false}, 
      3: {sorter: false},
      4: {sorter: false},
      5: {sorter: false},
      6: {sorter: false},
      7: {sorter: false}
  
    } 
  });






/*Admin interface Upgrade/Downgrade plan by using change plan*/

$('.customer-list table .update-plan-popup').on('click',function(e){
  e.preventDefault();
  var plan_id = $(this).parent().find(".plan_id_in_table").val();
  var user_id = $(this).parent().find(".user_id_in_table").val();


  $('.change-plan-model-popup .plan_list').each(function(e){
    var current_value = $(this).val();
    if(current_value == plan_id){
      $(this).attr("checked", true);

    }

  });
  $(".change-plan-model-popup .user_id_in_popup").val(user_id);
  $(".change-plan-model-popup .plan_id_in_popup").val(plan_id);

  $('.change-plan-model-popup .plan-initial-details').hide();
  $(".change-plan-model-popup input.btn-default").hide();
  $('.change-plan-model-popup .calculate-team-plan-total').hide();

  //$(".change-plan-model-popup").modal("show");

  setTimeout(function() {
    $('.change-plan-model-popup').modal();
},0);

});

$(".change-plan-model-popup .submit-change-plan-popup").on('click', function(e){

    e.preventDefault();
    var default_checked = $('.change-plan-model-popup input[name=plan_id_selected]:checked').val();

   
        $(".change-plan-model-popup form").submit();

   


})


  $(".change-plan-model-popup form .plan_list").each(function(e){
      $(this).change(function(){
          var id = "#" + $(this).attr('id');
          var checked = $(id).is(':checked');
          var selected_value = $(id).val();
           if(checked == true){
          
              if(selected_value == 3){
                  $(".change-plan-model-popup .plan-details").hide();
      $(".change-plan-model-popup .submit-change-plan-popup").hide();
      $('.change-plan-model-popup .plan-initial-details').show();
      $('.change-plan-model-popup .calculate-team-plan-total').show();

              }


           }
      });
  });


 $(".change-plan-model-popup  .btn-primary.calculate-team-plan-total").on("click", function(e){
    e.preventDefault();

    var form_variable = $('.change-plan-model-popup form').serialize();
    var form_submit_url = $(".change-plan-model-popup form .validate_team_plan_url").val();
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
               if(data_response == "Success"){
                var total = ($(".change-plan-model-popup .plan-hours").val()) * ($(".change-plan-model-popup  .plan-amount").val());
                $(".change-plan-model-popup  .plan-initial-details").hide();
                  $(".change-plan-model-popup  .modal-body .plan-total-details").css('display', 'block');
                 $(".change-plan-model-popup input.btn-default").css('display', "block");
                  $(".change-plan-model-popup .calculate-team-plan-total").css('display', "none");
                $(".change-plan-model-popup .modal-body .plan-total-details").html('');
                $(".change-plan-model-popup .modal-body .plan-total-details").html('Monthly Bill: $'+ total +'.');
                  //      $(".set-plan-amount-popup form").submit();

               }
               else{
                var data_response_object =$.parseJSON(data_response);

                if(data_response_object.error_message_plan_hours !== ""){
                  $(".change-plan-model-popup").find(".plan-hours").parent().addClass("has-feedback");
                  $(".change-plan-model-popup").find(".plan-hours").parent().addClass("has-error");
                  $(".change-plan-model-popup").find(".plan-hours").parent().find('.error-msg').text(" ");
$(".change-plan-model-popup").find(".plan-hours").parent().find('.error-msg').append(data_response_object.error_message_plan_hours);
                }

                  if(data_response_object.error_message_plan_amount !== ""){
                  $(".change-plan-model-popup").find(".plan-amount").parent().addClass("has-feedback");
                  $(".change-plan-model-popup").find(".plan-amount").parent().addClass("has-error");
                  $(".change-plan-model-popup").find(".plan-amount").parent().find('.error-msg').text(" ");
$(".change-plan-model-popup").find(".plan-amount").parent().find('.error-msg').append(data_response_object.error_message_plan_amount);
                }
                
                
               }
              
            }

    });

    
  });



/*End of Admin interface Upgrade/Downgrade plan by using change plan*/

$(".customer-list table .delete-customer-link").on('click',function(e){
  e.preventDefault();



  $(".delete_customer_id").val($(this).attr('data-delete-id'));

  
  $(".transaction_id").val($(this).attr('data-transaction-id'));
  $(".delete-customer-confirmation").modal('show');


})

$(".customer-list table .change-customer-status-link").on('click',function(e){
  e.preventDefault();


  var status_id = $(this).parent().find(".status_id_in_table").val();
  var user_id = $(this).parent().find(".user_id_in_table").val();
  var email_id = $(this).parent().find(".email_id_in_table").val();
  var plan_id = $(this).parent().find(".plan_id_in_table").val();
    var transaction_id = $(this).parent().find(".transaction_id_in_table").val();
  $('.change-user-status .status_list').each(function(e){
    var current_value = $(this).val();
    if(current_value == status_id){
      $(this).attr("checked", true);

    }

  });
  $(".change-user-status .user_id_to_change_status").val(user_id);
  $(".change-user-status .current_status").val(status_id);
  $(".change-user-status .email_id_in_popup").val(email_id);
   $(".change-user-status .current_plan").val(plan_id);
      $(".change-user-status .transaction_id_in_popup").val(transaction_id);

  $('.change-user-status .plan_list').each(function(e){
    var current_value = $(this).val();
    if(current_value == plan_id){
      $(this).attr("checked", true);

    }
  });

  $(".change-user-status .plan-details").hide();
  $(".change-user-status .plan-initial-details").hide();
  $(".change-user-status .plan-total-details").hide();
  $(".change-user-status .submit-change-plan-popup").hide();
  $(".change-user-status .calculate-team-plan-total").hide();
  $(".change-user-status .btn-final-submit").hide();
 setTimeout(function() {
    $('.change-user-status').modal();
},0);


});


$(".change-referral-status-link").on('click',function(e){
  e.preventDefault();


  var status_id = $(this).parent().find(".status_id_in_table").val();
  var user_id = $(this).parent().find(".user_id_in_table").val();

   $(".change-user-status .user_id_to_change_status").val(user_id);
  $(".change-user-status .current_status").val(status_id);
  var html = "";
  $("#add-time-page .m-b-md").remove();
  var html = '<div class="radio radio-custom"><label>';
  if(status_id == "0"){
    html += '<input type="radio" name="status_id_selected" value="1" class="status_list"><span class="checker"></span> <span class="check-label">Contacted</span><br></label></div><div class="radio radio-custom"><label><input type="radio" name="status_id_selected" value="0" class="status_list" checked="checked"><span class="checker"></span> <span class="check-label">New</span><br></label></div>';
  }else if(status_id == "1"){
    html += '<input type="radio" name="status_id_selected" value="1" class="status_list" checked="checked"><span class="checker"></span> <span class="check-label">Contacted</span><br></label></div><div class="radio radio-custom"><label><input type="radio" name="status_id_selected" value="0" class="status_list"><span class="checker"></span> <span class="check-label">New</span><br></label></div>';
  }
   
  $("#add-time-page .set_data").after(html);
  setTimeout(function() {
    $('.change-user-status').modal();
  },0);

});

//change notes pop up in referrals list
$(".change-referral-notes").on('click',function(e){
  
if (e.originalEvent.defaultPrevented) return;
  var referral_id = $(this).data('referral-id');
  console.log(referral_id);   
  var notes = $(this).data('notes');

   $(".change-referral-note #user_id_to_change_notes").val(referral_id);
   $(".change-referral-note #current_notes").val(notes);
 /* var html = "";
  $("#add-time-page .m-b-md").remove();
  var html = '<div class="radio radio-custom"><label>';
  if(status_id == "0"){
    html += '<input type="radio" name="status_id_selected" value="1" class="status_list"><span class="checker"></span> <span class="check-label">Contacted</span><br></label></div><div class="radio radio-custom"><label><input type="radio" name="status_id_selected" value="0" class="status_list" checked="checked"><span class="checker"></span> <span class="check-label">New</span><br></label></div>';
  }else if(status_id == "1"){
    html += '<input type="radio" name="status_id_selected" value="1" class="status_list" checked="checked"><span class="checker"></span> <span class="check-label">Contacted</span><br></label></div><div class="radio radio-custom"><label><input type="radio" name="status_id_selected" value="0" class="status_list"><span class="checker"></span> <span class="check-label">New</span><br></label></div>';
  }
   
  $("#add-time-page .set_data").after(html);*/
  setTimeout(function() {
    $('.change-referral-note').modal();
  },0);

});


/*Admin User Change Status logic
 *  1. Show plan change popup if the user changes the status from inactive to active
 *  2. Show Team hour/Amount Calculation popup if the user changes to team plan from other plan
 *  3.  
 *
 */


$(".change-user-status .btn-default").on("click", function(e){

    e.preventDefault();

     var current_status = $(".change-user-status form").find(".current_status").val();
  var default_checked = $('.change-user-status form input[name=status_id_selected]:checked').val();
  
   
    if(current_status !== "2" || current_status == default_checked){
      $(".change-user-status form").submit();
    }
    else{
      if(default_checked == "1"){
        $(".change-user-status  .status-wrapper").hide();
          $(".change-user-status  .btn-default").hide();
          $()
           $(".change-user-status  .submit-change-plan-popup").show();
        $(".change-user-status .plan-details").show();
      }
    }

});


$(".change-user-status .btn-primary.submit-change-plan-popup").on("click", function(e){

    e.preventDefault();

    var current_plan = $(".change-user-status .current_plan").val();
    var default_checked = $('.change-user-status form input[name=plan_id_selected]:checked').val();
  
    if(default_checked !== "3"){

      $(".change-user-status form").submit();
    }
    else{
     
           $(".change-user-status  .submit-change-plan-popup").hide();
           $(".change-user-status .plan-details").hide();
           $(".change-user-status .plan-initial-details").show();
           $(".change-user-status .calculate-team-plan-total").show();
    }

});




 $(".change-user-status  .btn-primary.calculate-team-plan-total").on("click", function(e){
    e.preventDefault();

    var form_variable = $('.change-user-status form').serialize();
    var form_submit_url = $(".change-user-status form .validate_team_plan_url").val();
    $.ajax({
      type: "POST",
      url: form_submit_url,
      data:  form_variable,
  
      success: function(data_response)
            {
               if(data_response == "Success"){
                var total = ($(".change-user-status .plan-hours").val()) * ($(".change-user-status .plan-amount").val());
                $(".change-user-status  .plan-initial-details").hide();
                  $(".change-user-status  .modal-body .plan-total-details").css('display', 'block');
                 $(".change-user-status input.btn-final-submit").show();
                  $(".change-user-status .calculate-team-plan-total").css('display', "none");
                $(".change-user-status .modal-body .plan-total-details").html('');
                $(".change-user-status .modal-body .plan-total-details").html('Monthly Bill: $'+ total +'.');
                  //      $(".set-plan-amount-popup form").submit();

               }
               else{
                var data_response_object =$.parseJSON(data_response);

                if(data_response_object.error_message_plan_hours !== ""){
                  $(".change-user-status").find(".plan-hours").parent().addClass("has-feedback");
                  $(".change-user-status").find(".plan-hours").parent().addClass("has-error");
                  $(".change-user-status").find(".plan-hours").parent().find('.error-msg').text(" ");
$(".change-user-status").find(".plan-hours").parent().find('.error-msg').append(data_response_object.error_message_plan_hours);
                }

                  if(data_response_object.error_message_plan_amount !== ""){
                  $(".change-user-status").find(".plan-amount").parent().addClass("has-feedback");
                  $(".change-user-status").find(".plan-amount").parent().addClass("has-error");
                  $(".change-user-status").find(".plan-amount").parent().find('.error-msg').text(" ");
$(".change-user-status").find(".plan-amount").parent().find('.error-msg').append(data_response_object.error_message_plan_amount);
                }
                
                
               }
              
            }

    });

    
  });


  $(".change-user-status input.btn-final-submit").on('click',function(e){
    $(".change-user-status form").submit();

  });


$(".member-list table .change-customer-status-link").on('click',function(e){
  e.preventDefault();


  var status_id = $(this).parent().find(".status_id_in_table").val();
  var user_id = $(this).parent().find(".user_id_in_table").val();
  var email_id = $(this).parent().find(".email_id_in_table").val();

  $('.change-team-status .status_list').each(function(e){
    var current_value = $(this).val();
    if(current_value == status_id){
      $(this).attr("checked", true);

    }

  });
  $(".change-team-status .user_id_to_change_status").val(user_id);
  $(".change-team-status .current_status").val(status_id);
  $(".change-team-status .email_id_in_popup").val(email_id);
 // $(".delete_customer_id").val($(this).attr('data-delete-id'));

 // $(".change-user-status").modal('show');

 setTimeout(function() {
    $('.change-team-status').modal();
},0);


});
 $(".form-control").each(function(e){
      $(this).keyup(function(e) {
        $(this).parent().removeClass("has-error");
        $(this).parent().find(".error-msg").html(" ");

      });
     });


    

       $(".add-more-file").on("click",function(e){
        e.preventDefault();


        var number = 1 + Math.floor(Math.random() * 6);
        var cloned_file = $(this).parent().find('.file-field-wrapper').eq(0).clone();
       
 var html = "<div class='file-field-wrapper'><input type='file'  size='40' name='userFiles[]' class='form-control margin-bottom-10' id='file"+number+"'  aria-required='true' aria-invalid='false'><a href='#' class='remove-more-file pull-right'>Remove</a></div>";
       
  
  
        $(html).appendTo('.file-clone-wrapper');

      });



       $(".file-wrapper").on("click",".remove-more-file",function(e){
         
          e.preventDefault();
          $(this).parent().remove();


       });


             
       
        $(".link-btn").click(function() {
           $(".wpcf7-text").val('');
           $(".wpcf7-textarea").val('');

           $('.multifiles').val('');
           $('span.fileName').text('');
         });
         $(".jQjob").click(function(){
             var variable = $(this).data('title');
             console.log('ddfsdf',variable);
             $("#jobdesc").val(variable);
         });
         
         $(".close").click(function(){
           location.reload();
         });


 $(".test").css("height",$(document).height() + "px");

  /*Customer Signup admin*/


  $(".admin-panel-signup .copy-billing-address").change(function() {
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
});



  
  //$( ".task-list select" ).select2();


 

  

 



