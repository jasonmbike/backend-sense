    </div>
    </div>
    </div>
    </div>
    </div>


<footer>
    
</footer>
<script>
    var base_url = "<?= base_url(); ?>";
</script>
<?php
if (is_array($datalibrary['libjs'])) {
    foreach ($datalibrary['libjs'] as $vista) {
        
        echo view($vista);
    }
}
?>



<!-- Bootstrap 5.0-->
<script src="<?= base_url() ?>public/styles/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="<?= base_url() ?>public/styles/menu_principal/js/color_mode.js"></script>
<script src="<?= base_url() ?>public/styles/menu_principal/js/menu_principal.js"></script>
<script>
    $(document).ready(function() {
        $('body').css("overflow", "");
    });

    
</script>


</body>

</html>