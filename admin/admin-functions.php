<?php


/* ADDS META BOXES TO THE ADD NEW TESTIMONIAL PAGE */
function testimonial_rotator_create_metaboxes() 
{
	// TESTIMONIAL OPTIONS
	add_meta_box( 'testimonial_rotator_metabox_select', __('Testimonial Options', 'testimonial_rotator'), 'testimonial_rotator_metabox_select', 'testimonial', 'normal', 'default' );
	
	// TESTIMONIAL IMAGE
	add_meta_box( 'postimagediv', __('Testimonial Image', 'testimonial_rotator'), 'post_thumbnail_meta_box', 'testimonial', 'normal', 'default');
	
	// TESTIMONIAL ORDER
	add_meta_box( 'pageparentdiv', __('Testimonial Order', 'testimonial_rotator'), 'page_attributes_meta_box', 'testimonial', 'normal', 'default');
	
	// IF ON EDIT SHOW THE SHORTCODE
	if(isset($_GET['action']) AND $_GET['action'] == "edit")
	{
		add_meta_box( 'testimonial_rotator_shortcode_metabox', __('Rotator Shortcode', 'testimonial_rotator'), 'testimonial_rotator_shortcode_metabox', 'testimonial_rotator', 'normal', 'default' );
	}
	
	// ROTATOR OPTIONS
	add_meta_box( 'testimonial_rotator_metabox_effects', __('Rotator Options', 'testimonial_rotator'), 'testimonial_rotator_metabox_effects', 'testimonial_rotator', 'normal', 'default' );
}



/* TITLE INPUT FOR TESTIMONIALS */
function register_testimonial_form_title( $title )
{
     $screen = get_current_screen();
     if  ( $screen->post_type == 'testimonial' ) 
     {
          return __('Enter Highlight Here', 'testimonial_rotator');
     }
}



/* ADMIN COLUMNS IN LIST VIEW */
function testimonial_rotator_columns( $columns ) 
{
	$columns = array(
		'cb'       => '<input type="checkbox" />',
		'image'    => __('Image', 'testimonial_rotator'),
		'title'    => __('Title', 'testimonial_rotator'),
		'rating'    => __('Rating', 'testimonial_rotator'),
		'ID'       => __('Rotator', 'testimonial_rotator'),
		'order'    => __('Order', 'testimonial_rotator')	
	);

	return $columns;
}

function testimonial_rotator_column_sort($columns)
{
	$columns = array(
		'title'    => __('Title', 'testimonial_rotator'),
		'ID'       => __('Rotator', 'testimonial_rotator'),
		'date'     => __('Date', 'testimonial_rotator')
	);

	return $columns;
}


function testimonial_rotator_add_columns( $column ) 
{
	global $post;
	$edit_link = get_edit_post_link( $post->ID );
	$rotator_ids = testimonial_rotator_break_piped_string( get_post_meta( $post->ID, "_rotator_id", true ) );
	
	$rotator_title_array = array();
	foreach($rotator_ids as $rotator_id) { $rotator_title_array[] = get_the_title( $rotator_id ); }


	if ( $column == 'ID' ) 		{ echo implode(", ", $rotator_title_array); }
	if ( $column == 'image' ) 	echo '<a href="' . $edit_link . '" title="' . $post->post_title . '">' . get_the_post_thumbnail( $post->ID, array( 50, 50 ), array( 'title' => trim( strip_tags(  $post->post_title ) ) ) ) . '</a>';
	if ( $column == 'order' ) 	echo '<a href="' . $edit_link . '">' . $post->menu_order . '</a>';
	if ( $column == 'rating' ) 	echo get_post_meta( $post->ID, "_rating", true );
}


function testimonial_rotator_rotator_columns( $columns ) 
{
	$columns = array(
		'cb'       		=> '<input type="checkbox" />',
		'title'    		=> __('Title', 'testimonial_rotator'),
		'shortcode'		=> __('Shortcodes', 'testimonial_rotator')
	);

	return $columns;
}

function testimonial_rotator_rotator_add_columns( $column ) 
{
	global $post;
	if ( $column == 'shortcode' )  	{ 	echo '
												<b>' . __('Use Rotator Settings' , 'testimonial_rotator') . '</b><br />
												[testimonial_rotator id=' . $post->ID . ']<br /><br />
												
												<b>' . __('Display as List' , 'testimonial_rotator') . '</b><br />
												[testimonial_rotator id=' . $post->ID . ' format=list]
											'; }
}


/* ROTATOR SUBMENU PAGE */
function register_testimonial_rotator_submenu_page()
{
	global $current_user;
	
	// ABILITY TO EDIT ROTATORS FOR ADMINS
	add_submenu_page( 'edit.php?post_type=testimonial', __('Add Rotator', 'testimonial_rotator'), __('Add Rotator', 'testimonial_rotator'), 'manage_options', 'post-new.php?post_type=testimonial_rotator' ); 
	
	
	// SETTINGS PAGE
	add_submenu_page( 'edit.php?post_type=testimonial', __('Settings', 'testimonial_rotator'), __('Settings', 'testimonial_rotator'), 'manage_options', 'testimonial_rotator', 'testimonial_rotator_settings_callback' );
	
	
	if( !current_user_can('manage_options') )
	{
		$current_user_roles = (array) $current_user->roles;
		
		
			
		// ADD THE EDIT ROTATOR PAGE FOR OTHER ROLES THAT ARE SELECTED IN SETTINGS
		$creator_setting = (array) get_option( 'testimonial-rotator-creator-role' );
		
		if( $creator_setting AND $current_user_roles )
		{
		
			foreach( $current_user_roles as $role)
			{
				if( in_array( $role, $creator_setting))
				{
					add_submenu_page( 'edit.php?post_type=testimonial', __('Rotators', 'testimonial_rotator'), __('Rotators', 'testimonial_rotator'), $role, 'edit.php?post_type=testimonial_rotator' ); 
					add_submenu_page( 'edit.php?post_type=testimonial', __('Add New', 'testimonial_rotator'), __('Add New', 'testimonial_rotator'), $role, 'post-new.php?post_type=testimonial_rotator' ); 
					break;
				}
			}
		}
	}
}




/* ADMIN ICON */
function testimonial_rotator_cpt_icon() 
{
	global $wp_version;
	
	if($wp_version >= 3.8)
	{
		echo '
			<style> 
				#adminmenu #menu-posts-testimonial div.wp-menu-image:before { content: "\f205"; }
			</style>
		';	
	}
	else
	{
?>
	<style type="text/css" media="screen">
		#menu-posts-testimonial .wp-menu-image { background: url(<?php echo TESTIMONIAL_ROTATOR_URI . '/images/thumb-up.png'; ?>) no-repeat 6px -17px !important; }
		#menu-posts-testimonial:hover .wp-menu-image, #menu-posts-testimonial.wp-has-current-submenu .wp-menu-image { background-position: 6px 7px!important; }	
	</style>
<?php 
	}
}


// SET SETTINGS LINK ON PLUGIN PAGE
function testimonial_rotator_plugin_action_links( $links, $file ) 
{
	$donate_link 		= '<a href="https://halgatewood.com/donate" target="_blank">' . esc_html__( 'Donate', 'testimonial_rotator' ) . '</a>';
	$settings_link 		= '<a href="' . admin_url( 'edit.php?post_type=testimonial&page=testimonial_rotator' ) . '">' . esc_html__( 'Settings', 'testimonial_rotator' ) . '</a>';
	
	if ( $file == 'testimonial-rotator/testimonial-rotator.php' )
	{
		array_unshift( $links, $settings_link );
		array_unshift( $links, $donate_link );
	}
	
	return $links;
}
add_filter( 'plugin_action_links', 'testimonial_rotator_plugin_action_links', 10, 2 );


// MEDIA BUTTON
function testimonial_rotator_button() 
{
	global $pagenow, $typenow, $wp_version;
	$output = '';
	if ( version_compare( $wp_version, '3.5', '>=' ) AND in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'testimonial' ) 
	{
		$img = '<style>#testimonial-rotator-media-button::before { font: 400 18px/1 dashicons; content: \'\f205\'; }</style><span class="wp-media-buttons-icon" id="testimonial-rotator-media-button"></span>';
		$output = '<a href="#TB_inline?width=640&inlineId=add-testimonial-rotator" class="thickbox button testimonial-rotator-thickbox" title="' .  __( 'Add Rotator', 'testimonial_rotator'  ) . '" style="padding-left: .4em;"> ' . $img . __( 'Add Rotator', 'testimonial_rotator'  ) . '</a>';
	}
	echo $output;
}
add_action( 'media_buttons', 'testimonial_rotator_button', 11 );


// MEDIA BUTTON FUNCTIONALITY
function testimonial_rotator_admin_footer_for_thickbox() 
{
	global $pagenow, $typenow, $wp_version;

	// Only run in post/page creation and edit screens
	if ( version_compare( $wp_version, '3.5', '>=' ) AND in_array( $pagenow, array( 'post.php', 'page.php', 'post-new.php', 'post-edit.php' ) ) && $typenow != 'testimonial' ) { ?>
		<script type="text/javascript">
            function insertTestimonialRotator() 
            {
            	var id = jQuery('#testimonial-rotator-select-box').val();
                if ('' === id)
                {
                    alert('<?php _e( "You must choose a rotator", "testimonial_rotator" ); ?>');
                    return;
                }
                window.send_to_editor('[testimonial_rotator id="' + id + '"]');
            }
		</script>

		<div id="add-testimonial-rotator" style="display: none;">
			<div class="wrap" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;">

				<?php
				
				$rotators = get_posts( array( 'post_type' => 'testimonial_rotator', 'numberposts' => -1, 'orderby' => 'title', 'order' => 'ASC' ) );
				
				if( $rotators ) { ?>
					<select id="testimonial-rotator-select-box" style="clear: both; display: block; margin-bottom: 1em;">
						<option value=""><?php _e('Choose a Rotator', 'testimonial_rotator'); ?></option>
						<?php
							foreach ( $rotators as $rotator ) 
							{
								echo '<option value="' . $rotator->ID . '">' . $rotator->post_title . '</option>';
							}
						?>
					</select>
				<?php } else { echo __('No rotators have been created yet. Please create one first and then you will be able to select it here.', 'testimonial_Rotator'); } ?>

				<p class="submit">
					<input type="button" id="testimonial-rotator-insert-download" class="button-primary" value="<?php echo __( 'Insert Rotator', 'testimonial_rotator' ); ?>" onclick="insertTestimonialRotator();" />
					<a id="testimonial-rotator-cancel-add" class="button-secondary" onclick="tb_remove();" title="<?php _e( 'Cancel', 'testimonial_rotator' ); ?>"><?php _e( 'Cancel', 'testimonial_rotator' ); ?></a>
				</p>
			</div>
		</div>
	<?php
	}
}
add_action( 'admin_footer', 'testimonial_rotator_admin_footer_for_thickbox' );