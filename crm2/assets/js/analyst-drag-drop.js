$(document).ready(function() { 

     $( ".task-analyst-sortable #assigned-panel, .task-analyst-sortable #inprogress-panel, .task-analyst-sortable #completed-panel" ).sortable({
      connectWith: ".sortable-wrapper",
        start: function(evt, ui){
            var start_html_id = ui.item.closest('ul').attr('id');

        },
        stop: function(evt, ui) {

          
            var current_sortable_item = $(this);
            //var start_html_id = ui.item.prev('ul').attr('id');
            var stop_html_id = ui.item.closest('ul').attr('id');
            var stop_type_id = ui.item.closest('ul').attr('data-status-type-id');
            var task_id = ui.item.find('.task-number').attr("data-type-id");

            var task_email = ui.item.find('.task-email').attr("data-type-email");
            var customer_name = ui.item.find('.task-email').attr("data-type-user-name");
            
            if(stop_html_id !== "completed-panel"){
              // console.log("hello");

               // $(".loader").css("display", "block");
               $(".loader").css("display", "block");
               // $(".test").css("display", "block");

               setTimeout(function() {
                 var update_response = updateStatus(task_id,stop_type_id,stop_html_id,task_email,customer_name);
               

                  if(update_response =="success"){
                    $(".loader").css("display", "none"); 
                    $(".test").css("display", "none"); 
                    location.reload();
                  }
               },0);
            
            }   
            else{
            var model_status = $('.confirmation-drag-drop').modal('show');
            if(model_status !== "undefined" && model_status !== "NULL"){
                var btn_yes = $(model_status).find(".btn-yes");
                var btn_no = $(model_status).find(".btn-no");
                var close_button = $(model_status).find('.close');
                $(btn_yes).on("click",function(e){
                $('.confirmation-drag-drop').modal('hide');
                //console.log($(".confirmation-drag-drop"));
                $(".loader").css("display", "block");
                  $(".test").css("display", "block");
                setTimeout(function() {
                 var result = updateStatus(task_id,stop_type_id,stop_html_id,task_email,customer_name);
                  //console.log(result);
                  //console.log(result)
                if(result == "success"){

                  $(".loader").css("display", "none"); 
                    $(".test").css("display", "none"); 
                    location.reload();
                }
                else{
                   // // console.log(result);
                    $(current_sortable_item).sortable('cancel'); 
                     $(".loader").css("display", "none"); 
                       $(".test").css("display", "none");
                      location.reload();
                }
                },0)
               
               });

                $(btn_no).on("click",function(e){       
                    $(current_sortable_item).sortable('cancel');
                     $('.confirmation-drag-drop').modal('hide'); 
               
               });

                $(close_button).on("click",function(e){       
                    $(current_sortable_item).sortable('cancel');
                     $('.confirmation-drag-drop').modal('hide'); 
                     
               });


            
            }
        }
            
  
        },
         // over: function(evt, ui){
         //     $(".loader").css("display", "none");
         // },
    
    }).disableSelection();
});

function updateStatus($task_id,$stop_type_id,$stop_html_id,$task_email,$customer_name){
    //  $('.confirmation-drag-drop').modal('hide');
    // $(".loader").css("display", "block");
      var status = "";
      $.ajax({
            type: "POST",
            url: "/task/update_analyst_status_id_for_task",
            async: false,
            data:  {task_id:$task_id, analyst_status_id: $stop_type_id,stop_html_id: $stop_html_id ,task_email: $task_email, customer_name: $customer_name},
            success: function(data_response)
            { 
                console.log(data_response);
                  
                    if(data_response.indexOf("1") > -1){
                           status =  "success";
                        
                    } 
                    else{
                        status = "failure";

                    }

            }
            });


      return status;





 }
