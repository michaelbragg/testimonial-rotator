<?php
/*
Plugin Name: Testimonial Rotator
Plugin URI: http://halgatewood.com/testimonial-rotator
Description: A handy plugin for WordPress developers to add testimonials to their site. Enough functionality to be helpful and also stays out of your way.
Author: Hal Gatewood
Author URI: http://www.halgatewood.com
Text Domain: testimonial_rotator
Domain Path: /languages
Version: 2.0.6
*/


// CONSTANTS
if ( ! defined( 'TESTIMONIAL_ROTATOR_URI' ) ) 
{
	define( 'TESTIMONIAL_ROTATOR_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

if ( ! defined( 'TESTIMONIAL_ROTATOR_DIR' ) ) 
{
	define( 'TESTIMONIAL_ROTATOR_DIR', plugin_dir_path( __FILE__ )  );
}


// SETUP
add_action( 'plugins_loaded', 'testimonial_rotator_setup' );
function testimonial_rotator_setup() 
{
	add_action( 'init', 'testimonial_rotator_init' );
	add_action( 'widgets_init', create_function('', 'return register_widget("TestimonialRotatorWidget");') );
	add_action( 'wp_enqueue_scripts', 'testimonial_rotator_enqueue_scripts' );
	
	// ADMIN ONLY HOOKS
	if( is_admin() )
	{
		add_action( 'add_meta_boxes', 'testimonial_rotator_create_metaboxes' );
		add_action( 'save_post', 'testimonial_rotator_save_testimonial_meta', 1, 2 );
		add_action( 'save_post', 'testimonial_rotator_save_rotator_meta', 1, 2 );
		
		add_filter( 'manage_edit-testimonial_columns', 'testimonial_rotator_columns' );
		add_action( 'manage_testimonial_posts_custom_column', 'testimonial_rotator_add_columns' );
		add_filter( 'manage_edit-testimonial_sortable_columns', 'testimonial_rotator_column_sort' );
		
		add_filter( 'manage_edit-testimonial_rotator_columns', 'testimonial_rotator_rotator_columns' );
		add_action( 'manage_testimonial_rotator_posts_custom_column', 'testimonial_rotator_rotator_add_columns' );
		
		add_action( 'admin_head', 'testimonial_rotator_cpt_icon' );
		add_action( 'admin_menu', 'register_testimonial_rotator_submenu_page' );
		
		add_filter( 'enter_title_here', 'register_testimonial_form_title' );
		add_action( 'admin_init', 'testimonial_rotator_settings_init' );
	}
}


// DO THE CSS AND JS
function testimonial_rotator_enqueue_scripts()
{
	$load_scripts_in_footer = apply_filters( 'testimonial_rotator_scripts_in_footer', false );

	wp_enqueue_script( 'cycletwo', plugins_url('/js/jquery.cycletwo.js', __FILE__), array('jquery'), false, $load_scripts_in_footer );
	wp_enqueue_script( 'cycletwo-addons', plugins_url('/js/jquery.cycletwo.addons.js', __FILE__), array('jquery', 'cycletwo'), false, $load_scripts_in_footer );
	wp_enqueue_style( 'testimonial-rotator-style', plugins_url('/testimonial-rotator-style.css', __FILE__) ); 

	$hide_font_awesome = get_option( 'testimonial-rotator-hide-fontawesome' );
	$hide_font_awesome = ($hide_font_awesome == 1) ? true : false;

	if( !$hide_font_awesome )
	{
		wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css' );
	}
}



// REQUIRE FUNCTIONS
if( is_admin() )
{
	require_once( TESTIMONIAL_ROTATOR_DIR . 'admin/admin-functions.php' );
	require_once( TESTIMONIAL_ROTATOR_DIR . 'admin/admin-settings.php' );
	require_once( TESTIMONIAL_ROTATOR_DIR . 'admin/metaboxes-testimonial.php' );
	require_once( TESTIMONIAL_ROTATOR_DIR . 'admin/metaboxes-rotator.php' );
}
else
{
	require_once( TESTIMONIAL_ROTATOR_DIR . 'frontend-functions.php' );
}



// CREATES THE CUSTOM POST TYPE
function testimonial_rotator_init() 
{
	// LOAD TEXT DOMAIN
	load_plugin_textdomain( 'testimonial_rotator', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	// REGISTER SHORTCODE
	add_shortcode( 'testimonial_rotator', 'testimonial_rotator_shortcode' );
	
	// POST THUMBNAILS (pippin)
	if(!current_theme_supports('post-thumbnails')) { add_theme_support('post-thumbnails'); }

	// TESTIMONIAL CUSTOM POST TYPE
  	$labels = array(
				    'name' 					=> __('Testimonials', 'testimonial_rotator'),
				    'singular_name' 		=> __('Testimonial', 'testimonial_rotator'),
				    'add_new' 				=> __('Add New', 'testimonial_rotator'),
				    'add_new_item' 			=> __('Add New Testimonial', 'testimonial_rotator'),
				    'edit_item' 			=> __('Edit Testimonial', 'testimonial_rotator'),
				    'new_item' 				=> __('New Testimonial', 'testimonial_rotator'),
				    'all_items' 			=> __('All Testimonials', 'testimonial_rotator'),
				    'view_item' 			=> __('View Testimonial', 'testimonial_rotator'),
				    'search_items' 			=> __('Search Testimonials', 'testimonial_rotator'),
				    'not_found' 			=>  __('No testimonials found', 'testimonial_rotator'),
				    'not_found_in_trash' 	=> __('No testimonials found in Trash', 'testimonial_rotator'), 
				    'parent_item_colon' 	=> '',
				    'menu_name'				=> __('Testimonials', 'testimonial_rotator')
  					);
	$args = array(
					'labels' 				=> $labels,
					'public' 				=> true,
					'publicly_queryable' 	=> true,
					'show_ui' 				=> true, 
					'show_in_menu' 			=> true, 
					'query_var' 			=> true,
					'rewrite' 				=> array( "slug" => apply_filters( "testimonial_rotator_testimonial_slug"  , "testimonials") ),
					'capability_type' 		=> 'post',
					'has_archive' 			=> true,
					'hierarchical' 			=> false,
					'menu_position' 		=> apply_filters( "testimonial_rotator_menu_position", 26.6),
					'exclude_from_search' 	=> true,
					'supports' 				=> apply_filters( "testimonial_rotator_testimonial_supports", array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes', 'custom-fields' ) )
					);
					
	register_post_type( 'testimonial', apply_filters( 'testimonial_rotator_pt_args', $args ) );

	// TESTIMONIAL ROTATOR CUSTOM POST TYPE
  	$labels = array(
				    'name' 					=> __('Testimonial Rotators', 'testimonial_rotator'),
				    'singular_name' 		=> __('Rotator', 'testimonial_rotator'),
				    'add_new' 				=> __('Add New', 'testimonial_rotator'),
				    'add_new_item' 			=> __('Add New Rotator', 'testimonial_rotator'),
				    'edit_item' 			=> __('Edit Rotator', 'testimonial_rotator'),
				    'new_item' 				=> __('New Rotator', 'testimonial_rotator'),
				    'all_items' 			=> __('All Rotators', 'testimonial_rotator'),
				    'view_item' 			=> __('View Rotator', 'testimonial_rotator'),
				    'search_items' 			=> __('Search Rotators', 'testimonial_rotator'),
				    'not_found' 			=>  __('No rotators found', 'testimonial_rotator'),
				    'not_found_in_trash' 	=> __('No rotators found in Trash', 'testimonial_rotator'), 
				    'parent_item_colon' 	=> '',
				    'menu_name'				=> __('Rotators', 'testimonial_rotator')
  					);
						
	$args = array(
					'labels' 				=> $labels,
					'public' 				=> false,
					'publicly_queryable' 	=> false,
					'show_ui' 				=> true, 
					'show_in_menu' 			=> false, 
					'query_var' 			=> true,
					'rewrite' 				=> array( 'with_front' => false ),
					'capability_type' 		=> 'post',
					'has_archive' 			=> false,
					'hierarchical' 			=> false,
					'menu_position' 		=> 26.7,
					'exclude_from_search' 	=> true,
					'supports' 				=> apply_filters( "testimonial_rotator_supports", array( 'title', 'custom-fields' ) ),
					'show_in_menu'  		=> 'edit.php?post_type=testimonial',
					);
					
	register_post_type( 'testimonial_rotator', apply_filters( 'testimonial_rotator_pt_rotator_args', $args )  );
}


// ON INSTALL FLUSH REWRITES FOR OUR NEW PERMALINKS
function testimonial_rotator_activate() 
{
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'testimonial_rotator_activate' );


// CREATE AND RETURN PIPED ROTATOR IDS
function testimonial_rotator_make_piped_string( $arr )
{
	return "|" . implode("|", (array) $arr) . "|";
}
function testimonial_rotator_break_piped_string( $arr )
{
	return array_filter( explode("|", (string) $arr), 'strlen' );
}



// SHORTCODE
function testimonial_rotator_shortcode( $atts )
{
	return get_testimonial_rotator( $atts );
}



// GET A ROTATOR (YOU CAN USE THIS, ALSO USED BY SHORTCODE
function get_testimonial_rotator( $atts )
{
	ob_start();
	testimonial_rotator( $atts );
	$output = ob_get_contents();
	ob_end_clean();
	return $output;
}



// MEAT & POTATOES OF THE ROTATOR
function testimonial_rotator($atts)
{
	global $wp_query;


	// GET ID 
	$id = isset($atts['id']) ? $atts['id'] : false;


	// GET ROTATOR
	if( $id )
	{
		$rotator = get_post( $id );
		if( !$rotator ) testimonial_rotator_error( sprintf( __('Rotator could not be found with ID: %d', 'testimonial_rotator' ), $id ) );
	
		// ROTATOR SLUG
		$rotator_slug = $rotator->post_name;
	}
	else
	{
		$rotator_slug = "all";
	}
	
	
	// GET OVERRIDE SETTINGS FROM WIDGET OR SHORTCODE
	$extra_classes	 	= isset($atts['extra_classes']) ? $atts['extra_classes'] : "";
	$timeout 			= (int) isset($atts['timeout']) ? $atts['timeout'] : false;
	$speed 				= (int) isset($atts['speed']) ? $atts['speed'] : false;
	$fx 				= (int) isset($atts['fx']) ? $atts['fx'] : false;
	$shuffle 			= (isset($atts['shuffle']) AND $atts['shuffle'] == 1) ? 1 : 0;
	$post_count			= isset($atts['limit']) ? (int) $atts['limit'] : false;
	$format				= isset($atts['format']) ? $atts['format'] : "rotator";
	$show_title 		= isset($atts['hide_title']) ? false : true;
	$is_widget 			= isset($atts['is_widget']) ? true : false;
	$show_size 			= (isset($atts['show_size']) AND $atts['show_size'] == "excerpt") ? "excerpt" : "full";
	$title_heading 		= (isset($atts['title_heading'])) ? $atts['title_heading'] : apply_filters('testimonial_rotator_title_heading', 'h2');
	$show_microdata		= (isset($atts['hide_microdata'])) ? false : true;
	$auto_height		= (isset($atts['auto_height'])) ? $atts['auto_height'] : apply_filters('testimonial_rotator_auto_height', 'calc');
	$vertical_align		= (isset($atts['vertical_align']) AND $atts['vertical_align'] == 1) ? 1 : 0;
	$div_selector		= (isset($atts['div_selector'])) ? $atts['div_selector'] : apply_filters('testimonial_rotator_div_selector', '> div.slide');
	$pause_on_hover		= (isset($atts['no_pause_on_hover'])) ? 'false' : 'true';
	$hide_image			= (isset($atts['hide_image'])) ? true : false;
	$prev_next			= (isset($atts['prev_next'])) ? true : false;
	$paged				= (isset($atts['paged'])) ? true : false;
	$template_name		= (isset($atts['template'])) ? $atts['template'] : 'default';
	$img_size			= (isset($atts['img_size'])) ? $atts['img_size'] : false;
	
	
	// SET DEFAULT SETTINGS IF NOT SET
	if(!$timeout) 			{ $timeout 			= (int) get_post_meta( $id, '_timeout', true ); }
	if(!$speed) 			{ $speed 			= (int) get_post_meta( $id, '_speed', true ); }
	if(!$fx)				{ $fx 				= get_post_meta( $id, '_fx', true ); }
	if(!$shuffle)			{ $shuffle 			= get_post_meta( $id, '_shuffle', true ); }
	if(!$vertical_align)	{ $vertical_align 	= get_post_meta( $id, '_verticalalign', true ); }
	if(!$hide_image)		{ $hide_image 		= get_post_meta( $id, '_hidefeaturedimage', true ); }
	if(!$prev_next)			{ $prev_next 		= get_post_meta( $id, '_prevnext', true ); }
	if(!$post_count)		{ $post_count 		= (int) get_post_meta( $id, '_limit', true ); }
	if(!$template_name)		{ $template_name 	= get_post_meta( $id, '_template', true ); }
	if(!$img_size)			{ $img_size 		= get_post_meta( $id, '_img_size', true ); }
	if( $show_microdata )
	{
		$hide_microdata = get_post_meta( $id, '_hide_microdata', true );
		$show_microdata = $hide_microdata ? false: true;
	}
	
	
	// SANATIZE SETTINGS
	if(!$timeout) 	{ $timeout = 5; }
	if(!$speed) 	{ $speed = 1; }
	$timeout 		= round($timeout * 1000);
	$speed 			= round($speed * 1000);
	$post_count     = (!$post_count) ? -1 : $post_count;
	if( $format != "rotator" ) $prev_next = false;
	if( !$img_size ) $img_size = 'thumbnail';
	if( $format == "list" AND $prev_next ) { $paged = true; }
	
	
	// FILTER AVAILABLE FOR PAUSE ON HOVER
	// ONE PARAMETER PASSED IS THE ID OF THE ROTATOR
	$pause_on_hover  = apply_filters('testimonial_rotator_hover', $pause_on_hover, $id );
	
	
	// STAR ICON
	$testimonial_rotator_star 	= apply_filters( 'testimonial_rotator_star', 'fa-star', $template_name, $id );
	
	
	// IF ID, QUERY FOR JUST THAT ROTATOR
	$meta_query = array();
	if( $id )
	{
		$meta_query = array( 'relation' => 'OR',
										array(
											'key' 		=> '_rotator_id',
											'value' 	=> $id
										),
										array(
											'key' 		=> '_rotator_id',
											'value' 	=> '|' . $id . '|',
											'compare'	=> 'LIKE'
										));
	}
	
	
	// GET TESTIMONIALS
	$order_by = ($shuffle) ? "rand" : "menu_order";
	$testimonials_args = array( 
								'post_type' => 'testimonial', 
								'order' => 'ASC', 
								'orderby' => $order_by,
								'posts_per_page' => $post_count,
								'meta_query' => $meta_query
							);
							
	if( $paged )
	{
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		$testimonials_args['paged'] = $paged;
	}
							
							
	query_posts( apply_filters( 'testimonial_rotator_display_args', $testimonials_args, $id ) );


	// ROTATOR CLASSES
	$cycle_class 						= ($format == "rotator") ? " cycletwo-slideshow" : "";
	$rotator_class_prefix 				= ($is_widget) ? "_widget" : "";
	if($extra_classes) $cycle_class 	.= " $cycle_class ";
	$cycle_class 						.= " format-{$format}";
	$cycle_class 						.= " template-{$template_name}";
	$extra_wrap_class 					= "";
	
	// VERTICAL ALIGN
	$centered = "";
	if($vertical_align)
	{
		$centered = " data-cycletwo-center-horz=\"true\" data-cycletwo-center-vert=\"true\" ";
	}
	
	
	// PREV/NEXT BUTTON
	$prevnextdata 	= "";
	if( $prev_next )
	{
		$prevnextdata = " data-cycletwo-next=\"#testimonial_rotator{$rotator_class_prefix}_wrap_{$id} .testimonial_rotator_next\" data-cycletwo-prev=\"#testimonial_rotator{$rotator_class_prefix}_wrap_{$id} .testimonial_rotator_prev\" ";
		$extra_wrap_class .= " with-prevnext ";

		// PREV / NEXT FONT AWESOME ICONS, FILTER READY
		if( $fx == "scrollVert")
		{
			$prev_fa_icon 	= apply_filters( 'testimonial_rotator_fa_icon_prev_vert', 'fa-chevron-down', $id );
			$next_fa_icon 	= apply_filters( 'testimonial_rotator_fa_icon_next_vert', 'fa-chevron-up', $id );
		}
		else
		{
			$prev_fa_icon 	= apply_filters( 'testimonial_rotator_fa_icon_prev', 'fa-chevron-left', $id );
			$next_fa_icon 	= apply_filters( 'testimonial_rotator_fa_icon_next', 'fa-chevron-right', $id );
		}
	}
	
	
	// SWIPE FILTER
	$touch_swipe = apply_filters( 'testimonial_rotator_swipe', 'true', $id );
	
	// EXTRA DATA ATTRIBUTE FILTER
	$extra_data_attributes 		= apply_filters( 'testimonial_rotator_data_attributes', '', $template_name, $id );
	
	$global_rating = 0;

	if ( have_posts() )
	{
		echo "<div id=\"testimonial_rotator{$rotator_class_prefix}_wrap_{$id}\" class=\"testimonial_rotator{$rotator_class_prefix}_wrap{$extra_wrap_class}\">\n";
		echo "	<div id=\"testimonial_rotator{$rotator_class_prefix}_{$id}\" class=\"testimonial_rotator hreview-aggregate{$rotator_class_prefix}{$cycle_class}\" data-cycletwo-timeout=\"{$timeout}\" data-cycletwo-speed=\"{$speed}\" data-cycletwo-pause-on-hover=\"{$pause_on_hover}\" {$centered} data-cycletwo-swipe=\"{$touch_swipe}\" data-cycletwo-fx=\"{$fx}\" data-cycletwo-auto-height=\"{$auto_height}\" {$prevnextdata}data-cycletwo-slides=\"{$div_selector}\" {$extra_data_attributes}>\n";
		
		do_action( 'testimonial_rotator_slides_before' );

		$slide_count = 1;
		while ( have_posts() )
		{
			the_post();
			
			// HAS IMAGE, CAN BE HIDDEN IN ROTATOR SETTINGS
			$has_image = has_post_thumbnail() ? "has-image" : false;
			if( $hide_image ) $has_image = false;
			
			// DATA
			$itemreviewed 		= get_post_meta( $id, '_itemreviewed', true );
			$cite 				= get_post_meta( get_the_ID(), '_cite', true );
			$rating 			= (int) get_post_meta( get_the_ID(), '_rating', true );
			
			// LOOK FOR TEMPLATE IN THEME
			$template = locate_template( array( "loop-testimonial-{$rotator_slug}.php", "loop-testimonial-{$id}.php", "loop-testimonial.php" ) );
			
			// LOOK IN PLUGIN
			if( !$template )
			{
				$template = dirname(__FILE__) . "/templates/loop-testimonial.php";
			}
			
			// LOAD TEMPLATE
			if( $template )
			{
				include( $template );
			}
			
			// SLIDE COUNTER
			$slide_count++;
		}
		
		
		// GLOBAL RATING
		$post_count = $wp_query->post_count;
		$global_rating_number = floor($global_rating / $post_count);
		
		if( $global_rating_number AND $show_microdata )
		{
			echo "<div class=\"testimonial_rotator_microdata\">\n";
			echo "\t<div class=\"rating\">{$global_rating_number}.0</div>\n";
			echo "\t<div class=\"count\">{$post_count}</div>\n";
			echo "</div>\n";
		}

		do_action( 'testimonial_rotator_after' );
		
		echo "</div> <!-- #testimonial_rotator{$rotator_class_prefix}_{$id} -->\n";
		
		// PREVIOUS / NEXT
		if( $prev_next AND $post_count > 1)
		{
			echo "<div class=\"testimonial_rotator_nav\">";
				echo "	<div class=\"testimonial_rotator_prev\"><i class=\"fa {$prev_fa_icon}\"></i></div>";
				echo "	<div class=\"testimonial_rotator_next\"><i class=\"fa {$next_fa_icon}\"></i></div>";
			echo "</div>\n";
		}
		
		echo "</div><!-- .testimonial_rotator{$rotator_class_prefix}_wrap -->\n\n";
	}
	
	if( $paged )
	{
		echo "<div class=\"testimonial_rotator_paged cf-tr\">";
			next_posts_link( __('Next Testimonials', 'testimonial_rotator') . ' <i class="fa fa-angle-double-right"></i>' );
			previous_posts_link( '<i class="fa fa-angle-double-left"></i> ' . __('Previous Testimonials', 'testimonial_rotator') );
		echo "</div>\n";
	}
	
	wp_reset_postdata();
	wp_reset_query();
}


// WIDGET
class TestimonialRotatorWidget extends WP_Widget
{
	function TestimonialRotatorWidget()
	{
		$widget_ops = array('classname' => 'TestimonialRotatorWidget', 'description' => __('Displays rotating testimonials', 'testimonial_rotator') );
		parent::__construct('TestimonialRotatorWidget', __('Testimonials Rotator', 'testimonial_rotator'), $widget_ops);
	}
 
	function form($instance)
	{
		$rotators = get_posts( array( 'post_type' => 'testimonial_rotator', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
		
		$title 			= isset($instance['title']) ? $instance['title'] : "";
		$rotator_id 	= isset($instance['rotator_id']) ? $instance['rotator_id'] : 0;
		$format			= isset($instance['format']) ? $instance['format'] : "rotator";
		$shuffle		= (isset($instance['shuffle']) AND $instance['shuffle'] == 1) ? 1 : 0;
		$limit 			= (int) isset($instance['limit']) ? $instance['limit'] : 5;
		$show_size 		= (isset($instance['show_size']) AND $instance['show_size'] == "full") ? "full" : "excerpt";
		
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'testimonial_rotator'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
	
		<p>
		<label for="<?php echo $this->get_field_id('rotator_id'); ?>"><?php _e('Rotator', 'testimonial_rotator'); ?>:
		<select name="<?php echo $this->get_field_name('rotator_id'); ?>" class="widefat" id="<?php echo $this->get_field_id('rotator_id'); ?>">
			<option value=""><?php _e('All Rotators', 'testimonial_rotator'); ?></option>
			<?php foreach($rotators as $rotator) { ?>
			<option value="<?php echo $rotator->ID ?>" <?php if($rotator->ID == $rotator_id) echo " SELECTED"; ?>><?php echo $rotator->post_title ?></option>
			<?php } ?>
		</select>
		</label>
		</p>
		
		<p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Limit:', 'testimonial_rotator'); ?> <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" /></label></p>

		<p>
			<label for="<?php echo $this->get_field_id('format'); ?>"><?php _e('Display:', 'testimonial_rotator'); ?></label> &nbsp;
			<input id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" value="rotator" type="radio"<?php if($format != "list") echo " checked='checked'"; ?>> <?php _e('Rotator', 'testimonial_rotator'); ?> &nbsp;
			<input id="<?php echo $this->get_field_id('format'); ?>" name="<?php echo $this->get_field_name('format'); ?>" value="list" type="radio"<?php if($format == "list") echo " checked='checked'"; ?>> <?php _e('List', 'testimonial_rotator'); ?>
		</p>		
		
		<p>
			<label for="<?php echo $this->get_field_id('show_size'); ?>"><?php _e('Show as:', 'testimonial_rotator'); ?></label> &nbsp;
			<input id="<?php echo $this->get_field_id('show_size'); ?>" name="<?php echo $this->get_field_name('show_size'); ?>" value="full" type="radio"<?php if($show_size == "full") echo " checked='checked'"; ?>> <?php _e('Full', 'testimonial_rotator'); ?>&nbsp;
			<input id="<?php echo $this->get_field_id('show_size'); ?>" name="<?php echo $this->get_field_name('show_size'); ?>" value="excerpt" type="radio"<?php if($show_size == "excerpt") echo " checked='checked'"; ?>> <?php _e('Excerpt', 'testimonial_rotator'); ?>
		</p>
		
		<hr>
		
		<p>
			<input id="<?php echo $this->get_field_id('shuffle'); ?>" name="<?php echo $this->get_field_name('shuffle'); ?>" value="1" type="checkbox"<?php if($shuffle) echo " checked='checked'"; ?>>&nbsp;
			<label for="<?php echo $this->get_field_id('shuffle'); ?>"><?php _e('Randomize Testimonials', 'testimonial_rotator'); ?></label>
		</p>
		
	<?php
	}
 
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] 			= $new_instance['title'];
		$instance['rotator_id'] 	= $new_instance['rotator_id'];
		$instance['format'] 		= $new_instance['format'];
		$instance['shuffle'] 		= isset($new_instance['shuffle']) ? 1 : 0;
		$instance['show_size'] 		= $new_instance['show_size'];
		$instance['limit'] 			= $new_instance['limit'];
		return $instance;
	}
 
	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP);
		
		$widget_title 		= isset($instance['title']) ? $instance['title'] : false;
		echo $before_widget;
		
		if ( $widget_title ) { echo $before_title . $widget_title . $after_title; }
		
		$instance['id'] 			= $instance['rotator_id'];
		$instance['is_widget'] 		= true;
		
		
		// HOOK INTO A WIDGET BEFORE IT GETS LOADED
		apply_filters( 'testimonial_rotator_pre_widget_instance', $instance, $instance['rotator_id']);
		
		testimonial_rotator( $instance );
		
		echo $after_widget;
	}
}

