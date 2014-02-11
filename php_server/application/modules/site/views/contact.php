<div class="container">

    <h1>Contact Us</h1>
    <hr />

    <div class="row">
        <div class="col-lg-6">
            <h2>Our Email</h2>
            <p>
                <?php
                echo safe_mailto("tapiwa.maruni@live.com", 'Click Here to Email Us', 'class="btn btn-large btn-primary"');
                ?>
            </p>
        </div>

        <div class="col-lg-6">
            <h3>Street Address and Phone</h3>
            <address>
                <strong>The 3 Musketeers</strong><br />
                street1<br />
                street2<br />
                city, state, zip<br />
                <a href="tel:8503215351">phone</a><br />
            </address>
        </div>

    </div>

</div>
