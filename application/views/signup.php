<div class="container">
    <div class="content">

        <div class="section-title"><h6>ساختن حساب کاربری جدید</h6></div>

        <div class="section-content">
            <form class="iform" action="<?php echo base_url(); ?>signup" method="POST"> <!-- "SignUp Form" -->

                <div class="fieldbox">
                    <input type="text" name="username" id="username" placeholder="نام کاربری" class="ifield medium blue eng" value="<?php echo $temp_values['username']; ?>">
                    <label for="username"><i class="fas fa-user"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="password" name="password" id="password" placeholder="کلمه عبور" class="ifield medium blue eng">
                    <label for="password"><i class="fas fa-unlock"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="password" name="re_password" id="re-password" placeholder="تکرار کلمه عبور" class="ifield medium blue eng">
                    <label for="re-password"><i class="fas fa-unlock-alt"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="email" name="email" id="email" placeholder="نشانی ایمیل" class="ifield medium blue eng" value="<?php echo $temp_values['email']; ?>">
                    <label for="email"><i class="fas fa-envelope-open"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="text" name="firstname" id="firstname" placeholder="نام کوچک (فارسی)" class="ifield medium blue" value="<?php echo $temp_values['firstname']; ?>">
                    <label for="firstname"><i class="far fa-id-card"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="text" name="lastname" id="lastname" placeholder="نام خانوادگی (فارسی)" class="ifield medium blue" value="<?php echo $temp_values['lastname']; ?>">
                    <label for="lastname"><i class="fas fa-address-card"></i></label>
                </div>

                <input type="hidden" name="form_status" value="1">
                <input type="submit" class="i-submit medium blue" value="ثبت نام">
            </form> <!-- End of "SignUp Form" -->

            <?php
            if (isset($signup_errors)) { // display the existing errors.
                echo '<div class="alert-box error"> <ul>';
                foreach ($signup_errors as $error) {
                    if (!empty($error))
                        echo '<li>' . $error . '</li>';
                }
                echo '</ul> </div>';
            }
            ?>
        </div>
    </div>