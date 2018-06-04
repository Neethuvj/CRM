 
 <div class="container products-detail admin-panel-signup">



<article class="rs-content-wrapper" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
        <?php if( $this->session->flashdata('success_message')): ?>
 <div class="alert alert-success">
          <?php
  echo $this->session->flashdata('success_message');

?>
</div>
<?php endif; ?>
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
           <div class="alert alert-info">Content for this tab needs to be finalized</div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>
  </div>