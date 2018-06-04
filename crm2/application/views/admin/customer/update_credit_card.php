

<article class="rs-content-wrapper update-credit-card-customer" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
             <?php

      //form validation
if($this->session->flashdata('success_message'))
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
           echo $this->session->flashdata('success_message');

          echo '</div>';       
        }

if($this->session->flashdata('error_message'))
        {
          echo '<div class="alert alert-danger">';
            echo '<a class="close" data-dismiss="alert">×</a>';
           echo $this->session->flashdata('error_message');

          echo '</div>';       
        }



      echo form_open('admin/customer/update_credit_card/'. $user_id);
      ?>


          <div class="row">
                  <div class="col-md-12">
                    <h3>Credit Card Info</h3>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('card_type')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label class="control-label">Card Type *</label>
                      <?php echo form_dropdown('card_type',$cards,array(),array('class' => 'form-control')); ?>
                      <span class="error-msg"> <?php echo form_error('card_type'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('card_number')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Card Number *</label>
                           <?php echo form_input(array('type' => 'text', 'name' => 'card_number', 'class' => 'form-control')); ?>
                          <span class="error-msg">  <?php echo form_error('card_number'); ?> </span>
                    </div>
                  </div>
                </div>

                 <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('card_holder_name')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Card Holder's Name *</label>
                                <?php echo form_input(array('type' => 'text', 'name' => 'card_holder_name', 'class' => 'form-control')); ?>
                             <span class="error-msg">    <?php echo form_error('card_holder_name'); ?> </span>
                    </div>
                  </div>
                  <div class="col-md-6">
                      
                      <div class="<?php  if(form_error('expiry-month') || form_error('expiry-year')  ) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Expiration Date *</label>
                            <div class="row">
                                <div class="col-sm-6 pad-right0 pad-left15">
                                    <input name ="expiry-month" type="text" class="form-control expiry-month" placeholder="MM">
                                     <span class="error-msg"> <?php echo form_error('expiry-month'); ?> </span>
                                </div>
                                <div class="col-sm-6 pad-left0 pad-right15">
                                 <input name ="expiry-year" type="text" class="form-control expiry-year" placeholder="YYYY">
                                     <span class="error-msg"> <?php echo form_error('expiry-year'); ?> </span>
                                </div>
                            </div>
                    </div>
                    </div>
                  </div>

                           <div class="row">
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('billing_street_address')  ) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Street Address *</label>
                                <?php echo form_input(array('type' => 'text', 'name' => 'billing_street_address', 'class' => 'form-control billing-address')); ?>
                             <span class="error-msg">    <?php echo form_error('billing_street_address'); ?></span>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('billing_city')  ) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>City *</label>
                             <?php echo form_input(array('type' => 'text', 'name' => 'billing_city', 'class' => 'form-control billing-city')); ?>
                         <span class="error-msg">     <?php echo form_error('billing_city'); ?></span>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">

          <div class= "<?php  if(form_error('billing_state')) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>State *</label>
                      <?php echo form_input(array('type' => 'text', 'name' => 'billing_state', 'class' => "form-control billing-state")); ?>
                        <span class="error-msg">  <?php echo form_error('billing_state'); ?> </span>
                    
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="<?php  if(form_error('billing_zip')  ) { echo $error_class; } else{ echo $error_less_class;} ?>">
                      <label>Zip / Postal Code *</label>
                      <?php echo form_input(array('type' => 'text', 'name' => 'billing_zip', 'class' => 'form-control billing-zip')); ?>
                    <span class="error-msg">   <?php echo form_error('billing_zip'); ?> </span>
                    
                    </div>
                  </div>
                </div>


                     <div class="row">
                  <div class="col-md-6">
                  <div class="form-submit">
                   <?php echo form_submit('submit', 'Update Card', array("class" => 'btn btn-primary')); ?>
                  </div>
                  </div>
                </div>
                
            
               <?php echo form_close(); ?>
              <!--credit card onfo-->
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>