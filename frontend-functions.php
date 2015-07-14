<?php



// SINGLE TESTIMONIAL
function testimonial_rotator_single( $content )
{
	// SINGLE TESTIMONIAL
	if( is_single() AND get_post_type() == "testimonial")
	{
		// LOOK FOR single-testimonial IN TEMPLATE IN THEME
		$template = locate_template( array( "single-testimonial.php" ) );
			
		if( !$template )
		{
			// NO CUSTOM TEMPLATE, MODIFY THE CONTENT
			ob_start();
			include( dirname(__FILE__) . "/templates/single-testimonial.php" );
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}
	}
	
	return $content;
}

if( !is_admin() )
{
	add_filter( 'the_content', 'testimonial_rotator_single' );
}



// READ MORE, WHEN EXCERPT IT USED
function testimonial_rotator_excerpt_more( $more ) 
{
	global $post;
	if( $post->post_type == "testimonial" )
	{
		return ' <a href="' . get_permalink( $post->id ) . '" class="testimonial-rotator-view-more">' . apply_filters('testimonia_rotator_view_more', __('View Full', 'testimonial_rotator')) . ' &rarr;</a>';
	}
}
add_filter( 'excerpt_more', 'testimonial_rotator_excerpt_more' );



// ERROR HANDLING
function testimonial_rotator_error( $msg )
{
	$error_handling = get_option( 'testimonial-rotator-error-handling' );
	if(!$error_handling) $error_handling = "source";
	if(!$msg) $msg = __('Something unknown went wrong', 'testimonial_rotator');
	
	if( $error_handling == "display-admin")
	{
		// DISPLAY ADMIN
		if ( current_user_can( 'manage_options' ) ) 
		{
			echo "<div class='testimonial-rotator-error'>" . $msg . "</div>";
		}
	}
	else if( $error_handling == "display-all")
	{
		// DISPLAY ALL
		echo "<div class='testimonial-rotator-error'>" . $msg . "</div>";
	}
	else
	{
		return apply_filters( 'testimonial_rotator_error', "<!-- TESTIMONIAL ROTATOR ERROR: " . $msg . " -->" );
	}
}