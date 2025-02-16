<?php
require_once 'actions.php';
require_once 'breadcrumbs.php';

add_action('template_redirect', 'templates_replacer');

/**
 * Replace header/footer templates
 *
 * @param $location_manager
 */
function templates_replacer($location_manager)
{
    $headerFooterFromPlugin = get_option('np_theme_appearance') === 'plugin-option';
    Nicepage::$isWooShopProductTemplate = function_exists('wc_get_product') && (is_shop() || is_product_category() || is_product());
    Nicepage::$isBlogPostTemplate = is_singular('post') || is_home() || (is_archive() && !Nicepage::$isWooShopProductTemplate);
    if ($headerFooterFromPlugin && Nicepage::isNpTheme()) {
        remove_action('wp_body_open', 'wp_admin_bar_render', 0); // wp-version >= 5.2
        add_action('get_header', 'get_np_header');
        add_action('get_footer', 'get_np_footer');
    }
}

/**
 * Get custom header
 *
 * @param $name
 */
function get_np_header($name) {
    include __DIR__ . '/header.php';

    $templates = [];
    $name = (string)$name;
    if ('' !== $name) {
        $templates[] = "header-{$name}.php";
    }

    $templates[] = 'header.php';

    // Avoid running wp_head hooks again
    remove_all_actions('wp_head');
    ob_start();
    // It cause a `require_once` so, in the get_header it self it will not be required again.
    locate_template($templates, true);
    ob_get_clean();

    $post_id = get_the_ID();
    $data_provider = np_data_provider($post_id);
    $headerNp = $data_provider->getNpHeader();
    $headerItem = '';
    if ($headerNp && !$data_provider->getHideHeader()) {
        $headerItem = json_decode($headerNp, true);
        $publishHeader = $data_provider->getTranslation($headerItem, 'header');
        $publishHeader = Nicepage::processFormCustomPhp($publishHeader, 'header');
        $publishHeader = Nicepage::processContent($publishHeader, array('templateName' => 'header'));
    }
    if ($headerItem) {
        if (false === strpos($headerItem['styles'], '<style>')) {
            $headerItem['styles'] = '<style>' . $headerItem['styles'] . '</style>';
        }
        echo $headerItem['styles'];
        echo $publishHeader;
    }
}

/**
 * Get custom footer
 *
 * @param $name
 */
function get_np_footer($name) {
    include __DIR__ . '/footer.php';

    $templates = [];
    $name = (string)$name;
    if ('' !== $name) {
        $templates[] = "footer-{$name}.php";
    }

    $templates[] = 'footer.php';

    ob_start();
    // It cause a `require_once` so, in the get_footer it self it will not be required again.
    locate_template($templates, true);
    ob_get_clean();

    $post_id = get_the_ID();
    $data_provider = np_data_provider($post_id);
    $footerNp = $data_provider->getNpFooter();
    $footerItem = '';
    if ($footerNp && !$data_provider->getHideFooter()) {
        $footerItem = json_decode($footerNp, true);
        $publishFooter = $data_provider->getTranslation($footerItem, 'footer');
        $publishFooter = Nicepage::processFormCustomPhp($publishFooter, 'footer');
        $publishFooter = Nicepage::processContent($publishFooter, array('templateName' => 'footer'));
    }
    if ($footerItem) {
        if (false === strpos($footerItem['styles'], '<style>')) {
            $footerItem['styles'] = '<style>' . $footerItem['styles'] . '</style>';
        }
        echo $footerItem['styles'];
        echo $publishFooter;
    }
}

add_filter(
    'template_include', function ($template) {
        if (get_option('np_theme_appearance') !== 'plugin-option') {
            return $template;
        }
        $isShop = function_exists('wc_get_product') && (is_shop() || is_product_category());
        $isProduct = function_exists('wc_get_product') && is_product();
        if (get_query_var('products-list', null) !== null) {
            $render = render_plugin_template('products');
            return $render ? null : $template;
        }
        if (get_query_var('product-id', null) !== null) {
            $render = render_plugin_template('product');
            return $render ? null : $template;
        }
        if (is_singular('post')) {
            $render = render_plugin_template('post');
            return $render ? null : $template;
        }
        if (is_home() || (is_archive() && !$isShop)) {
            $render = render_plugin_template('blog');
            return $render ? null : $template;
        }
        if ($isShop) {
            $render = render_plugin_template('products');
            return $render ? exit : $template;
        }
        if ($isProduct) {
            $render = render_plugin_template('product');
            return $render ? exit : $template;
        }
        return $template;
    },
    51
);

/**
 * Render base theme template from plugin db template
 *
 * @param string $type
 */
function render_plugin_template($type) {
    $result = null;
    $plugin_templates = get_posts(
        [
            'post_type'      => 'template',
            'name'           => $type,
            'posts_per_page' => 1,
        ]
    );

    if (!empty($plugin_templates)) {
        $GLOBALS['pluginTemplatesExists'] = true;
        $plugin_template = $plugin_templates[0];
        if ($plugin_template && $plugin_template->ID) {

            if ($type === 'blog') {
                update_option('blog_template_id', $plugin_template->ID);
            }

            if ($type === 'post' || $type === 'blog' || $type === 'products' || $type === 'product') {
                global $post;
                $original_post = $post;
                $post = get_post($plugin_template->ID);
            }

            if ($type === 'products') {
                update_option('products_template_id', $plugin_template->ID);
            }

            if ($type === 'product') {
                update_option('product_template_id', $plugin_template->ID);
            }

            $sections_html = Nicepage::html($plugin_template->ID);
            if ($sections_html) {
                if (function_exists('do_blocks') && function_exists('has_blocks') && has_blocks($sections_html)) {
                    $sections_html = do_blocks($sections_html);
                }
                if (function_exists('w123cf_widget_text_filter')) {
                    $sections_html = w123cf_widget_text_filter($sections_html);
                }
            }
            ob_start();
            get_header();
            if ($type === 'product' || $type === 'products') {
                $sections_html = processWoocommercePlaceholders($sections_html);
            }
            echo $sections_html;

            if ($type === 'post') {
                $post = $original_post;
                if (comments_open() || get_comments_number()) {
                    comments_template();
                }
                do_action('the_rating');
                do_action('after_post_content');
                $post = get_post($plugin_template->ID);
            }

            get_footer();
            wpFooterActions($plugin_template->ID);

            // for dialogs start
            $data_provider = np_data_provider($plugin_template->ID);
            $headerNp = $data_provider->getNpHeader();
            $footerNp = $data_provider->getNpFooter();
            $headerItem = '';
            $footerItem = '';
            if ($headerNp && !$data_provider->getHideHeader()) {
                $headerItem = json_decode($headerNp, true);
            }
            if ($footerNp && !$data_provider->getHideFooter()) {
                $footerItem = json_decode($footerNp, true);
            }
            $htmlDocument = ob_get_clean();
            $htmlDocument = $data_provider->addPublishDialogToBody($htmlDocument, $headerItem, $footerItem);
            echo $htmlDocument;
            // for dialogs end

            if ($type === 'post' || $type === 'blog' || $type === 'products' || $type === 'product') {
                $post = $original_post;
            }
        }
        $result = true;
    }
    return $result;
}

/**
 * Add plugin templates in admin
 */
function register_template_post_type() {
    register_post_type(
        'template', array(
        'label'               => 'Plugin Templates',
        'public'              => false, // frontend
        'show_ui'             => true,  // edit mode
        'show_in_menu'        => true,  // menu
        'supports'            => array('title', 'editor'), // fields
        '_edit_link'          => 'post.php?post=%d', // structure
        'capability_type'     => 'post',
        'map_meta_cap'        => true, // user rules
        'menu_icon'           => 'dashicons-text',
        )
    );
}
add_action('init', 'register_template_post_type');

/**
 * Hide menu with templates in admin if templates is empty
 */
function hide_empty_template_menu() {
    if (get_post_type_object('template') && current_user_can('edit_posts')) {
        $query = new WP_Query(
            array(
            'post_type'      => 'template',
            'posts_per_page' => 1,
            'post_status'    => 'any',
            )
        );
        if (!$query->have_posts()) {
            remove_menu_page('edit.php?post_type=template');
        }
    }
}
add_action('admin_menu', 'hide_empty_template_menu', 20);

/**
 * Disable remove for templates in admin
 *
 * @param $actions
 * @param $post
 *
 * @return mixed
 */
function modify_template_post_row_actions($actions, $post) {
    if ($post->post_type === 'template') {
        unset($actions['inline hide-if-no-js']);
        unset($actions['trash']);
    }
    return $actions;
}
add_filter('post_row_actions', 'modify_template_post_row_actions', 10, 2);

/**
 * Disable right panel with remove and update buttons in the edit mode for template
 */
function remove_template_edit_sidebar() {
    global $post;
    if ($post && $post->post_type === 'template') {
        remove_meta_box('submitdiv', 'template', 'side');
        remove_meta_box('postimagediv', 'template', 'side');
        remove_meta_box('tagsdiv-post_tag', 'template', 'side');
        remove_meta_box('categorydiv', 'template', 'side');
        remove_meta_box('formatdiv', 'template', 'side');
    }
}
add_action('add_meta_boxes', 'remove_template_edit_sidebar', 10, 2);

/**
 * Disable add new template in admin menu
 */
function remove_template_add_new_button() {
    global $submenu;
    if (isset($submenu['edit.php?post_type=template'])) {
        foreach ($submenu['edit.php?post_type=template'] as $key => $item) {
            if (in_array('post-new.php?post_type=template', $item)) {
                unset($submenu['edit.php?post_type=template'][$key]);
            }
        }
    }
}
add_action('admin_menu', 'remove_template_add_new_button', 999);

/**
 * Disable add new template in admin bar
 *
 * @param WP_Admin_Bar $wp_admin_bar
 */
function remove_template_add_new_from_admin_bar($wp_admin_bar) {
    $wp_admin_bar->remove_node('new-template');
}
add_action('admin_bar_menu', 'remove_template_add_new_from_admin_bar', 999);

/**
 * Hide add new template in edit mode for template
 */
function hide_template_add_new_button_css() {
    global $post_type;
    if ($post_type === 'template') {
        echo '<style>
            .wrap > .page-title-action { display: none !important; }
        </style>';
    }
}
add_action('admin_head-edit.php', 'hide_template_add_new_button_css');
add_action('admin_head-post.php', 'hide_template_add_new_button_css');

/**
 * Disable multi templates operations in admin
 *
 * @param $bulk_actions
 *
 * @return array|mixed
 */
function remove_bulk_actions_for_template($bulk_actions) {
    if ('template' === get_post_type()) {
        return [];
    }
    return $bulk_actions;
}
add_filter('bulk_actions-edit-template', 'remove_bulk_actions_for_template');

/**
 * Disable change template title in edit mode
 */
function disable_title_input() {
    global $post;
    if ($post->post_type === 'template') {
        echo '<style>
            #titlewrap input#title { 
                pointer-events: none;
            }
        </style>';
    }
}
add_action('admin_head-post.php', 'disable_title_input');
add_action('admin_head-post-new.php', 'disable_title_input');

/**
 * Hooks replacer in plugin woo templates
 *
 * @param string $html Plugin Template HTML with placeholders.
 *
 * @return string HTML with result of hooks.
 */
function processWoocommercePlaceholders($html) {
    // Hooks list.
    $hooks = [
        'woocommerce_before_single_product',         // before product details
        'woocommerce_single_product_summary',        // after last control - product button
        'woocommerce_after_single_product',          // after product details
        'woocommerce_before_add_to_cart_button',     // before add to cart button
        'woocommerce_after_add_to_cart_button',      // after add to cart button
        'woocommerce_after_main_content',            // before woocommerce_sidebar
        'woocommerce_sidebar',                       // before footer
    ];

    if (function_exists('is_woocommerce') && is_woocommerce()) {
        //disable not needed hooks
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    }

    foreach ($hooks as $hook) {
        $replacerTo = function_exists('is_woocommerce') && is_woocommerce() ? getHookOutput($hook) : '';
        $html = str_replace('<!-- {{' . $hook . '}} -->', $replacerTo, $html);
    }
    $html = preg_replace('/<!--\s*\{\{add_to_cart_button_classes:.*?\}\}\s*-->/', '', $html);
    return $html;
}

/**
 * Get result of hook
 *
 * @param string $hook Hook Name.
 *
 * @return string Hook result.
 */
function getHookOutput( $hook ) {
    ob_start();
    do_action($hook);
    return ob_get_clean();
}