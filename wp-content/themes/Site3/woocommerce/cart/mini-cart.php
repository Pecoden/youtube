<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 999.3.0
 */

defined( 'ABSPATH' ) || exit; ?>
<script type="text/javascript">
    if (window.sessionStorage) {
        window.sessionStorage.setItem('wc_cart_created', '');
    }
</script>
<a class="u-shopping-cart " data-payment-service="{&quot;id&quot;:&quot;8d409229275962e42396c83de742b67b&quot;,&quot;formServices&quot;:[],&quot;paymentMethods&quot;:[],&quot;userToken&quot;:&quot;1177ff2d-42f7-4e6c-830a-52ae4ffc0695&quot;}" href="<?php echo wc_get_cart_url(); ?>">
      <span class="u-icon u-shopping-cart-icon"><svg class="u-svg-link" preserveAspectRatio="xMidYMin slice" viewBox="0 0 16 16" style=""><use xlink:href="#svg-cb52"></use></svg><svg class="u-svg-content" viewBox="0 0 16 16" x="0px" y="0px" id="svg-cb52"><path d="M14.5,3l-2.1,5H6.1L5.9,7.6L4,3H14.5 M0,0v1h2.1L5,8l-2,4h11v-1H4.6l1-2H13l3-7H3.6L2.8,0H0z M12.5,13
	c-0.8,0-1.5,0.7-1.5,1.5s0.7,1.5,1.5,1.5s1.5-0.7,1.5-1.5S13.3,13,12.5,13L12.5,13z M4.5,13C3.7,13,3,13.7,3,14.5S3.7,16,4.5,16
	S6,15.3,6,14.5S5.3,13,4.5,13L4.5,13z"></path></svg><span class="u-icon-circle u-palette-1-base u-shopping-cart-count" style="font-size: 0.75rem;"><?php $count = WC()->cart->get_cart_contents_count(); $count = isset($count) ? $count : 0; echo $count; ?></span></span>
    </a>
