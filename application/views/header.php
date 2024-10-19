<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="AMIN SALEHI ZADE">
        <meta name="description" content="آی بوکا ، سرویس مدیریت بوکمارک آنلاین">
        <meta name="keywords" content="Bookmark , Online Bookmark Service , ibooka , آی بوکا , سرویس مدیریت آنلاین بوکمارک">
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/image/favicon.png">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/theme.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/font/fontawesome/fontawesome-all.css">
        <?php
        if (isset($additional_css)) { // load existing additional css files.
            foreach ($additional_css as $style) {
                echo "<link rel='stylesheet' href='" . base_url() . "assets/css/$style.css'>";
            }
        }
        ?>
        <title><?php echo 'آی بوکا - ' . $title ?></title>
    </head>

    <body>
        <div class="wrapper">

            <header>
                <nav>
                    <ul class="navbar">
                        <?php if ($login_status) { // display proper buttons on navbar ?>
                            <li class="nav-item right"><a href="<?php echo base_url(); ?>dashboard">داشبورد</a></li>
                            <li class="nav-item left dropdown"><a>حساب کاربری</a>
                                <div class="dropdown-list">
                                    <a href="<?php echo base_url(); ?>account">ویرایش</a>
                                    <a href="<?php echo base_url(); ?>logout">خروج</a>
                                </div>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item right"><a href="<?php echo base_url(); ?>">خانه</a></li>
                            <li class="nav-item left"><a href="<?php echo base_url(); ?>login">ورود</a></li>
                            <li class="nav-item left"><a href="<?php echo base_url(); ?>signup">ثبت نام</a></li>
                            <?php } ?>
                    </ul>
                </nav>
            </header>