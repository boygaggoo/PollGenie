<script type="text/javascript">
    $(document).ready(function() {

    });
    function sendPushNotification(id) {
        var data = $('form#' + id).serialize();
        $('form#' + id).unbind('submit');
        $.ajax({
            url: "<?php echo site_url('api/send_message') ?>",
            type: 'GET',
            data: data,
            beforeSend: function() {

            },
            success: 7us, xhr) {
                $('.txt_message').val("");
            },
            error: function(xhr, textStatus, errorThrown) {

            }
        });
        return false;
    }
</script>


<style type="text/css">
    h1{
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 24px;
        color: #777;
    }
    div.clear{
        clear: both;
    }
    ul.devices{
        margin: 0;
        padding: 0;
    }
    ul.devices li{
        float: left;
        list-style: none;
        border: 1px solid #dedede;
        padding: 10px;
        margin: 0 15px 25px 0;
        border-radius: 3px;
        -webkit-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
        -moz-box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.35);
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        color: #555;
    }
    ul.devices li label, ul.devices li span{
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        font-size: 12px;
        font-style: normal;
        font-variant: normal;
        font-weight: bold;
        color: #393939;
        display: block;
        float: left;
    }
    ul.devices li label{
        height: 25px;
        width: 50px;                
    }
    ul.devices li textarea{
        float: left;
        resize: none;
    }
    ul.devices li .send_btn{
        background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
        background: -webkit-linear-gradient(0% 0%, 0% 100%, from(#0096FF), to(#005DFF));
        background: -moz-linear-gradient(center top, #0096FF, #005DFF);
        background: linear-gradient(#0096FF, #005DFF);
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.3);
        border-radius: 3px;
        color: #fff;
    }
</style>

<div class="container">
    <?php if (sizeof($users) > 0): ?>
    
        <?php echo form_open('api/send_message'); //"method=\"post\" onsubmit=\"return sendPushNotification('$u->id')\" "); ?>
    
            <ul class="devices">
                <?php foreach ($users as $u): ?>
                    <?php if (!empty($u)): ?>
                        <li>

                            <div class="clear"></div>
                            <label>Email:</label> <span><?php echo $u->email; ?></span>
                            <div class="clear"></div>
                            <div class="send_container">                                
                                <textarea rows="3" name="message_<?php echo $u->id; ?>" cols="25" class="txt_message" placeholder="Type message here"></textarea>
                                <input type="hidden" name="regId_<?php echo $u->id; ?>" value="<?php echo $u->gcm_regid ?>" />
                                <button type="submit" class="send_btn" name="phones" value="p_<?php echo $u->id; ?>">Send</button>
                            </div>

                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
    
        <?php echo form_close(); ?>
    
    <?php endif; ?>

</div>