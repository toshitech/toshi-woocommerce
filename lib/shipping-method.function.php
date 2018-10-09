<?php

function setup_toshi_shipping_method()
{
    function toshi_shipping_method()
    {
        if (!class_exists('Toshi_Shipping_Method')) {
            class Toshi_Shipping_Method extends WC_Shipping_Method
            {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct($instance_id = 0)
                {
                    $this->id = 'toshi';
                    $this->instance_id = absint( $instance_id );
                    $this->method_title = __('Toshi Concierge', 'toshi');
                    $this->method_description = __('Toshi Concierge shipping method.', 'toshi');
                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('Toshi Concierge', 'toshi');
                    $this->supports = array(
                        'shipping-zones',
                        'instance-settings',
                        'instance-settings-modal',
                        'settings'
                    );
                    $this->init();
                }

//                public function supports($feature)
                //                {
                //                    return in_array($feature, array('settings', 'shipping-zones'));
                //                }

                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings in admin if you have any defined
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                function init_form_fields()
                {
                    $this->form_fields = array('api_key' => array(
                        'title' => __('API Key', 'toshi'),
                        'type' => 'text',
                        'description' => __('Contact TOSHI to receive your key', 'toshi'),
                        'default' => __('', 'toshi'),
                    ),

                    'api_url' => array(
                        'title' => __('API URL', 'toshi'),
                        'type' => 'text',
                        'description' => __('API base URL', 'toshi'),
                        'default' => __('', 'toshi'),
                    ));

                    $this->instance_form_fields = array(
                        'enabled' => array(
                            'title' => __('Enable', 'toshi'),
                            'type' => 'checkbox',
                            'description' => __('Enable this shipping.', 'toshi'),
                            'default' => 'yes',
                        ),

                        'title' => array(
                            'title' => __('Title', 'toshi'),
                            'type' => 'text',
                            'description' => __('Title to be display on site', 'toshi'),
                            'default' => __('Toshi Concierge', 'toshi'),
                        ),
                    );
                }

                /**
                 * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping($package = array())
                {
                    $rate = array(
                        'id' => $this->id,
                        'label' => 'TOSHI',
                        'cost' => '0.00',
                        'calc_tax' => 'per_item',
                    );

                    $this->add_rate($rate);
                }
            }
        }
    }

    add_action('woocommerce_shipping_init', 'toshi_shipping_method');

    function add_toshi_shipping_method($methods)
    {
        $methods['toshi'] = 'Toshi_Shipping_Method';
        return $methods;
    }

    add_filter('woocommerce_shipping_methods', 'add_toshi_shipping_method');
}

add_action('woocommerce_after_shipping_rate', function (WC_Shipping_Rate $shipping) {
    if ($shipping->get_method_id() === 'toshi' && is_checkout()) {
        ?>
        <a href="/" id="js-toshi-select-delivery-button"
           onclick="window.wp_toshi_plugin.showModal(event)">Select
            Delivery</a>
        <script type="text/javascript">
            window.wp_toshi_plugin.configure({
                apiKey: '<?php echo get_option('woocommerce_toshi_settings')['api_key']; ?>',
                cart: <?php echo json_encode(toshi_get_checkout_data()); ?>
            });
        </script>
        <style type="text/css">
            .toshi__woo_modal_overlay, .toshi__woo_modal {
                display: none;
            }

            .toshi__woo_modal {
                background: white;
                position: relative;         
                width: 60%;
                left: 20%;
                top: calc(50% - 40vh);
                z-index: 1000;
            }

            @media all and (max-width: 720px) {
                .toshi__woo_modal {
                    width: 90%;
                    left: 5%;
                }
            }

            .toshi__woo_modal .toshi__woo_modal__close {
                background: url(<?php echo plugins_url('./images/close.png', __FILE__) ?>);
                background-size: contain;
                -moz-background-size: contain;
                -o-background-size: contain;
                -webkit-background-size: contain;
                width: 40px;
                height: 40px;
                position: absolute;
                top: -20px;
                left: -20px;
                z-index: 1100;
            }

            .toshi__woo_modal > div {
                position: relative;
            }

            .toshi__woo_modal_overlay {
                position: fixed;
                top: 0;
                left: 0;
                background: rgba(0, 0, 0, .8);
                width: 100vw;
                height: 100vh;
                z-index: 999;
                overflow-y: auto;
                box-sizing: border-box;
                padding-bottom: 60px;
                padding-top: 60px;
            }
        </style>
        <div id="js-toshi-modal-overlay" class="toshi__woo_modal_overlay">
        <div id="js-toshi-modal-window" class="toshi__woo_modal">
            <a href="#" id="js-toshi-close" class="toshi__woo_modal__close"></a>
            <div id="js-toshi-app"></div>
        </div>
        </div>
        <?php
}
});
