<?php

	if(!defined('ABSPATH')) // http://docs.woothemes.com/document/create-a-plugin/
	{
		exit;
	}

	class Subscribe_To_New_WooCommerce_Products extends WP_Widget
	{
		function __construct() // Constructs the widget
		{
			parent::__construct(
				'subscribe_to_new_woocommerce_products',
				'Subscribe To New WooCommerce Products',
				array('description' => 'Lets the user input an email address to be notified when new products are added.')
			);
		}

		function update($new_instance, $old_instance)
		{
			$instance = $old_instance;

			$instance['title'] 			= strip_tags($new_instance['title']);
			$instance['description'] = strip_tags($new_instance['description']);

			return $instance;
		}

		function form($instance) // Create HTML for admin settings
		{
			if($instance)
			{
				$title 			= esc_attr($instance['title']);
				$description = esc_attr($instance['description']);
			}
			else
			{
				$title 			= '';
				$description = '';
			}

			echo '<p><label for="' . $this->get_field_id('title') . '">' . _e('Title', 'subscribe_to_new_woocommerce_products') . '</label>';
			echo '<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';
			echo '<p><label for="' . $this->get_field_id('description') . '">' . _e('Description', 'subscribe_to_new_woocommerce_products') . '</label>';
			echo '<textarea style="display:block; width:100%; resize:none;" name="' . $this->get_field_name('description') . '">' . $description . '</textarea></p>';
		}

		function widget($args, $instance) // Output sidebar widget HTML
		{
			extract($args);

			$title		  			 = apply_filters('widget_title', $instance['title']);
			$email_subscribers = get_option('stnwp_subscribers');
			$description 			 = $instance['description'];
			$form_sent  			 = false;

			echo $before_widget;

			if($title)
			{
				echo $before_title . $title . $after_title;
			}

			if(isset($_POST['form_sent']) && isset($_POST['subscripton_email']))
			{
				$form_sent = true;
        $new_email = sanitize_email($_POST['subscripton_email']); // Sanatize email
			}

			if(!$form_sent)
			{
				if($description)
				{
					echo '<p>' . $description . '</p>';
				}
        
				echo '<style>.subscription-email{width:100%;}</style>';

				echo '<form action="#" method="post">';
        wp_nonce_field(); // Create nonce
				echo '	<label>Email Address</label>';
				echo '	<input type="hidden" name="form_sent" />';
				echo '	<p><input type="email" class="subscription-email" name="subscripton_email" /></p>';
				echo '	<p style="' . ((is_rtl()) ? 'right' : 'left') . ': -999em; position:absolute;"><input type="email" name="email_2" tabindex="-1" autocomplete="off" /></p>'; // Spam trap
				echo '	<p><input type="submit" name="subscription" value="Sign Up" /></p>';
				echo '</form>';
        
				echo $after_widget;
			}
			else
			{
				$unique_email = true;

				if($email_subscribers)
				{
					foreach($email_subscribers as $email_subscriber) // Loop through all addresses to check if the address isn't subscribed
					{
						if($email_subscriber == $new_email)
						{
							$unique_email = false;

							break;
						}
					}
				}

        if(wp_verify_nonce($_REQUEST['_wpnonce'])) // Verify the nonce
        {
          if(isset($_POST['email_2']))
          {
            if(!($_POST['email_2'])) // Check spam hasnt been filled in
            {
              if($unique_email)
              {
                if(is_email($new_email)) // Check email is inputted
                {
                  $email_subscribers[] = $new_email;

                  update_option('stnwp_subscribers', $email_subscribers);

                  echo '<p class="success">You have signed up! We will notify you when new products are added.</p>';
                }
                else
                {
                  echo '<p class="error">Please input a valid email address.</p>';
                }
              }
              else
              {
                echo '<p class="error">This email is already signed up to the list.</p>';
              }
            }
            else
            {
              echo '<p class="error">Spam detected. If this is a mistake, please turn off your browser form autofiller.</p>';
            }
          }
        }
			}
		}
	}

?>
