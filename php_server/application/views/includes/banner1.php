<!-- NAVBAR
================================================== -->
<style>
    body{
        font-family: 'Amarante', cursive;
    }
    
    .navbar-header a{
        font-family: 'Monoton', cursive;
    }
</style>
<div class="navbar-wrapper">
    <div class="navbar navbar-inverse navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                
                <?php echo anchor('site', 'Poll-Genie', array('class'=>'navbar-brand') ); ?>
                
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><?php echo anchor('site', 'Home', array('class'=>'active') ); ?></li>
                    <li><a href="site/features">Features</a></li>
                    <li><a href="site/contact">Contact</a></li>
                </ul>

                <ul class="nav navbar-nav navbar-right">

                    <?php
                    $is_logged_in = $this->session->userdata('is_logged_in');

                    if (!isset($is_logged_in) || $is_logged_in != true) {
                        echo '<li  class="active">';
                        echo anchor('user/login', 'Login');
                        echo '</li><li>';
                        echo anchor('site/register', 'Register');
                        echo '</li>';
                    } else {
                        echo '<li>' . anchor('site/members', 'My Account') . '</li>';
                        echo '<li>';
                        echo anchor('user/logout', 'Logout');
                        echo '</li>';
                    }
                    ?>

                </ul>

            </div>
        </div>
    </div>
</div>



<!-- MAIN CONTENT
================================================== -->