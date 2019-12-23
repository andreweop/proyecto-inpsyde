jQuery(document).ready( function( $ ) {

    $('#upload_image_button').click(function() {

       var formfield = $('#overview_worker_image').val();
        tb_show( '', 'media-upload.php?type=image&amp;TB_iframe=true' );
        window.send_to_editor = function(html) {
          var imgurl = $(html).attr('src');
           $('#overview_worker_image').val(imgurl);
           tb_remove();
        }

        return false;
    });

});
