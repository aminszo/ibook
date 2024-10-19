<?php
// this is a simple '404 Page' that only contains a 'not found' message and a 'back to home' button.
?>
<div class="container">
    <div class="content">

        <div class="section-title"><h6 style="text-align: center;">404</h6></div>

        <div class="section-content">
            <div class="alert-box warning" style="text-align: center;">صفحه مورد نظر پیدا نشد! </div>

            <a href="<?php echo base_url(); ?>home">
                <button class="button-1">
                    <span>بازگشت به خانه</span><i class="far fa-arrow-square-left"></i>
                </button>
            </a>
        </div>

    </div>