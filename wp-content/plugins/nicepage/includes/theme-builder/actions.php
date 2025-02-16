<?php
/**
 * Action on wp_footer
 * Print backlink html
 *
 * @param int|string $template_id
 */
function wpFooterActions($template_id)
{
    if (Nicepage::$override_with_plugin) {
        $data_provider = np_data_provider($template_id);
        $backlink = $data_provider->getPageBacklink();
        echo $backlink;
    }
}

add_action(
    'wp_enqueue_scripts', function () {
        if (get_option('np_theme_appearance') === 'plugin-option' && function_exists('is_shop') && (is_shop() || is_product_category() || is_product())) {
            if (!empty($GLOBALS['pluginTemplatesExists'])) {
                wp_dequeue_style('woocommerce-general'); //disable woocommerce.css
                wp_dequeue_style('woocommerce-layout'); //disable woocommerce-layout.css
            }
        }
    },
    20
);

add_action('wp_enqueue_scripts', 'add_plugin_templates_woocommerce_script', 1003);
/**
 * Add our woocommerce scripts and styles for plugin templates
 */
function add_plugin_templates_woocommerce_script() {
    if (get_option('np_theme_appearance') === 'plugin-option') {
        wp_enqueue_style('theme-default-styles', APP_PLUGIN_URL . 'includes/theme-builder/css/style.css');
        if (function_exists('is_shop') && (is_shop() || is_product_category() || is_product())) {
            wp_register_script('theme-woo-scripts', APP_PLUGIN_URL . 'includes/theme-builder/js/woocommerce.js', array());
            wp_enqueue_script('theme-woo-scripts');
            wp_enqueue_style('theme-woo-styles', APP_PLUGIN_URL . 'includes/theme-builder/css/woocommerce.css');
        }
    }
}

add_action('wp', 'remove_theme_npProductsJsonUrl');
/**
 * Remove theme productsJsonUrl for plugin templates
 */
function remove_theme_npProductsJsonUrl() {
    if (get_option('np_theme_appearance') === 'plugin-option' && (get_query_var('product-id', null) !== null || get_query_var('products-list', null) !== null)) {
        remove_action('wp_head', 'add_npProductsJsonUrl');
    }
}

add_filter('woocommerce_loop_add_to_cart_link', 'np_add_to_cart_button', 10, 2);
/**
 * Add np css class to add to cart button when plugin woo templates
 *
 * @param string $button_html
 * @param object $product
 *
 * @return string $button_html
 */
function np_add_to_cart_button($button_html, $product) {
    $additional_classes = isset($GLOBALS['addToCartClasses']) ? $GLOBALS['addToCartClasses'] : '';
    $button_html = str_replace('class="', 'class="' . $additional_classes . ' ', $button_html);
    return $button_html;
}


/**
 * Add default class for product comment button
 */
add_filter(
    'comment_form_defaults', function ($defaults) {
        if (isset($defaults['submit_button']) && Nicepage::$override_with_plugin) {
            $defaults['submit_button'] = str_replace('class="', 'class="u-btn ', $defaults['submit_button']);
        }
        return $defaults;
    }
);