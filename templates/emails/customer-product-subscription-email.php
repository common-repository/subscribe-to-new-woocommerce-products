<?php
/**
 * Customer Product Stock Waiting List
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/plain/customer-product-stock-waiting-list.php.
 *
 * @author   Lewis Self
 * @version  1.0.0
 */

if(!defined('ABSPATH'))
{
	exit;
}

/**
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email);

?>
<p>Hello,</p>
<p>Just to let you know, we have a new product on our website! <?php echo get_the_title($post_id); ?> is available now on <?php echo get_bloginfo('name'); ?>. You can now view this product online from the following link <a href="<?php echo get_permalink($post_id); ?>"><?php echo get_permalink($post_id); ?></a></p>

<?php

/**
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);
