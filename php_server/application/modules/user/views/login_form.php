<div class="container">
    <h2>Login</h2>

    <?php echo form_open('user/validate_credentials', 'class="form-horizontal" role="form" '); ?>

    <?php if (validation_errors() != false) : ?>
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <br>
            <fieldset id="error">
                <legend><h2>Sorry, something went wrong...</h2></legend>
                <?php echo validation_errors('<p class="error"-->'); ?>;
            </fieldset>
        </div>	
    <?php endif; ?>

    <?php if (isset($error)) : ?>
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <br>
            <fieldset id="error">
                <legend><h2>Sorry, something went wrong...</h2></legend>
                <p class="error"><?php echo $error; ?></p>
            </fieldset>
        </div>	
    <?php endif; ?>


    <div class="form-group">
        <label class="col-lg-2 control-label" for="username">Email</label>
        <div class="col-lg-8">
            <?php echo form_input('email', set_value('email'), 'class="input-lg form-control"'); ?>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-2 control-label" for="password">Password</label>
        <div class="col-lg-8">
            <?php echo form_password('password', '', 'class="input-lg form-control"'); ?>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?php echo form_submit('submit', 'login', 'class = "btn btn-large btn-primary"'); ?>
            <?php echo anchor('site/register', 'Register', 'class = "btn btn-large btn-primary"'); ?>
        </div>
    </div>


    <?php echo form_close(); ?>


</div>