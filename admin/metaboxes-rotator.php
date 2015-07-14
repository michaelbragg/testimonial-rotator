<?php

/* SHORTCODE DISPLAY HELPER */
function testimonial_rotator_shortcode_metabox()
{
	global $post;
	
	echo '
		<b>' . __('Base Rotator (Uses rotator options set on this page)', 'testimonial_rotator') . '</b><br />
		[testimonial_rotator id=' . $post->ID . ']<br /><br />
		
		<b>' . __('List All Testimonials', 'testimonial_rotator') . '</b><br />
		[testimonial_rotator id=' . $post->ID . ' format="list"]<br /><br />
		
		<b>' . __('Limit Results to 10 and list', 'testimonial_rotator') . '</b><br />
		[testimonial_rotator id=' . $post->ID . ' format="list" limit="10"]<br /><br />
			
		<b>' . __('Hide Titles', 'testimonial_rotator') . '</b><br />
		[testimonial_rotator id=' . $post->ID . ' hide_title="true"]<br /><br />
		
		<b>' . __('Randomize Testimonials', 'testimonial_rotator') . '</b><br />
		[testimonial_rotator id=' . $post->ID . ' shuffle="true"]<br /><br />	
	';
	
	echo '<span class="description">' . __('Put one of the codes above where you want the testimonial rotator to appear.', 'testimonial_rotator') . '</span>';	
}


/* TESTIMONIAL ROTATOR EFFECTS AND TIMING */
function testimonial_rotator_metabox_effects()
{
	global $post;
	$timeout				= (int) get_post_meta( $post->ID, '_timeout', true );
	$speed					= (int) get_post_meta( $post->ID, '_speed', true );
	$fx						= get_post_meta( $post->ID, '_fx', true );
	$shuffle				= get_post_meta( $post->ID, '_shuffle', true );
	$verticalalign			= get_post_meta( $post->ID, '_verticalalign', true );
	$prevnext				= get_post_meta( $post->ID, '_prevnext', true );
	$hidefeaturedimage		= get_post_meta( $post->ID, '_hidefeaturedimage', true );
	$hide_microdata			= get_post_meta( $post->ID, '_hide_microdata', true );
	$limit					= (int) get_post_meta( $post->ID, '_limit', true );
	$itemreviewed			= get_post_meta( $post->ID, '_itemreviewed', true );
	$template				= get_post_meta( $post->ID, '_template', true );
	$img_size				= get_post_meta( $post->ID, '_img_size', true );
	
	$available_effects = array('fade', 'fadeout', 'scrollHorz', 'scrollVert', 'none');
	$image_sizes = get_intermediate_image_sizes();
	
	if(!$timeout) 	{ $timeout = 5; }
	if(!$speed) 	{ $speed = 1; }
	if(!$template) 	{ $template = 'default'; }
	if(!$img_size) 	{ $template = 'thumbnail'; }
	?>
	
	<style>
		.hg_slider_column { width: 46%; margin: 0 2%; float: left; } 
		
		/* 680 */
		@media only screen and (max-width: 680px) {
			.hg_slider_column { width: 100%; margin: 0 0 20px 0; float: none; }
		}
	
	</style>
	
	<div class="hg_slider_column">
	<p>
		<select name="fx">
			<?php foreach($available_effects as $available_effect) { ?>
			<option value="<?php echo $available_effect ?>" <?php if($available_effect == $fx) echo " SELECTED"; ?>><?php echo $available_effect ?></option>
			<?php } ?>
		</select>
		<?php _e('Transition Effect', 'testimonial_rotator'); ?>
	</p>
	
	<p>
		<select name="img_size">
			<?php foreach($image_sizes as $image_size) { ?>
			<option value="<?php echo $image_size ?>" <?php if($image_size == $img_size) echo " SELECTED"; ?>><?php echo $image_size ?></option>
			<?php } ?>
		</select>
		<?php _e('Image Size', 'testimonial_rotator'); ?>
	</p>
	
	<p>
		<input type="text" style="width: 45px; text-align: center;" name="timeout" value="<?php echo esc_attr( $timeout ); ?>" maxlength="4" />
		<?php _e('Seconds per Testimonial', 'testimonial_rotator'); ?>
	</p>
	
	<p>
		<input type="text" style="width: 45px; text-align: center;" name="speed" value="<?php echo esc_attr( $speed ); ?>" maxlength="4" />
		<?php _e('Transition Speed (in seconds)', 'testimonial_rotator'); ?>
	</p>
	
	<p>
		<input type="text" style="width: 45px; text-align: center;" name="limit" value="<?php echo esc_attr( $limit ); ?>" maxlength="4" />
		<?php _e('Limit Number of Testimonials', 'testimonial_rotator'); ?>
	</p>
	
	</div>
	
	<div class="hg_slider_column">
	<p>
		<input type="checkbox" name="shuffle" value="1" <?php if($shuffle) echo " CHECKED"; ?> />
		<?php _e('Randomize Testimonials', 'testimonial_rotator'); ?>
	</p>
	
	<p>
		<input type="checkbox" name="verticalalign" value="1" <?php if($verticalalign) echo " CHECKED"; ?> />
		<?php _e('Vertical Align (Center Testimonials Height)', 'testimonial_rotator'); ?>
	</p>
	
	<p>
		<input type="checkbox" name="prevnext" value="1" <?php if($prevnext) echo " CHECKED"; ?> />
		<?php _e('Show Previous/Next Buttons', 'testimonial_rotator'); ?>
	</p>
	
	<p>
		<input type="checkbox" name="hidefeaturedimage" value="1" <?php if($hidefeaturedimage) echo " CHECKED"; ?> />
		<?php _e('Hide Featured Image', 'testimonial_rotator'); ?>
	</p>
	
	<p>
		<input type="checkbox" name="hide_microdata" value="1" onchange="if(this.checked) { jQuery('#item-reviewed-p').slideUp(); } else { jQuery('#item-reviewed-p').slideDown(); }" <?php if($hide_microdata) echo " CHECKED"; ?> />
		<?php _e('Hide Microdata (hReview)', 'testimonial_rotator'); ?>
	</p>
	
	</div>
	<div class="clear"></div>
	
	<p id="item-reviewed-p" style="border-top: solid 1px #ccc; margin-top: 15px; padding-top: 15px;<?php if($hide_microdata) echo " display:none;"; ?>">
		<label for="itemreviewed"><?php _e('Name of Item Being Reviewed (optional):', 'testimonial_rotator'); ?></label><br />
		<small><?php _e("Company Name, Product Name, etc. (not visible on your website, used for search engines)", 'testimonial_rotator'); ?></small><br />
		<input type="text" style="width: 80%;" id="itemreviewed" name="itemreviewed" value="<?php echo esc_attr( $itemreviewed ); ?>" />
	</p>

	<div style="padding: 15px 0; margin: 15px 0; border-top: solid 1px #ccc; border-bottom: solid 1px #ccc;">
	
		<?php if( defined('TESTIMONIAL_ROTATOR_THEME_PACK') ) { ?>
		
			<?php $available_themes = (array) apply_filters( 'testimonial_rotator_themes', array( 'default' => array('title' => 'Default', 'icon' => TESTIMONIAL_ROTATOR_URI . "/images/icon-default.png") ) ); ?>
			<p>
				<input type="text" name="template" id="testimonial_rotator_template" value="<?php echo $template; ?>" />
				<strong><?php _e('Select a Template:', 'testimonial_rotator'); ?></strong><br>
			</p>
			
			<div id="testimonial-rotator-templates">
			
				<?php foreach( $available_themes as $theme_slug => $available_theme ) { ?>
					<div style="float: left; text-align: center; padding: 10px; margin: 10px; min-height: 100px;">
						<a href="javascript:;" class="testimonial-rotator-template-selector" data-slug="<?php echo esc_attr($theme_slug); ?>"><img src="<?php echo $available_theme['icon']; ?>" style="width: 155px;<?php if($template == $theme_slug) echo " border: solid 2px #bee483;"; ?>"></a><br>
						<b><?php echo $available_theme['title']; ?></b> - <a href="javascript:;" class="testimonial-rotator-template-selector" data-slug="<?php echo esc_attr($theme_slug); ?>"><?php echo __('Use', 'testimonial_rotator'); ?></a>
					</div>
				<?php } ?>
				
				<div style="clear:both;"></div>
			</div>
		
		<?php } else { ?>
		
			<input type="hidden" name="template" id="testimonial_rotator_template" value="default" />
			<p><a href="https://halgatewood.com/downloads/testimonial-rotator-theme-pack/" target="_blank"><img src="https://halgatewood.com/wp-content/uploads/2014/05/testimonial-theme-pack-ad.png" style="max-width: 100%; width: 600px;"></a></p>
		
		<?php } ?>

	</div>

	<?php if($post->post_name) { ?>
	<p>
		<strong><?php _e('Make a Custom Template:', 'testimonial_rotator'); ?></strong><br>
		<?php _e('To create custom layouts for this rotator create a file called', 'testimonial_rotator'); ?>
		<b>loop-testimonial-<?php echo $post->post_name; ?>.php</b> <?php _e('and place it in your theme.', 'testimonial_rotator'); ?>
	</p>
	<?php } ?>

	<?php	
}

/* SAVE TESTIMONIAL ROTATOR META DATA */
function testimonial_rotator_save_rotator_meta( $post_id, $post ) 
{
	global $post;  
	if( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == "testimonial_rotator" )  
    {  
		// SAVE
		if ( isset( $_POST['fx'] ) ) 				{ update_post_meta( $post_id, '_fx', strip_tags( $_POST['fx'] ) ); }
		if ( isset( $_POST['timeout'] ) ) 			{ update_post_meta( $post_id, '_timeout', strip_tags( $_POST['timeout'] ) ); }
		if ( isset( $_POST['speed'] ) ) 			{ update_post_meta( $post_id, '_speed', strip_tags( $_POST['speed'] ) ); }
		if ( isset( $_POST['limit'] ) ) 			{ update_post_meta( $post_id, '_limit', strip_tags( $_POST['limit'] ) ); }
		if ( isset( $_POST['itemreviewed'] ) ) 		{ update_post_meta( $post_id, '_itemreviewed', strip_tags( $_POST['itemreviewed'] ) ); }
		if ( isset( $_POST['template'] ) ) 			{ update_post_meta( $post_id, '_template', strip_tags( $_POST['template'] ) ); }
		if ( isset( $_POST['img_size'] ) ) 			{ update_post_meta( $post_id, '_img_size', strip_tags( $_POST['img_size'] ) ); }
		
		update_post_meta( $post_id, '_shuffle', isset( $_POST['shuffle']) ? 1 : 0 );
		update_post_meta( $post_id, '_verticalalign', isset( $_POST['verticalalign']) ? 1 : 0 );
		update_post_meta( $post_id, '_prevnext', isset( $_POST['prevnext']) ? 1 : 0 );
		update_post_meta( $post_id, '_hidefeaturedimage', isset( $_POST['hidefeaturedimage']) ? 1 : 0 );
		update_post_meta( $post_id, '_hide_microdata', isset( $_POST['hide_microdata']) ? 1 : 0 );
	}
}