<div class="container">
    <h2>Group polls</h2>

    <div class="row">
        <div class="col-lg-8">

            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4><strong><em>Polls</em></strong></h4></div>

                <table class="table table-striped table-hover" id="polls">

                    <thead>
                    <th>#</th>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Options</th>
                    </thead>

                    <tbody>
                        <?php foreach ($polls as $key => $p): ?>
                            <tr>
                                <td><?php echo $key + 1; ?>
                                    <?php if($this->session->userdata('id') == $p['owner'] ):  ?>
                                        <br />
                                        <button>Delete</button>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $p['id']; ?></td>
                                <td><?php echo anchor("members/poll_result/". $p['id'], $p['ques'], 'class="btn btn-primary"' ); ?></td>
                                <td>
                                    <table class="table table-condensed" id="poll_<?php echo $p['id']; ?>">
                                        <?php foreach ($p['answers'] as $i): ?>
                                            <tr>
                                                <td><input name="poll" id="poll_<?php echo $p['id'] ?>" type="radio" value="<?php echo $p['id'] . "_" . $i['id']; ?>"></td>
                                                <td><?php echo $i['ans']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Groups Menu</h3>
                </div>
                <ul class="list-group">
                    <?php
                    echo '<li class="list-group-item">' . anchor('members', 'All my groups') . '</li>';
                    echo '<li class="list-group-item">' . anchor('members/create_group', 'Create a group') . '</li>';
                    echo '<li class="list-group-item">' . anchor('members/join_group', 'Join a group') . '</li>';
                    ?>
                </ul>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Polls Menu</h3>
                </div>
                <ul class="list-group">
                    <?php
                    echo '<li class="list-group-item">' . anchor('members/group/' . $group_id, 'All polls') . '</li>';
                    echo '<li class="list-group-item">' . anchor('members/create_poll/' . $group_id, 'Create a poll') . '</li>';
                    ?>
                </ul>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Poll results</h3>
                </div>
                <ul class="list-group">
                    <?php
                    echo '<li class="list-group-item">' . anchor('members/results/' . $group_id, 'Summary') . '</li>';
                    ?>
                </ul>
            </div>

        </div>
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