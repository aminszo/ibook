<?php
/*
  | we used a full html page and also internal style sheet, and did not use the header
  | and footer template parts so in ie and edge we dont load our css and script files.
 */
?>
<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="author" content="AMIN SALEHI ZADE">
        <meta name="description" content="Online Bookmark Service">
        <meta name="keywords" content="Bookmark , Online Bookmark Service">
        <title>IE Not Supported</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                outline: none;
                box-sizing: border-box;
            }
            body {
                background: #ebebeb url("<?php echo base_url(); ?>assets/image/BodyBG.png") repeat;
            }
            .alert-warning {
                width: 100%;
                padding: 15px 20px;
                margin: 20px 0;
                background-color: rgba(248, 233, 21, 0.65);
                font-family: 'Segoe UI', 'Tahoma', 'Geneva', 'Verdana', 'sans-serif';
                font-size: 1.1rem;
                text-align: center;
            }
            p {
                margin: 20px 0;
            }
        </style>
    </head>
    <body>
        <div class="alert-warning">
            <p>این وبسایت از "Internet Explorer" و "Microsoft Edge" پشتیبانی نمیکند.</p>
            <p>برای مشاهده وبسایت از مرورگر دیگری ( مانند
                <a href="https://google.com/chrome" target="_blank">Chrome</a> ،
                <a href="https://opera.com/download" target="_blank">Opera</a> ،
                <a href="https://mozilla.org/firefox" target="_blank">Firefox</a>
                ) استفاده کنید.</p>
        </div>
    </body>
</html>