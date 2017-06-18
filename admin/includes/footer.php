<footer class="col-md-12 text-center" id="footer">&copy; Copyright 2017 'Ocean' Bon Magasin</footer>

<script>
    function get_child_options() {
        var parentID = jQuery('#parent').val();
        jQuery.ajax({
            url: '/admin/parsers/child_categories.php',
            type: 'POST',
            data: {parentID : parentID},
            success: function (data) {
                jQuery('#child').html(data);
            },
            error: function () {
                alert("Что-то не так с дочерней опцией")},
        });
    }
    jQuery('select[name="parent"]').change(get_child_options);
</script>
</body>
</html>
