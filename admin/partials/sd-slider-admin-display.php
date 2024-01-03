<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://shwetadanej.com
 * @since      1.0.0
 *
 * @package    Sd_Slider
 * @subpackage Sd_Slider/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h1><?php esc_html_e('SD Slider', 'sd-slider') ?></h1>
    <div id="sort_message"></div>
    <form name="sd_frm" method="post" id="sd_frm">

        <?php wp_nonce_field('slider_save_action', 'slider_save_nonce_field'); ?>

        <table class="form-table">
            <tr class="section_title">
                <td colspan="2"><?php esc_html_e('Slider Images', 'sd-slider'); ?></td>
            </tr>
            <tr>
                <th><?php esc_html_e('Choose Slider Images', 'sd-slider'); ?></th>
                <td>
                    <button id="upload-button" type="button" class="button">
                        <i class="dashicons dashicons-format-gallery"></i><?php esc_html_e(' Choose Images', 'sd-slider') ?>
                    </button>
                    <input id="image-url" type="hidden" name="urls" />
                    <input type='hidden' name='attachments' id='attachments' value=''>
                    <div id="image-prev">
                        <p><?php esc_html_e('No Slider Images', 'sd-slider'); ?></p>
                    </div>
                </td>
            </tr>
        </table>

        <table class="form-table">
            <tr class="section_title">
                <td colspan="2"><?php esc_html_e('Slider Options', 'sd-slider'); ?></td>
            </tr>
            <tr>
                <th>
                    <?php esc_html_e('Items', 'sd-slider'); ?>
                </th>
                <td>
                    <?php
                    foreach ($items as $key => $value) {
                        $selected = $settings['items'] === $key ? 'checked' : '';
                    ?>
                        <input type='radio' class='items' name='items' value='<?php echo esc_attr($key) ?>' <?php echo esc_attr($selected)  ?>> <?php echo esc_html($value) ?>
                    <?php
                    }
                    ?>
                </td>
            </tr>

            <tr class="multiple_settings">
                <th>
                    <?php esc_html_e('Slide to show', 'sd-slider'); ?>
                </th>
                <td>
                    <input type="number" min="1" max="5" name="slide_to_show" value="<?php echo !empty($settings['slide_to_show']) ? esc_attr($settings['slide_to_show']) : 1; ?>">
                </td>
            </tr>

            <tr class="multiple_settings">
                <th>
                    <?php esc_html_e('Slide to scroll', 'sd-slider'); ?>
                </th>
                <td>
                    <input type="number" min="1" max="5" name="slide_to_scroll" value="<?php echo !empty($settings['slide_to_scroll']) ? esc_attr($settings['slide_to_scroll']) : 1; ?>">
                </td>
            </tr>

            <tr class="multiple_settings">
                <th>
                    <?php esc_html_e('Center Mode', 'sd-slider'); ?>
                </th>
                <td>
                    <?php
                    foreach ($yes_no as $key => $value) {
                        $selected_cm = (int)$settings['center_mode'] === $key ? 'checked' : '';
                    ?>
                        <input type='radio' class='center_mode' name='center_mode' value='<?php echo esc_attr($key) ?>' <?php echo esc_attr($selected_cm)  ?>> <?php echo esc_html($value) ?>
                    <?php
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?php esc_html_e('Auto Play', 'sd-slider'); ?>
                </th>
                <td>
                    <?php
                    foreach ($yes_no as $key => $value) {
                        $selected_ap = (int)$settings['autoplay'] === $key ? 'checked' : '';
                    ?>
                        <input type='radio' class='autoplay' name='autoplay' value='<?php echo esc_attr($key) ?>' <?php echo esc_attr($selected_ap)  ?>> <?php echo esc_html($value) ?>
                    <?php
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?php esc_html_e('Bullets', 'sd-slider'); ?>
                </th>
                <td>
                    <?php
                    foreach ($yes_no as $key => $value) {
                        $selected_bullet = (int)$settings['bullets'] === $key ? 'checked' : '';
                    ?>
                        <input type='radio' class='bullets' name='bullets' value='<?php echo esc_attr($key) ?>' <?php echo esc_attr($selected_bullet)  ?>> <?php echo esc_html($value) ?>
                    <?php
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?php esc_html_e('Arrows', 'sd-slider'); ?>
                </th>
                <td>
                    <?php
                    foreach ($yes_no as $key => $value) {
                        $selected_arrow = (int)$settings['arrows'] === $key ? 'checked' : '';
                    ?>
                        <input type='radio' class='arrows' name='arrows' value='<?php echo esc_attr($key) ?>' <?php echo esc_attr($selected_arrow)  ?>> <?php echo esc_html($value) ?>
                    <?php
                    }
                    ?>
                </td>
            </tr>

            <tr>
                <th>
                    <?php esc_html_e('Bullet Color', 'sd-slider'); ?>
                </th>
                <td>
                    <input type="text" name="bullet_color" class="colors" value="<?php echo esc_attr(!empty($settings['bullet_color']) ? $settings['bullet_color'] : '#000') ?>">
                </td>
            </tr>

            <tr>
                <th>
                    <?php esc_html_e('Arrow Color', 'sd-slider'); ?>
                </th>
                <td>
                    <input type="text" name="arrow_color" class="colors" value="<?php echo esc_attr(!empty($settings['arrow_color']) ? $settings['arrow_color'] : '#000') ?>">
                </td>
            </tr>
        </table>

        <div class="sd_info">
            <h3><?php esc_html_e('How to use ?', 'sd-slider') ?></h3>
            <h4><?php esc_html_e('Use [sd-slideshow] shortcode in your post/page to display this slider.', 'sd-slider') ?></h4>
        </div>
        <input type="submit" name="save_sd_slider" id="save_sd_slider" value="<?php echo esc_attr__('Save Slider', 'sd-slider') ?>" class="button button-primary" />
        <a id="delete_all_images" class="button button-primary"> <?php echo esc_html__('Delete All Images', 'sd-slider') ?> </a>

    </form>

    <div id="image-sort">
        <?php
        if ($slider_images) {
            asort($slider_images);
        ?>
            <?php
            echo '<ul id="slider_images">';
            foreach ($slider_images as $key => $value) {
            ?>
                <li id='<?php echo esc_attr($key) ?>'>
                    <div class='sd_delete'><a href='javascript:void(0);'>x</a></div><img class='image-preview' src='<?php echo esc_attr(wp_get_attachment_url($key)); ?>'>
                </li>
            <?php
            }
            echo '</ul>';
            ?>
        <?php
        }
        ?>

    </div>
</div>