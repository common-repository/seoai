jQuery(document).ready(function($) {
    "use strict";
    $('#seoai-form input').on('change', function() {
        let a = $('input[name=seoai_option_saveimage]:checked', '#seoai-form').val();
        if (a == 1)
            $('#row-featured').show('fade');
        else
            $('#row-featured').hide('fade');
    });
});

function seoai_spin(id) {
    jQuery('#icon-status-' + id).addClass('icon-spining');
    jQuery.ajax({
        type: "POST",
        url: "admin-ajax.php",
        data: {
            action: 'seoai_spin_content',
            id: id
        },
        dataType: "json",
        success: function(response) {
            if (response.status == 'success') {
                jQuery('#icon-status-' + id).removeClass('icon-spining');
                jQuery('#icon-status-' + id).addClass('icon-spined');
                toastr.success(response.msg, 'Thành công', { timeOut: 2000, positionClass: "toast-bottom-right", })
            } else {
                jQuery('#icon-status-' + id).removeClass('icon-spining');
                jQuery('#icon-status-' + id).removeClass('icon-spined');
                toastr.error(response.msg, 'Đã có lỗi', { timeOut: 2000, positionClass: "toast-bottom-right", })
            }
        }
    });
}

function checkApiKey() {
    let apikey = jQuery('#apikey').val();
    jQuery.ajax({
        type: "POST",
        url: "admin-ajax.php",
        data: {
            action: 'seoai_check_apikey',
            apikey: apikey
        },
        dataType: "json",
        success: function(response) {
            if (response.status == 'success') {
                toastr.success(response.msg, 'Thành công', { timeOut: 2000, positionClass: "toast-bottom-right", })
            } else {
                toastr.error(response.msg, 'Đã có lỗi', { timeOut: 2000, positionClass: "toast-bottom-right", })
            }
        }
    });
}

jQuery(document).ready(function($) {
    var file_frame;
    var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
    jQuery('#upload_image_button').on('click', function(event) {
        event.preventDefault();
        if (file_frame) {
            file_frame.open();
            return;
        }
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Chọn ảnh đại diện',
            button: {
                text: 'Chọn ảnh này',
            },
            multiple: false
        });
        file_frame.on('select', function() {
            attachment = file_frame.state().get('selection').first().toJSON();
            $('#image-preview').attr('src', attachment.url).css('width', 'auto');
            $('#image_attachment_id').val(attachment.id);
            wp.media.model.settings.post.id = wp_media_post_id;
        });
        file_frame.open();
    });
    jQuery('a.add_media').on('click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });
});