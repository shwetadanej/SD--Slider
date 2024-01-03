<?php
use SD\Slider\Admin\Sd_Slider_Admin;
use PHPUnit\Framework\TestCase;

class SDSliderTest extends TestCase {

    public function setUp(): void {
        $_POST = [];
        parent::setUp();
        WP_Mock::setUp();
    }

    public function tearDown(): void {

        WP_Mock::tearDown();
        Mockery::close();
        parent::tearDown();
    }

    public function testSaveMetaBox() {

        WP_Mock::userFunction('wp_verify_nonce', [
            'return' => true,
        ]);

        $slider_images = WP_Mock::userFunction('get_option', [
            'return' => [
                68 => 3, 
                65 => 4, 
                66 => 5
            ],
        ]);

        WP_Mock::userFunction('update_option', [
            'times' => 'atLeast',
            'args' => ['sd_slider_images', Mockery::type('array')],
        ]);
    
        WP_Mock::userFunction('sanitize_text_field', [
            'return' => 'sanitized_value',
        ]);

        $_POST['slider_save_nonce_field'] = 'slider_save_action';
        $_POST['save_sd_slider'] = true;
        $_POST['attachments'] = [80,90];
        $_POST['items'] = 'multiple';
        $_POST['slide_to_show'] = '3';
        $_POST['slide_to_scroll'] = '2';
        $_POST['center_mode'] = true;
        $_POST['autoplay'] = true;
        $_POST['bullets'] = true;
        $_POST['arrows'] = true;
        $_POST['bullet_color'] = "#ddd";
        $_POST['arrow_color'] = '#ddd';
       
        WP_Mock::userFunction('update_option', [
            'times' => 1,
            'args' => ['sd_slider_options', Mockery::type('array')],
        ]);

        $sd_slider = new Sd_Slider_Admin("sd-slider", "1.0.0", "SD Slider");
        $sd_slider->save_images();
    }
}