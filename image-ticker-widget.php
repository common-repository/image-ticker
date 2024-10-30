<?php
namespace ITFE;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Image_Ticker
 */
class Image_Ticker extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     *
     * @return string
     */
    public function get_name() {
        return 'itfe_image_ticker';
    }

    /**
     * Get widget title.
     *
     * @return string
     */
    public function get_title() {
        return esc_html__('Image Ticker', 'image-ticker-for-elementor');
    }

    /**
     * Get widget icon.
     *
     * @return string
     */
    public function get_icon() {
        return 'eicon-slides';
    }

    /**
     * Get widget categories.
     *
     * @return array
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'image-ticker-for-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'images',
            [
                'label' => esc_html__('Add Images', 'image-ticker-for-elementor'),
                'type' => \Elementor\Controls_Manager::GALLERY,
                'default' => [],
                'description' => esc_html__('Select and manage the images you want to include in the slider. You can add, remove, or reorder images here.', 'image-ticker-for-elementor'),
            ]
        );

        $this->add_control(
            'image_resolution',
            [
                'label' => esc_html__('Image Resolution', 'image-ticker-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => esc_html__('Thumbnail - 150 x 150', 'image-ticker-for-elementor'),
                    'medium' => esc_html__('Medium - 300 x 300', 'image-ticker-for-elementor'),
                    'medium_large' => esc_html__('Medium Large - 768 x 0', 'image-ticker-for-elementor'),
                    'large' => esc_html__('Large - 1024 x 1024', 'image-ticker-for-elementor'),
                    '1536x1536' => esc_html__('1536x1536 - 1536 x 1536', 'image-ticker-for-elementor'),
                    '2048x2048' => esc_html__('2048x2048 - 2048 x 2048', 'image-ticker-for-elementor'),
                    'full' => esc_html__('Full', 'image-ticker-for-elementor'),
                ],
                'default' => 'thumbnail',
                'description' => esc_html__('Choose the resolution for your images. This setting affects the display size and quality of the images.', 'image-ticker-for-elementor'),
            ]
        );

        $this->add_control(
            'direction',
            [
                'label' => esc_html__('Direction', 'image-ticker-for-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'left-to-right' => esc_html__('Left to Right', 'image-ticker-for-elementor'),
                    'right-to-left' => esc_html__('Right to Left', 'image-ticker-for-elementor'),
                ],
                'default' => 'left-to-right',
                'description' => esc_html__('Set the direction in which the images will move. Options include left to right or right to left.', 'image-ticker-for-elementor'),
            ]
        );

        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Speed (1 = Slower, 30 = Faster)', 'image-ticker-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 20,
                'min' => 1,
                'max' => 30,
                'step' => 1,
                'description' => esc_html__('Adjust the speed at which the images scroll. A lower number makes the scroll slower, while a higher number makes it faster.', 'image-ticker-for-elementor'),
            ]
        );

        $this->add_control(
            'image_width',
            [
                'label' => esc_html__('Image Width (px)', 'image-ticker-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 100,
                'min' => 10,
                'max' => 1000,
                'step' => 1,
                'description' => esc_html__('Specify the width of each image in pixels. This determines how wide each image will appear in the slider.', 'image-ticker-for-elementor'),
            ]
        );

        $this->add_control(
            'spacing',
            [
                'label' => esc_html__('Spacing (px)', 'image-ticker-for-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 30,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'description' => esc_html__('Set the space between images in pixels. This controls the gap between each image in the slider.', 'image-ticker-for-elementor'),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $image_resolution = $settings['image_resolution'];
        $direction = $settings['direction'];
        $speed = $settings['speed'];
        $image_width = $settings['image_width'];
        $spacing = $settings['spacing'];
        ?>
        <div class="itfe-image-ticker-container itfe-image-ticker-<?php echo esc_attr($direction); ?>" data-speed="<?php echo esc_attr($speed); ?>" data-image-width="<?php echo esc_attr($image_width); ?>" data-spacing="<?php echo esc_attr($spacing); ?>">
            <div class="itfe-image-ticker-content">
                <?php echo wp_kses_post($this->get_images_html($settings['images'], $image_width, $spacing, $image_resolution)); ?>
            </div>
        </div>
        <?php
    }

    /**
     * Get images HTML.
     *
     * @param array $images List of images.
     * @param int $image_width Width of images.
     * @param int $spacing Spacing between images.
     * @param string $image_resolution Resolution of images.
     * @return string
     */
    private function get_images_html($images, $image_width, $spacing, $image_resolution) {
        $html = '';
        if (!empty($images)) {
            foreach ($images as $image) {
                $img_src = wp_get_attachment_image_url($image['id'], $image_resolution);
                if ($img_src) {
                    $html .= '<img src="' . esc_url($img_src) . '" class="attachment-' . esc_attr($image_resolution) . ' size-' . esc_attr($image_resolution) . '" alt="' . esc_attr__('Image Ticker', 'image-ticker-for-elementor') . '" style="width:' . esc_attr($image_width) . 'px;height:auto;margin-right:' . esc_attr($spacing) . 'px;">';
                } else {
                    // Fallback in case the selected resolution is not available
                    $full_img_src = wp_get_attachment_image_url($image['id'], 'full');
                    $html .= '<img src="' . esc_url($full_img_src) . '" class="attachment-full size-full" alt="' . esc_attr__('Image Ticker', 'image-ticker-for-elementor') . '" style="width:' . esc_attr($image_width) . 'px;height:auto;margin-right:' . esc_attr($spacing) . 'px;">';
                }
            }
        }
        return $html;
    }
}