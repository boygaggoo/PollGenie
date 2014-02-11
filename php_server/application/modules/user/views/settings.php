<div class="container">
	
	<h2>Settings</h2>
	
	<?php if(validation_errors() != false) : ?>
		<div class="alert">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
			<br />
			<fieldset id="error">
			<legend><h2>Sorry, something went wrong...</h2></legend>
			<?php echo validation_errors('<p class="error">'); ?>
			</fieldset>
		</div>	
	<?php  endif; ?>
	
	<?php if (isset($success) ){echo "<p class=\"alert alert-success\">$success</p>";}?>

	<div class="row-fluid">
		<div class="span6 well">
			<h3>Email</h3>
			<hr />
			<p>Preffered Email: <?php echo $this->session->userdata('email'); ?></p>	
			<?php // echo anchor('login/update_email_option', 'edit'); ?>
			<a href="#update_email" role="button" class="btn btn-primary" data-toggle="modal">Update email</a>
		</div>
		
		<div class="span6 well">
			<h3>Password</h3>  
			<hr />
			<p>Last Changed: <?php echo 'date'; ?></p>
			<?php // echo anchor('login/update_password_option', 'Manage Security'); ?>
			<a href="#update_password" role="button" class="btn btn-primary" data-toggle="modal">Update password</a>
		</div>
	</div>
</div>


<!-- Email Modal -->
<div id="update_email" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Update email" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="Update Email">Update Email</h3>
  </div>
  <div class="modal-body">
  	<p>For security reason, please enter in your current password along with your new email address</p>
  	
  	<?php echo form_open('login/settings_update', 'id="email_form"'); ?>    
	
	<div class="control-group">
	<label class="control-label" for="password">Password</label>
	<div class="controls">
	<?php echo form_password('password', '', 'class="input-xlarge" id="password"');?>
	<span id="validatePassword"></span>
	</div>
	</div>
	
	<div class="control-group">
	<label class="control-label" for="username">New email</label>
	<div class="controls">
	<?php echo form_input('email', set_value('email'), 'class="input-xlarge", id="email"');?>
	<span id="validateEmail"></span>
	</div>
	</div>
	
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <?php echo form_submit('submit', 'Save email', 'class = "btn btn-primary" id="sendit"');?>
  </div>
</div>


<!-- Password Modal -->
<div id="update_password" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Update password" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="Update Email">Update password</h3>
  </div>
  <div class="modal-body">
    
	<?php echo form_open('login/settings_update', 'id="pass_form"'); ?>    
	
	<div class="control-group">
	<label class="control-label" for="old_pass">Current password</label>
	<div class="controls">
	<?php echo form_password('old_pass', '', 'class="input-xlarge" id="old_pass"');?>
	<span id="validatePassword1"></span>
	</div>
	</div>
	
	<div class="control-group">
	<label class="control-label" for="new_pass">New password</label>
	<div class="controls">
	<?php echo form_password('new_pass', '', 'class="input-xlarge" id="new_pass"');?>
	<span id="validatePassword2"></span>
	</div>
	</div>
	
	<div class="control-group">
	<label class="control-label" for="new_pass1">Confirm password</label>
	<div class="controls">
	<?php echo form_password('new_pass1', '', 'class="input-xlarge" id="new_pass1"');?>
	<span id="validatePassword3"></span>
	</div>
	</div>
	
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <?php echo form_submit('submit', 'Save password', 'class = "btn btn-primary" id="sendit1"');?>
  </div>
</div>

<script>
function validateEmail($email) {
  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
  if( !emailReg.test( $email ) ) {
    return false;
  } else {
    return true;
  }
}

$(document).ready(function () {
	
	$('#password').keyup(function () {
    	var password = $('#password').val();
    	$.post(
    		'/codeboard/index.php/login/check_password',
      		{ 'password': password },
      		function(result) {
				// clear any message that may have already been written
				$('#validatePassword').replaceWith('');
	
				if (result) {
					$('#validatePassword').html(result);
					if(result == '<span class="badge badge-success">Password ok.</span>'){
						$('#sendit').prop('disabled', false);
					}
					
				}
			}
		);
	});
	
	$('#old_pass').keyup(function () {
    	var password = $('#old_pass').val();
    	$.post(
    		'/codeboard/index.php/login/check_password',
      		{ 'password': password },
      		function(result) {
				// clear any message that may have already been written
				$('#validatePassword1').replaceWith('');
	
				if (result) {
					$('#validatePassword1').html(result);
					if(result == '<span class="badge badge-success">Password ok.</span>'){
						//$('#sendit').prop('disabled', false);
					}
					
				}
			}
		);
	});
	
});	





</script>

