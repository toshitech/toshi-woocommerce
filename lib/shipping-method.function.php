<?php
/**
 * Sets up the Toshi WooCommerce shipping method.
 *
 * Per WooCommerce's API we also configure shipping option settings here too.
 * This is augmented by additional controls defined in 'settings/settings-controls.php'
 */
function setup_toshi_shipping_method()
{
    function toshi_shipping_method()
    {
        if (!class_exists('Toshi_Shipping_Method')) {
            class Toshi_Shipping_Method extends WC_Shipping_Method
            {
                /**
                 * @access public
                 * @return void
                 */
                public function __construct($instance_id = 0)
                {
                    $this->id = 'toshi';
                    $this->instance_id = absint($instance_id);
                    $this->method_title = __('Toshi Concierge', 'toshi');
                    $this->method_description = __('Toshi Concierge shipping method.', 'toshi');
                    $this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
                    $this->title = isset($this->settings['title']) ? $this->settings['title'] : __('Toshi Concierge', 'toshi');
                    $this->supports = array(
                        'shipping-zones',
                        'instance-settings',
                        'instance-settings-modal',
                        'settings',
                    );
                    $this->init();
                }

                /**
                 * Init settings
                 *
                 * @access public
                 * @return void
                 */
                function init()
                {
                    // Load the settings API
                    $this->init_form_fields();
                    $this->init_settings();

                    // Save settings
                    add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
                }

                /**
                 * Define settings field for this shipping
                 * @return void
                 */
                function init_form_fields()
                {
                    $this->form_fields = array(
                        'api_key' => array(
                            'title' => __('API Key', 'toshi'),
                            'type' => 'text',
                            'description' => __('Contact TOSHI to receive your key', 'toshi'),
                            'default' => __('', 'toshi'),
                        ),

                        'api_url' => array(
                            'title' => __('API URL', 'toshi'),
                            'type' => 'text',
                            'description' => __('Contact TOSHI for more information', 'toshi'),
                            'default' => __('', 'toshi'),
                        ),

                        'choose_slot_button_class' => array(
                            'title' => __('Choose Timeslot button class', 'toshi'),
                            'type' => 'text',
                            'description' => __('Override the class for the "Choose Timeslot" button', 'toshi'),
                            'default' => __('', 'toshi'),
                        ),

                        'sandbox' => array(
                            'title' => __('Use sandbox', 'toshi'),
                            'type' => 'checkbox',
                            'description' => __('Load the development modal?', 'toshi'),
                            'default' => false,
                        ),
                    );

                    $this->instance_form_fields = array(
                        'enabled' => array(
                            'title' => __('Enable', 'toshi'),
                            'type' => 'checkbox',
                            // 'description' => __('Is TOSHI enabled?', 'toshi'),
                            'default' => 'yes',
                        ),

                        'title' => array(
                            'title' => __('Title', 'toshi'),
                            'type' => 'text',
                            // 'description' => __('Title to be displayed at checkout', 'toshi'),
                            'default' => __('Toshi Concierge', 'toshi'),
                        ),

                        'minimum_basket_value' => array(
                            'title' => __('Minimum Basket Total', 'toshi'),
                            'type' => 'text',
                            // 'description' => __('The minimum basket value for TOSHI to appear at checkout', 'toshi'),
                            'default' => __('0', 'toshi'),
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
                        'label' => $this->get_instance_option('title') ?? 'TOSHI',
                        'cost' => '0.00',
                        'calc_tax' => 'per_item',
                    );

                    $this->add_rate($rate);
                }

                public function is_available($package)
                {
                    $minimum_basket_value = $this->get_instance_option('minimum_basket_value');

                    // Defer to default value if the supplied setting is not numeric
                    if (! is_numeric($minimum_basket_value)) {
                        return parent::is_available($package);
                    }

                    $minimum_basket_value = floatval($minimum_basket_value);

                    if ($minimum_basket_value <= 0) {
                        return parent::is_available($package);
                    }


                    if (floatval($package['cart_subtotal']) < $minimum_basket_value) {
                        return false;
                    }

                    return parent::is_available($package);
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

    add_filter('woocommerce_cart_shipping_method_full_label', function ($label, $method) {
        if ($method->method_id != 'toshi') {
            return $label;
        }

        if ($method->cost <= 0) {
            $label .= ': ' . wc_price(0);
        }
        return $label;
    }, 10, 2);
}
