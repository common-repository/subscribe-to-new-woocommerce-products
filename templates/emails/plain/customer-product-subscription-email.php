<?php
/**
 * Customer Product Stock Waiting List
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-product-stock-waiting-list.php.
 *
 * @author   Lewis Self
 * @version  1.0.0
 */

	if(!defined('ABSPATH'))
	{
		exit;
	}

	echo "= " . $email_heading . " =\n\n";

	echo "Hello, \n\n";

	echo "Just to let you know we have a new product on our website. " . get_the_title($post_id) . " is now on " . get_bloginfo('name') . ". You can now order online from the following link. " . get_permalink($post_id) . " \n\n";

	echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=\n\n";

	echo apply_filters('woocommerce_email_footer_text', get_option('woocommerce_email_footer_text'));
?>
