


<?php if(array_key_exists("app.produce", $_ENV)) :?>
    <script src="<?php echo site_furl('assets/js/worker.js?t='.time());?>"></script>
    <script src="<?php echo site_furl('assets/js/main-script.js?t='.time());?>"></script>
<?php else : ?>
    <script src="<?php echo site_furl('assets/js/worker.js?v=1');?>"></script>
    <script src="<?php echo site_furl('assets/js/main-script.js?v=1');?>"></script>
<?php endif ?>

</body>
</html>