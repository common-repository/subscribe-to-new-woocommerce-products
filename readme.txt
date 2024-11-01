=== Subscribe To WooCommerce New Products ===

Contributors: Lewis Self
Donate Link: https://www.lewisself.co.uk/
Tags: woocommerce, new, products, notification, subscription, subscribe, newsletter, email
Requires at least: 4.0
Tested up to: 5.0.0
Stable tag: Trunk
License: GPLv2
Version: 1.0.2

== Description ==

This plugin creates a sidebar widget to let customers sign up to be notified when new products are added to the website. All the email settings are customisable, you can change the subject, heading and the type of email from plain to html. It also creates a admin dashboard widget to let you add and delete customers to and from the list. For this plugin to function correctly, WooCommerce must be installed.

== Installation ==

1. Extract "subscribe-to-new-woocommerce-products.zip"
2. Upload the "subscribe-to-new-woocommerce-products" Folder to the Plugin Directory
3. Activate the plugin through the WordPress admin "Plugins" menu
4. Drag the sidebar widget to the product archive sidebar

== Frequently Asked Questions ==

= How do I add the sidebar widget? =

Navigate to "Appearance->Widgets". You should see a list of all of the WordPress widgets. Drag the "Subscribe To New WooCommerce Products" onto your sidebar. Make sure to fill in the title and description.

= How do I manually add and delete customers to and from the email list? =

You can add and remove people to and from the email list by using the admin widget. To get to this widget, click on the dashboard button. You can use the widget called "Emails subscribed to new products" to add and remove emails. If you cannot see this widget, click on the "Screen Options" in the top right. Check the button for the "New Product Subscribers" and it should show in the widget section.

= Can I export the email list? =

Not currently but we are looking to implement this feature in the future.

= Will I need a spam filter to stop spam emails signing up to my list? =

We have added a feature that helps detect spam and prevent it from signing up to your email list.

= Can I create my own email template for this plugin? =

Yes. You can create your custom email template the same way you would create a WooCommerce default template. Go to woocommerce->settings. Click on the "Emails" tab. You should see a table which has all of the WooCommerce emails listed. Click on the "Product Subscription Email", click "copy file to theme" and you can modify it in your theme folder.

== Screenshots ==

1. The sidebar widget settings.
2. The admin widget, lets you add and delete subscribers to the email list. (Admin only)
3. The email settings, lets you change the subject, heading and email type. (Admin only)
4. Preview of the email in the storefront theme.

== Upgrade Notice ==

== Changelog ==

= 1.0.2 (01/07/2019) =
* Stopped emails being when disabled in email settings
* Fixed error when WooCommerce disabled

= 1.0.1 (03/05/2017) =
* Fixed admin widget dashboard bug
* Added spam on subscription form

= 1.0.0 (30/04/2017) =
* Initial release
