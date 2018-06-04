</div>
<div id="footer">
  <div class="footer-top">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="text-center">
            <p>Copyright &#169; <?php echo date("Y"); ?> - SalesSupport360. All rights reserved</p>
            <?php
            if(isset($name) && $name){?>
            <a href="/user/logout">Logout</a>
            <?php }?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="<?php echo base_url(); ?>assets/js/chosen.jquery.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.js" ></script>

<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.min.js"></script>


<script src="<?php echo base_url(); ?>assets/js/datepicker-example.js"></script> 

<script>
  
  $( ".task-list-admin .filter-panel select" ).chosen({width: '100%'});

  $( ".customer-list .filter-panel select" ).chosen({width: '100%'});

  $(".user-summary-filter-form select").chosen();
  $(".password").keyup(function() {
          //alert("here");
          passwordStrength($(this).val());
        });


    $('.rs-timepicker').datetimepicker({

                format: 'HH:ii p',
                autoclose: true,
                defaultDate: $('.rs-timepicker').attr('value'),
                // todayHighlight: true,
                showMeridian: true,
                startView: 1,
                maxView: 1

                    });


  $(".expiry-month").datepicker( {
 format: " mm", // Notice the Extra space at the beginning
    viewMode: "months", 
    minViewMode: "months"
})


        $(".expiry-year").datepicker( {
 format: " yyyy", // Notice the Extra space at the beginning
    viewMode: "years", 
   yearRange: "-1:+25",
    minViewMode: "years"
})

    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.form_date').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.form_time').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });
  

           //alert(here);
     $('#editable').on('click',function() {           
     $('.log-worked').attr('disabled', false);
     $('.log-date').attr('disabled', false);   

  });

</script>




<!-- Bootstrap Core JavaScript --> 
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script> 

<script src="<?php echo base_url(); ?>assets/js/jaktutorial.js"></script> 


<script src="<?php echo base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="<?php echo base_url(); ?>assets/js/jquery.tablesorter.js"></script> 

 <script src="<?php echo base_url(); ?>assets/js/custom.js"></script> 
 <script src="<?php echo base_url(); ?>assets/js/common.js"></script> 
 <script src="<?php echo base_url(); ?>assets/js/sidebar-collapse.js"></script>








<script>
    // jQuery('.carousel').carousel({   
    //     interval: 5000 //changes the speed
    // })

      
    $(document).ready(function() {
        $("#to").datepicker({ dateFormat: 'yy-mm-dd' });
        $("#from").datepicker({ dateFormat: 'yy-mm-dd' }).bind("change",function(){
            var minValue = $(this).val();
            minValue = $.datepicker.parseDate("yy-mm-dd", minValue);
            minValue.setDate(minValue.getDate()+1);
            $("#to").datepicker( "option", "minDate", minValue );
        })
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap .wrapper-to-add"); //Fields wrapper
    var add_button      = $(".add-fields"); //Add button ID
    
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click

        e.preventDefault();
        if(x < max_fields){ //max input box allowed

            x++; //text box increment
            //alert(x);
            //alert($(wrapper).append('<p>here</p>'));
            $(wrapper).append('<div><div class="row"><div class="col-sm-3"><div class="form-group"g><label>Enable Report Notification</label><input type="checkbox" name="email_status[]" value ="1" class="ss-check" ></div></div></div><div class="row"><div class="col-md-6"><div class="form-group"><label>Name</label><input type="text" name="assistant_name[]" class="form-control" ></div></div><div class="col-md-6"><div class="form-group"><label>Email</label><input type="text" name="assistant_email[]" class="form-control"></div></div></div><div class="row"><div class="col-md-6"><div class="form-group"><label>Phone No</label><input type="text" name="assistant_phone[]" class="form-control"></div></div></div><a href="#" class="remove_field">Remove</a></div><input type="hidden" name="delete_assistant[]" value="" class="form-control delete_assistance" ><input type="hidden" name="assistant_id[]" value="" class="form-control assistant_id" >'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text

        e.preventDefault(); 
        console.log($(this).parent('div').next(".delete_assistance").val($(this).parent('div').find(".assistant_id").val()));
        $(this).parent('div').remove(); 
        x--;
    });
    // $(".form_datetime").datepicker({format: 'yyyy-mm-dd hh:ii'});
    });
    </script>
     <script type="text/javascript">
$(document).ready(function(){
    var maxField = 20; //Input fields increment limitation
    var addButton = $('.add_button'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var fieldHTML = '<div><input type="text" class="form-control" name="links[]" /><a href="javascript:void(0);" class="remove_button" title="Remove field">Remove</a></div>'; //New input field html 
    var x = 1; //Initial field counter is 1
    $(addButton).click(function(){ //Once add button is clicked
        if(x < maxField){ //Check maximum number of input fields
            x++; //Increment field counter
            $(wrapper).append(fieldHTML); // Add field html
        }
    });
    $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
        e.preventDefault();
        $(this).parent('div').remove(); //Remove field html
        x--; //Decrement field counter
     });
});

</script>
</body>
</html>