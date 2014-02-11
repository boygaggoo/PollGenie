<div class="container">
    <h2>Join a Group</h2>

    <?php
    //var_dump($groups); // echo '<hr />'; var_dump($my_groups); //die();
    
    // remove all groups I am already in
    if(isset($groups)){
        foreach ($groups as $key=>$g){
            foreach($my_groups as $m){
                if($g->id == $m->id){
                    unset($groups[$key]);
                    break;
                }
            }
        }
    }
    
    // echo '<hr />'; var_dump($groups); // die();
    
    ?>
    
    <div class="row">
        <div class="col-lg-8">
            
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4><strong><em>All Groups I am not in</em></strong></h4></div>

                <!-- Table -->
                <table class="table">
                    <thead>
                    <th>Group ID</th>
                    <th>Group Name</th>
                    </thead>

                    <?php foreach ($groups as $g): ?>
                        <tr>
                            <td><?php echo $g->id; ?></td>
                            <td><?php echo $g->name; ?></td>
                            <td><?php echo anchor('members/j_group/' . $g->id, "join", 'class="btn btn-primary"'); ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </div>
            
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h4><strong><em>My Groups</em></strong></h4></div>

                <!-- Table -->
                <table class="table">
                    <thead>
                    <th>Group ID</th>
                    <th>Group Name</th>
                    </thead>

                    <?php foreach ($my_groups as $g1): ?>
                        <tr>
                            <td><?php echo $g1->id; ?></td>
                            <td><?php echo anchor('members/group/' . $g1->id, $g1->name); ?></td>
                            <td><?php echo anchor('members/r_group/' . $g->id, "leave", 'class="btn btn-primary"'); ?></td>
                        </tr>
                    <?php endforeach; ?>

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
        </div>
    </div>

</div>