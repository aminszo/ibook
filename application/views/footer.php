</div> <!-- End of <div class="container"> -->

<footer>

    <div class="footer-item nav-box">
        <a target="_blank" href="<?php echo base_url() ?>info/about">درباره</a>
        <a target="_blank" href="<?php echo base_url() ?>info/guide">راهنما</a>
        <a target="_blank" href="<?php echo base_url() ?>info/contact">تماس</a>
    </div>
    <div class="footer-item brand-box">
        <div><img src="<?php echo base_url() ?>assets/image/favicon.png" alt="ibooka - online bookmark service" class="logo"></div>
        <div><p>آی بوکا - ibooka.ir</p></div>
    </div>
    <div class="footer-item asz-box">
        <div class="box-asz">
            <!-- <img src="<?php echo base_url() ?>assets/image/asz-logo.png"> -->
            <div class="sn-icon telegram">
                <a target="_blank" href="http://t.me/aminosz"><i class="fab fa-telegram-plane"></i></a>
            </div>
            <div class="sn-icon gmail">
                <a target="_blank" href="mailto:amin.salehizade@gmail.com"><i class="fab fa-google"></i></a>
            </div>
        </div>
    </div>

</footer>

</div> <!-- End of <div class="wrapper"> -->

<script src="<?php echo base_url() ?>assets/javascript/jquery.js"></script>
<script src="<?php echo base_url() ?>assets/javascript/script.js"></script>
<?php
if (isset($additional_js)) { // load existing additional javascript files.
    foreach ($additional_js as $style) {
        echo "<link rel='stylesheet' href='" . base_url() . "assets/css/$style.css'>";
    }
}
?>

</body>

</html>