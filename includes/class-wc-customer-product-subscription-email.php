<?php

	if(!defined('ABSPATH')) // http://docs.woothemes.com/document/create-a-plugin/
	{
		exit;
	}

	class WC_Product_Subscription_Email extends WC_Email
	{
		public $product;

		public function __construct()
		{
			$this->id             = 'product_subscription_email';
			$this->customer_email = true;
			$this->title          = 'Product Subscription Email';
			$this->description    = 'New product emails are sent to the email list when a new product is uploaded to the website.';
			$this->template_base  = stnwp_get_plugin_path() . 'templates/';
			$this->heading        = '{product_name} Available';
			$this->subject        = '{product_name} Available on {site_title}';
			$this->template_html  = 'emails/customer-product-subscription-email.php';
			$this->template_plain = 'emails/plain/customer-product-subscription-email.php';

			parent::__construct();
		}

		public function trigger($post_id, $email_address)
		{
			$this->post_id   = $post_id;
			$this->recipient = $email_address;

			$this->find['product-name']    = '{product_name}';
			$this->replace['product-name'] = get_the_title($post_id);

			if(!$this->recipient || ($this->is_enabled() == false))
			{
				return;
			}

			$this->send($this->recipient, $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments());
		}

		public function get_content_html() // HTML Email Content Template
		{
			ob_start();

			wc_get_template(
				$this->template_html,
				array(
					'email_heading' => $this->get_heading(),
					'post_id'       => $this->post_id,
					'plain_text'    => false,
					'email'				  => $this
				),
				'',
				stnwp_get_plugin_path() . 'templates/'
			);

			return ob_get_clean();
		}

		public function get_content_plain() // Plain Email Content Template
		{
			ob_start();

			woocommerce_get_template(
				$this->template_plain,
				array(
					'email_heading' => $this->get_heading(),
					'post_id'       => $this->post_id,
					'plain_text'    => true,
					'email'				  => $this
				),
				'',
				stnwp_get_plugin_path() . 'templates/'
			);

			return ob_get_clean();
		}

		public function init_form_fields() // Email Template Settings
		{
			$this->form_fields = array(
				'enabled' 		=> array(
					'title'       => 'Enable/Disable',
					'type'        => 'checkbox',
					'label'       => 'Enable this email notification',
					'default'     => 'yes'
				),
				'subject'			=> array(
					'title'       => 'Subject',
					'type'        => 'text',
					'description' => sprintf('This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject),
					'placeholder' => '',
					'default'     => ''
				),
				'heading' 		=> array(
					'title'       => 'Email Heading',
					'type'        => 'text',
					'description' => sprintf('This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.', $this->heading),
					'placeholder' => '',
					'default'     => ''
				),
				'email_type' 	=> array(
					'title'       => 'Email type',
					'type'        => 'select',
					'description' => 'Choose which format of email to send.',
					'default'     => 'html',
					'class'       => 'email_type',
					'options'     => array(
						'plain'       => 'Plain text',
						'html'        => 'HTML',
						'multipart'   => 'Multipart'
					)
				)
			);
		}
	}
?>
