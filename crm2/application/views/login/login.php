<!DOCTYPE html> 
<html lang="en-US">
  <head>
    <title>OP360</title>
    <meta charset="utf-8">
    
  </head>
  <body>
    <div class="container login" style = "height: 500px; padding:100px 380px;">
      <?php 
      $attributes = array('class' => 'form-signin');
      echo form_open('user/validate_credentials', $attributes);
      echo '<h2 class="form-signin-heading">Login</h2>';
      echo form_input('user_name', '', 'placeholder="Username"');
      echo form_password('password', '', 'placeholder="Password"');
      if(isset($message_error) && $message_error){
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> Change a few things up and try submitting again.';
          echo '</div>';             
      }
      echo "<br />";
      // echo anchor('admin/signup', 'Signup!');
      echo "<br />";
      echo "<br />";
      echo form_submit('submit', 'Login');
      echo "<br />";
      echo "<br />";
      echo form_close();
      ?>      
    </div><!--container-->
   
  </body>
</html>    
    