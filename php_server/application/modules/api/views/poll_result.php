<style>
    div#loading{
        margin:3em auto;
        width:50px;
        height:50px;
        border-radius:25px;
        box-sizing:border-box;
        border:25px solid #000;
        animation: loading 1.3s linear 0 infinite;
        -webkit-animation: loading 1.3s linear 0 infinite /* Safari and Chrome */
    }

    div#loader{
        text-align:center;
        font-weight:bold;
        padding: 10px;
    }

    @keyframes loading
    {
        0% {
            border: 25px solid #000;
            background:#fff;
        }
        49.9% {
            border: 0px solid #000;
            background:#fff;
        }
        50% {
            border: 25px solid #fff;
            background:#000;
        }
        100%{
            border: 0px solid #fff;
            background:#000;
        }
    }

    @-webkit-keyframes loading /* Safari and Chrome */
    {
        0% {
            border: 25px solid #000;
            background:#fff;
        }
        49.9% {
            border: 0px solid #000;
            background:#fff;
        }
        50% {
            border: 25px solid #fff;
            background:#000;
        }
        100%{
            border: 0px solid #fff;
            background:#000;
        }
    }
</style>

<script>
    window.onload = function() {
        document.getElementById('loader').style.display = 'none';
    };
    window.onunload = function() {
        document.getElementById('loader').style.display = 'block';
    };
</script>


<div class="container">
    <h2>Group polls</h2>

    <div class="row">

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading"><h4><strong><em>Poll result</em></strong></h4></div>

            <div id="loader">
                <div id="loading"></div>
                Loading...
            </div>

            <div id="poll_div"></div>

            <?php
            // view
            echo $this->gcharts->ColumnChart('Poll')->outputInto('poll_div');
            echo $this->gcharts->div(200, 100);

            if ($this->gcharts->hasErrors()) {
                echo $this->gcharts->getErrors();
            }
            ?>

        </div>
        <?php echo anchor("members/group/$group_id", "Back", 'class="btn btn-primary btn-lg"'); ?>

    </div>

</div>

<script>
    $(document).ready(function() {
        $('#polls').click(function() {
        });

<?php foreach ($polls as $p): ?>
            $('#poll_<?php echo $p['id']; ?> tr').click(function() {
                var r = $(this).find('td input[type=radio]');
                r.prop('checked', true);
                var url = "<?php echo site_url("members/poll_ajax"); ?>";
                var data = r.val();
                var n = data.split("_");
                url = url + "/" + n[0] + "/" + n[1];
                $.post(url).done(function(data) {
                    alert(data);
                });
            });
<?php endforeach; ?>
    });

</script>