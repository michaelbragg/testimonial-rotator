=== Plugin Name ===
Contributors: halgatewood
Donate link: https://halgatewood.com/donate/
Tags: testimonials, sidebar, shortcode, testimonial, praise, homage, testimony, witness, appreciation, green light, rotator, rotators, for developers
Requires at least: 3.5
Tested up to: 4.4
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add Testimonials to your WordPress Blog or Company Website.

== Description ==
Finally a really simple way to manage testimonials on your site. This plugin creates a testimonial and a testimonial rotator custom post type, complete with WordPress admin fields for adding testimonials and assigning them to rotators for display. It includes a Widget and Shortcode to display the testimonials.

= Documentation =
Help documents and code snippets can be viewed at http://halgatewood.com/docs/plugins/testimonial-rotator/

= Version 2 Available Now = 
Version 2 includes a big release full of awesome features like:

* Change all rotator settings in the admin
* Add testimonials to multiple rotators
* Prev/Next Buttons
* Vertical Align Testimonials based on Height
* Star Ratings
* Author information field
* Testimonial single template
* Ability to make custom templates (custom themes in the works)
* hReview Support
* Pagination in List Format
* Ability to show the Add Rotator section based on User Role
* New Settings section
* New hooks


== Installation ==

1. Add plugin to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add a testimonial rotator
1. Create testimonials and specify which rotator you want it to be a part of
1. Add the rotators to your pages using the shortcode or developers can add the placeholders in their themes.


== Frequently Asked Questions ==

= How do I change the speed and transition of the rotator? =

When you are adding or editing the rotator, you have the ability to specify how many seconds each testimonial should appear for. You can also choose from a handful of transitions there (like fades and wipes).



== Screenshots ==

1. New Sidebar added just for Testimonials
2. Adding a Testimonial Rotator
3. Add a new Testimonial. Uses built-in WordPress functionality like excerpt, featured images and menu order
4. A Testimonial Rotator inserted into a block of text with a shortcode
5. Testimonials have their own page and use the single template they can be customized by making a single-testimonial.php file in your theme.
6. Testimonial widget on the new TwentyFourteen theme
7. New Widget Options (version 1.3+)
8. New settings area (version 2.0)



== Changelog ==

= 2.1 - Updated Oct 9, 2015 =
* Added Shortcode for Single Testimonial
* Added Testimonial Count to Rotator List View
* Change the Element for the Title in Settings (default H2)
* Code added for upcoming custom templates
* Flip transitions added
* Fix hreview itemreviewed name
* Code cleanup and better sanitation of variables
* Updated text domain from testimonial_rotator to testimonial-rotator


= 2.0.6 - Updated July 13, 2015 =
* Changed WP_Widget() to __construct, for maximum PHP5 support

= 2.0.5 - Updated July 20, 2014 =
* New filter to change the stars to any FontAwesome Icon
* Improved stability when upgrading from 1.4+
* When 'Previous/Next' is checked in the rotator it will automatically turn on paged for the list view

= 2.0.4 - Updated July 7, 2014 =
* Ability to center the stars
* Hopefully fixed up issues with the_content on the single page
* Added new filter for pause on hover
* Added new filter for loading scripts in the footer
* Added new filter for settings on Widgets
* Added Rotator IDs to most filters so they can used on a rotator basis

= 2.0.3 - Updated May 30, 2014 =
* Added thumbnail setting for Rotator
* Wrapped Init functions with is_admin for the Admin only hooks

= 2.0.2 - Updated May 20, 2014 =
* Fixed Widget Title
* Fixed rotator timeout and transition speed

= 2.0.1 - May 15, 2014 =
* Added wrapper div around quote part of testimonial

= 2.0 -  May 15, 2014 =
* Change all rotator settings in the admin
* Add testimonials to multiple rotators
* Prev / Next Buttons
* Vertical Align Testimonials based on Height
* Font Awesome
* Star Ratings
* Hide Featured Image
* Author Cite Field
* Testimonial single template
* Ability to make custom templates (Theme Pack coming soon)
* hReview Support
* Pagination in List Format
* Ability to show the Add Rotator section based on User Role
* New Settings section
* New hooks added
* Code cleanup and more commenting

= 1.4 =
* Use shortcode to display testimonials from all rotators by not passing in an 'id' attribute
* Completed preparation for translation, wrapped all text in __()
* Two new filters for the 'supports' section of the register_post_type: testimonial_rotator_supports and testimonial_rotator_testimonial_supports
* Two new filters for auto-height 'calc': testimonial_rotator_calc and testimonial_rotator_widget_fx

= 1.3.7 =
* Updated icon for WordPress 3.8
* Fixed translation and added languages folder, moved .pot to this folder
* Moved styles and scripts from action wp_head to wp_enqueue_scripts
* Fixed images and order not showing in admin list view
* Prepped for an upcoming PRO version!

= 1.3.6 =
* Fix bug not rotating widget

= 1.3.5 =
* Changed cycle2 to cycletwo as it was conflicting with other plugins

= 1.3.4 = 
* Fixed small bug where some themes were adding extra spaces and breaking the rotator

= 1.3.3 =
* Switched from jQuery Cycle1 to Cycle 2
* Widget now uses Rotator FX and Timeout settings
* Added .testimonial_rotator_widget_blockquote class to widget blockquote to help override some CSS problems with themes.
* Rotator Height is now fixed at the highest testimonial instead of auto adjusting the height

= 1.3.2 =
reset query bug

= 1.3 =
* Randomize testimonials without code
* Hide the title
* Display excerpt or full testimonial in width
* Display specific rotator in widget
* More shortcode examples
* The widget has been updated with all the features as options, no more coding!

= 1.2 =
* main testimonial now uses the_content filter to make styling better.
* include rotator using the rotator slug, for example: [testimonial_rotator id=homepage]
* new attributes to the shortcode: 
** hide_title: hides the h2 heading
** format: settings format=list will remove rotator and display all testimonials
** limit: set the number of testimonials to display, new default is -1 or all testimonials

= 1.1.5 =
* small bug in widget javascript

= 1.1.4 =
* reworking loading of scripts for rotator, should be sorted now.

= 1.1.3 =
* jQuery ready function always

= 1.1.2 =
* Testimonial widget using jQuery ready function instead of window.onload

= 1.1.1 =
* Can't remember, forgot to put this one in (not cool of me, I know)

= 1.1 =
* Small fix to make the testimonial widget fit it's container

= 1.0 =
* Initial load of the plugin.

