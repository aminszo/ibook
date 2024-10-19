<div dir="rtl" style="text-align: right; font-family: tahoma; font-size: 18px; padding:5px; display:inline-block; border: 2px solid black;">
    برای فعالسازی حساب کاربری تان در آی بوکا، روی لینک زیر کلیک کنید. <br>
    این لینک تا 12 ساعت اعتبار دارد.<br><br>
    <a style="color: green;" href="<?php echo base_url() . "signup/verify?" . "i=" . $user_info['id'] . "&u=" . $user_info['username'] . "&h="; ?>" target="_blank">لینک فعالسازی</a><br><br>
    اطلاعات حساب کاربری شما در آی بوکا : <br>
    username : &nbsp; <?php echo $user_info['username']; ?> <br>
    password : &nbsp; <?php echo $user_info['_password']; ?> <br><br>
    <a style="color: mediumseagreen;" target="_blank" href="<?php echo base_url(); ?>">آی بوکا</a>
</div>
