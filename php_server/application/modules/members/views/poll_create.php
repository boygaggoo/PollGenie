<div class="container">
    <h2>Create a poll</h2>

    <?php echo form_open('members/c_poll/'.$group_id, 'class="form-horizontal well" role="form" '); ?>

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
        <label class="col-lg-2 control-label" for="ques">Poll Question</label>
        <div class="col-lg-8">
            <?php echo form_input('ques', set_value('ques'), 'class="input-lg form-control"'); ?>
        </div>
    </div>
    <hr />
    
    <div id="answers"></div>

    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <button id="addOption" class="btn btn-primary" onclick="return false;">Add multiple choice option</button>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-lg-offset-2 col-lg-10">
            <?php echo form_submit('submit', 'Create Poll', 'class = "btn btn-large btn-primary"'); ?>
        </div>
    </div>


    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
    var counter=1;
   $('#addOption').click(function(){
       addItem(counter);
       counter ++;
   }); 
});

function addItem(num){
    
    var opt = '<div class="form-group">';
   opt = opt + '<label class="col-lg-2 control-label" for="type">option_'+ num +'</label>';
   opt = opt + '<div class="col-lg-8">';
   opt = opt + '<input type="text" name="option_'+ num +'" class="input-lg form-control" /><br />';
   opt = opt + '</div></div>';
   
    $('#answers').append(opt);
}

function removeItem(name){
}

</script> 