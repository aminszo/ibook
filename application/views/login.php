<div class="container">
    <div class="content">

        <div class="section-title"><h6>ورود به حساب کاربری</h6></div>

        <div class="section-content">

            <form class="iform" action="<?php echo base_url(); ?>login" method="POST"> <!-- "Login Form" -->

                <div class="fieldbox">
                    <input type="text" name="username" id="username" placeholder="نام کاربری" class="ifield eng">
                    <label for="username"><i class="fas fa-user"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="password" name="password" id="password" placeholder="کلمه عبور" class="ifield eng">
                    <label for="password"><i class="fas fa-key"></i></label>
                </div>

                <input type="hidden" name="form_status" value="1">
                <input type="submit" class="i-submit" value="ورود">

            </form> <!-- End of "Login Form" -->

            <?php
            // display the login error(s) that occurred.
            if (isset($login_errors)) {
                echo '<div class="alert-box error"> <ul>';
                foreach ($login_errors as $error) {
                    if (!empty($error))
                        echo '<li>' . $error . '</li>';
                }
                echo '</ul> </div>';
            }
            // if user is logged out right now (redirected from logout page) , show a message.
            if ($just_logout) {
                echo '<div class="alert-box warning">شما از حساب کاربری خود خارج شدید.</div>';
                session_unset();
                session_destroy();
            }
            ?>

        </div>
    </div>