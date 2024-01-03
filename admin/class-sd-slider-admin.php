<?php
namespace SD\Slider\Admin;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://shwetadanej.com
 * @since      1.0.0
 *
 * @package    Sd_Slider
 * @subpackage Sd_Slider/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sd_Slider
 * @subpackage Sd_Slider/admin
 * @author     Shweta Danej <shwetadanej@gmail.com>
 */
class Sd_Slider_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The title of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_title    The title of this plugin.
	 */
	private $plugin_title;

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_title ) {

		$this->plugin_name = $plugin_name;
		$this->plugin_title = $plugin_title;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sd-slider-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sd-slider-admin.js', array( 'jquery', 'jquery-ui-sortable', 'wp-color-picker' ), $this->version, true );
		wp_localize_script($this->plugin_name, 'sd_obj', array(
			'reorder_nonce_field' => wp_create_nonce('reorder_nonce'),
			'delete_single_slider_image_nonce_field' => wp_create_nonce('delete_single_slider_image_nonce'),
			'delete_all_image_nonce_field' => wp_create_nonce('delete_all_image_nonce'),
			'select_images' => __('Select Slider Images', 'sd-slider'),
			'btn_select' => __('Select', 'sd-slider'),
			'success' => __('Image order has been changed', 'sd-slider'),
			'error' => __('Error occurred while saving the new image order.', 'sd-slider'),
			'confirm' => __('Are you sure you want to remove all images?', 'sd-slider')
		));
	}

	/**
	 * Create new option page for slider
	 *
	 * @return void
	 */
	public function create_admin_menu() {
		add_menu_page(
			$this->plugin_title, 
			$this->plugin_title, 
			'manage_options', 
			'sd_slider', 
			array( $this, 'slider_menu_callback' ),
			'dashicons-cover-image'
		);
	}

	/**
	 * Show slider settings
	 *
	 * @return void
	 */
	public function slider_menu_callback() {
		$slider_images = get_option('sd_slider_images');
		$settings = get_option('sd_slider_options');
		$items = array(
					'single' => __('Single', 'sd-slider'),
					'multiple' => __('Multiple', 'sd-slider')
					);
		$yes_no = array( __('No', 'sd-slider'), __('Yes', 'sd-slider') );
		require_once __DIR__.'/partials/sd-slider-admin-display.php';
	}

	/**
	 * Save slider images and options
	 *
	 * @return void
	 */
	public function save_images() {
		if (isset( $_POST['slider_save_nonce_field'] ) && wp_verify_nonce( $_POST['slider_save_nonce_field'], 'slider_save_action' ) ) {
			if (isset($_POST['save_sd_slider'])) {
				if (!empty($_POST['attachments'])) {
					$arr = $_POST['attachments'];
					$arr = array_map('sanitize_text_field', $arr);
					$slider_images = get_option('sd_slider_images');
					if (!is_array($slider_images)) {
						$slider_images = array();
					}
					foreach ($arr as $key => $value) {
						$counter = count($slider_images);
						$slider_images[$value] = $counter;
						update_option('sd_slider_images', $slider_images);
					}
				}

				$settings = array(
					'items' => sanitize_text_field($_POST['items']),
					'slide_to_show' => (int)sanitize_text_field($_POST['slide_to_show']),
					'slide_to_scroll' => (int)sanitize_text_field($_POST['slide_to_scroll']),
					'center_mode' => !empty(sanitize_text_field($_POST['center_mode'])) ? true : false,
					'autoplay' => !empty(sanitize_text_field($_POST['autoplay'])) ? true : false,
					'bullets' => !empty(sanitize_text_field($_POST['bullets'])) ? true : false,
					'arrows' => !empty(sanitize_text_field($_POST['arrows'])) ? true : false,
					'bullet_color' => sanitize_text_field($_POST['bullet_color']),
					'arrow_color' => sanitize_text_field($_POST['arrow_color']),
				);

				update_option('sd_slider_options', $settings);
			}
		}
	}

	/**
	 * Save new sort order the slider images
	 *
	 * @return void
	 */
	public function save_slider_image_order() {
		$is_valid_request = true;
		$data['status'] = false;
		if (!check_ajax_referer('reorder_nonce', 'reorder_nonce_field')) {
			$is_valid_request = false;
			$data['message'] = __('Invalid request.', 'sd-slider');
		}
		if (!current_user_can('manage_options')) {
			$is_valid_request = false;
			$data['message'] = __('You are not allow to do this!', 'sd-slider');
		}
		if ($is_valid_request) {
			$order = $_POST['order'];
			$o = explode(',', $order);
			$counter = 0;
			$slider_images = get_option('sd_slider_images');
			foreach ($o as $item_id) {
				$slider_images[$item_id] = $counter;
				update_option('sd_slider_images', $slider_images);
				++$counter;
			}
			$data['message'] = __('New image order has been saved.', 'sd-slider');
			$data['status'] = true;
		}
		
		wp_send_json($data);
		wp_die();
	}

	/**
	 * Delete single image from the selected slider images via ajax request
	 *
	 * @return void
	 */
	public function delete_single_slider_image() {

		$is_valid_request = true;
		$data['status'] = false;
		if (!check_ajax_referer('delete_single_slider_image_nonce', 'delete_single_slider_image_nonce_field')) {
			$is_valid_request = false;
			$data['message'] = __('Invalid request.', 'sd-slider');
		}
		if (!current_user_can('manage_options')) {
			$is_valid_request = false;
			$data['message'] = __('You are not allow to do this!', 'sd-slider');
		}

		if ($is_valid_request) {
			$key = $_POST['key'];
			if (!empty($key)) {
				$slider_images = get_option('sd_slider_images');
				if (array_key_exists($key, $slider_images)) {
					unset($slider_images[$key]);
					update_option('sd_slider_images', $slider_images);
					$data['message'] = __('Image deleted.');
					$data['status'] = true;
				} else {
					$data['message'] = __('Image not found or it can not be deleted.');
				}
			}
		}

		wp_send_json($data);
		wp_die();
	}

	/**
	 * Delete all images of the slider via ajax request
	 *
	 * @return void
	 */
	public function delete_all_slider_images() {

		$is_valid_request = true;
		$data['status'] = false;
		if (!check_ajax_referer('delete_all_image_nonce', 'delete_all_image_nonce_field')) {
			$is_valid_request = false;
			$data['message'] = __('Invalid request.', 'sd-slider');
		}
		if (!current_user_can('manage_options')) {
			$is_valid_request = false;
			$data['message'] = __('You are not allow to do this!', 'sd-slider');
		}

		if ($is_valid_request) {
			update_option('sd_slider_images', array());
			$data['message'] = __('Images deleted', 'sd-slider');
			$data['status'] = true;
		}

		wp_send_json($data);
		wp_die();
	}
}
