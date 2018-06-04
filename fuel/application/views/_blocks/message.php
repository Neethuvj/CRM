<?php

$success_message =$this->session->flashdata('success_message');
if($success_message){
echo '<div class="alert alert-success">';
echo $success_message;
echo '</div>';
}
$warning_message =$this->session->flashdata('warning_message');
if($error_message){
echo '<div class="alert alert-warning">';
echo $warning_message;
echo '</div>';
}
$info_message =$this->session->flashdata('info_message');
if($error_message){
echo '<div class="alert alert-info">';
echo $info_message;
echo '</div>';
}
$error_message =$this->session->flashdata('error_message');
if($error_message){
echo '<div class="alert alert-danger">';
echo $error_message;
echo '</div>';
}
?>

