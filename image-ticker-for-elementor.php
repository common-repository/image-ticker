<?php
/**
 * Plugin Name: Image Ticker for Elementor
 * Requires Plugins: elementor
 * Description: Add a customizable, marquee-style image ticker to your Elementor pages. Enhance your site with smoothly scrolling images to capture visitors' attention.
 * Version: 1.2
 * Author: Prashant Deshmukh
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: image-ticker-for-elementor
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.2
 * Elementor tested up to: 3.19
 * Elementor Pro tested up to: 3.19
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class ITFE_Image_Ticker {
    function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'itfe_enqueue_scripts'));
    }

    public function itfe_enqueue_scripts() {
        if ( is_admin() ) {
            return;
        }
        $version = '1.2';
        wp_enqueue_style('itfe-image-ticker-css', plugins_url('itfe-image-ticker.css', __FILE__), array(), $version);
        wp_enqueue_script('itfe-image-ticker-js', plugins_url('itfe-image-ticker.js', __FILE__), array(), $version, true);
    }
}

new ITFE_Image_Ticker();

function itfe_register_image_ticker($widgets_manager) {
    if (class_exists('\Elementor\Widget_Base')) {
        require_once plugin_dir_path( __FILE__ ) . '/image-ticker-widget.php';
        $widgets_manager->register(new \ITFE\Image_Ticker());
    }
}

add_action('elementor/widgets/register', 'itfe_register_image_ticker');