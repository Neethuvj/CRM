

 <article class="rs-content-wrapper task-panel" >
    <div class="rs-content" >
      <div class="rs-inner"> 
        <!-- Begin default content width -->
        <div class="container-fluid container-fluid-custom"> 
          
          <!-- Begin Panel -->
          <div class="panel panel-plain panel-rounded">
            <div class="p-t-xs">
             
              <ul class="list-group task-list" style="list-style-type: none;">
                <?php foreach($tasklist as $task) {?>
                <li  class="list-group-item"><a href="<?php echo base_url();?>task/add/<?php echo $task->id; ?>"><?php echo $task->name; ?></a>
                <span class="ss_description">
                  <?php echo $task->description; ?>
                </span>

                </li>
                <?php } ?>
              </ul>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>
  <div id="push"></div>

         