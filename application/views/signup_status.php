<div class="container"> 
    <div class="content">

        <div class="section-content">
            <?php
            // we have one view file for different cases, and we switch on cases 
            // depending on the given status, to display the appropriate content.
            switch ($status) {
                case 'success' :
                    ?>
                    <div class="alert-box">
                        کاربر گرامی، ثبت نام شما با موفقیت انجام شد.  و لینک فعالسازی حساب به ایمیل شما ارسال شده است.
                        <br>
                        برای تکمیل کردن فرایند ثبت نام، به ایمیل خود مراجعه کرده و روی لینک فعالسازی کلیک کنید.
                    </div>
                    <?php
                    break;

                case 'error' :
                    ?>
                    <div class="alert-box warning">
                        <?php echo $verify_error; // display the error that is passed through $data from controller ?> 
                    </div>
                    <?php
                    break;

                case 'finish' :
                    ?>
                    <div class="alert-box success">
                        کاربر گرامی،
                        <?php echo $current_user['firstname'] . ' ' . $current_user['lastname']; ?>
                        فعالسازی حساب شما با موفقیت انجام شد.
                    </div>
                    <a href="<?php echo base_url(); ?>dashboard">
                        <button class="button-1">
                            <span>محیط کاربری شما</span><i class="far fa-arrow-square-left"></i>
                        </button>
                    </a>
                    <?php
                    break;
            }
            ?>

        </div>
    </div>