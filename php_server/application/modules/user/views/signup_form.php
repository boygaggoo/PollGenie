<div class="container">

    <h2> Create a Student Account</h2>
    <?php echo form_open('user/create_user', 'class="form-horizontal well" role="form" '); ?>

    <?php if (validation_errors() != false) : ?>
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <br />
            <fieldset id="error">
                <legend><h2>Sorry, something went wrong...</h2></legend>
                <?php echo validation_errors('<p class="error">'); ?>
            </fieldset>
        </div>	
    <?php endif; ?>

    <?php if (isset($error)) : ?>
        <div class="alert alert-warning alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <br />
            <fieldset id="error">
                <legend><h2>Sorry, something went wrong...</h2></legend>
                <pre class="error"><?php echo $error; ?></pre>
            </fieldset>
        </div>	
    <?php endif; ?>

    <fieldset>

        <div class="form-group">
            <label class="col-lg-2 control-label" for="name">Full Name</label>
            <div class="col-lg-8">
                <?php echo form_input('name', set_value('name'), 'class="input-lg form-control" id="name" placeholder="Name"'); ?>
            </div>
        </div>


        <div class="form-group">
            <label class="col-lg-2 control-label" for="username">Email</label>
            <div class="col-lg-8">
                <?php echo form_input('email', set_value('email'), 'class="input-lg form-control" id="name" placeholder="Email"'); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label" for="password">Password</label>
            <div class="col-lg-8">
                <?php echo form_password('password', "", 'class="input-lg form-control" id="name" placeholder="Password"'); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-2 control-label" for="password2">Password Again</label>
            <div class="col-lg-8">
                <?php echo form_password('password2', "", 'class="input-lg form-control" id="name" placeholder="Password Again"'); ?>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?php echo form_submit('submit', 'Create Account', 'class = "btn btn-large btn-primary"'); ?>
            </div>
        </div>


    </fieldset>

</form>
</div>
