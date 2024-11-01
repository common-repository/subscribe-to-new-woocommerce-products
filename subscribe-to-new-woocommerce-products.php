<?php

	/*
		Plugin Name:          Subscribe To New WooCommerce Products
		Description:          Lets customers input an email address to be notified when new products are uploaded to the website.
		Version:              1.0.2
		Author:               Lewis Self
		Author URI:           https://www.lewisself.co.uk/
		WC requires at least: 3.0.0
		WC tested up to:      3.6.4
	*/

	if(!defined('ABSPATH')) // http://docs.woothemes.com/document/create-a-plugin/
	{
		exit;
	}

	if(in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) // Check if WooCommerce is active
	{
		if(is_admin()) // Functions to be ran on admin
		{
			function stnwp_activation() // Run on plugin activation
			{
				if(!(get_option('stnwp_subscribers')))
				{
					add_option('stnwp_subscribers', array());
				}
			}
			register_activation_hook(__FILE__, 'stnwp_activation');

			function stnwp_add_setting_link($links) // Creates settings link on the plugin page
			{
				$stnwp_settings_link = array(
					'<a href="' . admin_url('admin.php?page=wc-settings&tab=email&section=wc_product_subscription_email') . '">Email Settings</a>'
				);

				return array_merge($links, $stnwp_settings_link);
			}
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'stnwp_add_setting_link');

			function stnwp_create_admin_widget() // Create admin widget
			{
				if(current_user_can('administrator'))
				{
					wp_add_dashboard_widget(
						'subscribe_to_new_woocommerce_products',
						'New Product Subscribers',
						'stnwp_create_admin_widget_html'
					);
				}
			}
			add_action('wp_dashboard_setup', 'stnwp_create_admin_widget');

			function stnwp_create_admin_widget_html() // Admin widget HTML
			{
				if(current_user_can('administrator'))
				{
					if(isset($_REQUEST['_wpnonce']))
					{
						if(wp_verify_nonce($_REQUEST['_wpnonce'])) // Verify the nonce
						{
							if($_POST['update_submitted'])
							{
								$sanatized_emails = array();

								foreach(explode("\n", $_POST['product_subscription_emails']) as $product_subscription_email) // Loop and sanatize all email addresses in array
								{
									$sanatized_emails[] = sanitize_email($product_subscription_email);
								}
								update_option('stnwp_subscribers', $sanatized_emails);
							}
						}
					}

					$email_subscribers = get_option('stnwp_subscribers');

					$email_list_count        = 0;
					$email_subscribers_total = count($email_subscribers);
					$textbox_value           = '';

					if($email_subscribers)
					{
						foreach($email_subscribers as $email_subscriber)
						{
							$email_list_count++;

							$textbox_value .= $email_subscriber . (($email_list_count != $email_subscribers_total) ? "\n" : ''); // Stops blank array values on save
						}
					}

					echo '<form method="post" action="#">';
					wp_nonce_field(); // Create nonce
					echo '  <p>Add each email on a new line</p>';
					echo '  <textarea style="width:100%; resize:none; height:100px;" name="product_subscription_emails">' . esc_textarea($textbox_value) . '</textarea>';
					echo '  <input type="submit" name="save" class="button button-primary" value="Update List" style="margin:10px 0 0 0;">';
					echo '  <input type="hidden" name="update_submitted" value="true">';
					echo '</form>';
				}
			}

			function stnwp_new_product_created($new_status, $old_status, $post)
			{
				global $post;

				if('publish' !== $new_status || 'publish' === $old_status)
				{
					return;
				}

				if($post->post_type == 'product')
				{
					$subscriber_emails = get_option('stnwp_subscribers');

					$mailer 	 = WC()->mailer();
					$mails  	 = $mailer->get_emails();
					$mail_sent = false;
					$post_id	 = $post->ID;

					if($subscriber_emails)
					{
						foreach($subscriber_emails as $subscriber_email)
						{
							if(!empty($mails))
							{
								foreach($mails as $mail)
								{
									if($mail->id == 'product_subscription_email')
									{
										$mail->trigger($post_id, $subscriber_email); // Trigger the email (Product id, Subscriber Email address)
									}
								}
							}
						}
					}
				}
			}
			add_action('transition_post_status', 'stnwp_new_product_created', 10, 3);

			function stnwp_uninstall() // Run on plugin uninstall
			{
				delete_option('stnwp_subscribers');
			}
			register_uninstall_hook(__FILE__, 'stnwp_uninstall');
		}

		function stnwp_create_sidebar_widget() // Creates sidebar widget
		{
			require('includes/class-customer-product-subscription-widget.php'); // Imports widget class

			register_widget('subscribe_to_new_woocommerce_products'); // Register new widget
		}
		add_action('widgets_init', 'stnwp_create_sidebar_widget');

		function stnwp_product_subscription_email($email_classes) // Add new email class
		{
			require('includes/class-wc-customer-product-subscription-email.php');

			$email_classes['WC_Product_Subscription_Email'] = new WC_Product_Subscription_Email();

			return $email_classes;
		}
		add_filter('woocommerce_email_classes', 'stnwp_product_subscription_email');

		function stnwp_get_plugin_path()
		{
			return plugin_dir_path(__FILE__);
		}
		add_action('init', 'stnwp_get_plugin_path');
	}
	else
	{
		function stnwp_woocommerce_inactive() // Error if WooCommerce not active
		{
			echo '<div class="error"><p>The WooCommerce plugin needs to be enabled to use the Product Subscription For WooCommerce plugin.</p></div>';
		}
		add_action('admin_notices', 'stnwp_woocommerce_inactive');
	}

?>
