<div class="container">
    <div class="content">

        <div class="section-title"><h6>ویرایش حساب کاربری</h6></div>

        <div class="section-content">

            <form class="iform" action="<?php echo base_url(); ?>account" method="POST"> <!-- "Edit Account Form" -->

                <?php
                if (isset($edit_logs) && !empty($edit_logs)) // display the existing logs.
                    foreach ($edit_logs as $log)
                        if (!empty($log))
                            echo "<div class='alert-box " . $log['class'] . "'>" . $log['message'] . "</div>";
                ?>

                <div class="fieldbox tooltip-container">
                    <input type="text" id="username" placeholder="نام کاربری" class="ifield medium orange eng" disabled="disabled" value="<?php echo $current_user['username']; ?>">
                    <label for="username"><i class="fas fa-user"></i></label>
                    <span class="tooltip-box">نام کاربری قابل تغییر نیست</span>
                </div>

                <div class="fieldbox tooltip-container">
                    <input type="email" name="email" id="email" placeholder="نشانی ایمیل" class="ifield medium orange eng" disabled="disabled" value="<?php echo $current_user['email']; ?>">
                    <label for="email"><i class="fas fa-envelope-open"></i></label>
                    <span class="tooltip-box">نشانی ایمیل قابل تغییر نیست</span>
                </div>

                <div class="fieldbox">
                    <input type="password" name="old_password" id="old-password" placeholder="کلمه عبور فعلی (اگر نمیخواهید تغییر بدهید، خالی بگذارید)" class="ifield medium orange eng">
                    <label for="old-password"><i class="fas fa-lock"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="password" name="password" id="password" placeholder="کلمه عبور جدید" class="ifield medium orange eng">
                    <label for="password"><i class="fas fa-unlock"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="password" name="re_password" id="re-password" placeholder="تکرار کلمه عبور جدید" class="ifield medium orange eng">
                    <label for="re-password"><i class="fas fa-unlock-alt"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="text" name="firstname" id="firstname" placeholder="نام کوچک (فارسی)" class="ifield medium orange" value="<?php echo $current_user['firstname']; ?>">
                    <label for="firstname"><i class="far fa-id-card"></i></label>
                </div>

                <div class="fieldbox">
                    <input type="text" name="lastname" id="lastname" placeholder="نام خانوادگی (فارسی)" class="ifield medium orange" value="<?php echo $current_user['lastname']; ?>">
                    <label for="lastname"><i class="fas fa-address-card"></i></label>
                </div>

                <input type="hidden" name="form_status" value="1">
                <input type="submit" class="i-submit orange" value="ثبت تغییرات">
            </form> <!-- End of "Edit Account Form" -->

            <?php
            if (isset($edit_errors) && !empty($edit_errors)) { // display the existing errors.
                echo '<div class="alert-box error"> <ul>';
                foreach ($edit_errors as $error) {
                    if (!empty($error))
                        echo '<li>' . $error . '</li>';
                }
                echo '</ul> </div>';
            }
            ?>

        </div>
    </div>