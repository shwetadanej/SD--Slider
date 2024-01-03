<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://shwetadanej.com
 * @since      1.0.0
 *
 * @package    Sd_Slider
 * @subpackage Sd_Slider/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Sd_Slider
 * @subpackage Sd_Slider/public
 * @author     Shweta Danej <shwetadanej@gmail.com>
 */
class Sd_Slider_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sd_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sd_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sd-slider-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'owl-carousel-min-css', plugin_dir_url( __FILE__ ) . 'css/owl.carousel.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'owl-carousel-theme-default-min-css', plugin_dir_url( __FILE__ ) . 'css/owl.theme.default.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Sd_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sd_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'owl-carousel-min-js', plugin_dir_url( __FILE__ ) . 'js/owl.carousel.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sd-slider-public.js', array( 'jquery', 'owl-carousel-min-js' ), $this->version, true );
		$settings = get_option('sd_slider_options');
		$slider_images = get_option('sd_slider_images');
		wp_localize_script($this->plugin_name, 'sd_obj', array(
			'slider' => $settings,
			'slider_status' => !empty($slider_images) ? 'active' : 'inactive',
		));
	}

	/**
	 * Enqueued nav colors of the slider 
	 *
	 * @return void
	 */
	public function enqueue_slider_style() {
		$settings = get_option('sd_slider_options');
		if (isset($settings['bullet_color']) || isset($settings['arrow_color'])) {
			$arrow_color = (string) isset($settings['arrow_color']) ? $settings['arrow_color'] : '#000';
			$bullet_color = (string) isset($settings['bullet_color']) ? $settings['bullet_color'] : '#000';
			?>
			<style>
				.owl-theme .owl-nav [class*=owl-] {
					color: <?php echo esc_attr($arrow_color); ?> !important;
				}
	
				.owl-theme .owl-dots .owl-dot span {
					background: <?php echo esc_attr($bullet_color); ?> !important;
				}
			</style>
			<?php
		}
	}

	/**
	 * Slide show of the slider
	 *
	 * @since    1.0.0
	 * @return string
	 */
	public function sd_slideshow_callback() {
		ob_start();
        $slider_images = get_option('sd_slider_images');
		if ($slider_images) {
			asort($slider_images);
			?>
			<div class="owl-carousel owl-theme">
			<?php
			foreach ($slider_images as $key => $value) {
				?>
				<div class='item'><img src='<?php echo esc_url(wp_get_attachment_url($key)) ?>'/></div>
				<?php
			}
			?>
			</div>
			<?php
		} else {
			esc_html_e('No slider images are selected for this slider.');
		}
		?>
		<?php
		return ob_get_clean();
	}
}
