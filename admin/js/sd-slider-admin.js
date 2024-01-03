(function ($) {
    'use strict';

    $(function () {

        var sd_slider = {
            init: function () {
                $('#upload-button').on('click', this.upload_image);
                $('#image-sort .sd_delete').on('click', this.delete_single_slider_image);
                $('#image-prev').on('click', '.img a', this.delete_image_prev);
                $('#delete_all_images').on('click', this.delete_all_slider_images);
                sd_slider.check_multiple();
                $('input[type=radio][name=items]').on("change", this.check_multiple);

                sd_slider.apply_image_sortable();
                $('.colors').wpColorPicker();
            },
            apply_image_sortable: function () {
                var sortList = $('ul#slider_images');
                sortList.sortable({
                    update: function (event, ui) {
                        $.ajax({
                            'url': ajaxurl,
                            'type': 'POST',
                            'dataType': 'json',
                            'data': { 
                                'action': 'save_slider_image_order', 
                                'order': sortList.sortable('toArray').toString(), 
                                'reorder_nonce_field': sd_obj.reorder_nonce_field 
                            },
                            success: function (response) {
                                $('div#message').remove();
                                if (true === response.status) {
                                    $("#sort_message").append('<div id="message" class="updated below-h2"><p>' + sd_obj.success + '</p></div>');
                                } else {
                                    $("#sort_message").append('<div id="message" class="error below-h2"><p>' + sd_obj.error + '</p></div>');
                                }
                            }
                        });
                    }
                });
            },
            upload_image: function (e) {
                e.preventDefault();
                var mediaUploader;
                if (typeof mediaUploader !== 'undefined') {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: sd_obj.select_images,
                    button: {
                        text: sd_obj.btn_select
                    }, multiple: true
                });

                mediaUploader.on('select', function () {
                    var media = mediaUploader.state().get('selection').models;
                    var images = new Array();
                    var html = '';
                    jQuery.each(media, function (i, val) {
                        images.push(val.attributes.id);
                        html += "<div class='img' data-attr='" + val.attributes.id + "'><div class='sd_delete'><a href='javascript:void(0)'>x</a></div><img src='" + val.attributes.url + "' height='70' width='70'><input type='hidden' name='attachments[]' value='" + val.attributes.id + "'></div>";
                    });
                    $('#image-prev p').hide();
                    $('#image-prev').append(html);
                });
                mediaUploader.open();
            },
            delete_single_slider_image: function () {
                var key = $(this).parent().attr('id');
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: { 
                        'action' : 'delete_single_slider_image', 
                        'key' : key, 
                        'delete_single_slider_image_nonce_field' : sd_obj.delete_single_slider_image_nonce_field 
                    },
                    success: function (response) {
                        if (true === response.status) {
                            $("#slider_images li#" + key).remove();
                        } else {
                            alert(data.message);
                        }
                    }
                });
            },
            check_multiple: function () {
                if ($('input[name=items]:checked').val() == "single") {
                    $(".multiple_settings").hide();
                    $("#fade").show();
                } else {
                    $(".multiple_settings").show();
                    $("#fade").hide();
                }
            },
            delete_all_slider_images: function () {
                var ans = confirm(sd_obj.confirm);
                if (ans) {
                    $.ajax({
                        'url': ajaxurl,
                        'type': 'POST',
                        'data': { 
                            'action': 'delete_all_slider_images', 
                            'delete_all_image_nonce_field': sd_obj.delete_all_image_nonce_field
                        },
                        success: function (response) {
                            if (true === response.status) {
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        }
                    });
                }
            },
            delete_image_prev: function () {
                $(this).closest(".img").remove().fadeOut();
                if ($('#image-prev .img').length == 0) {
                    $('#image-prev p').fadeIn();
                }
            }
        }
        sd_slider.init();

    });

})(jQuery);
