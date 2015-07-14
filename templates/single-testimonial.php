<?php

global $post;

$rotator_id 		= get_post_meta( get_the_ID(), '_rotator_id', true );
$rotator_ids		= (array) testimonial_rotator_break_piped_string($rotator_id); 
$rotator_id			= reset($rotator_ids);

$itemreviewed 		= get_post_meta( $rotator_id, '_itemreviewed', true );
$img_size 			= get_post_meta( $rotator_id, '_img_size', true );
$cite 				= get_post_meta( get_the_ID(), '_cite', true );
$rating 			= (int) get_post_meta( get_the_ID(), '_rating', true );
$has_image 			= has_post_thumbnail() ? "has-image" : false;


// WRAPPER
echo "<div class=\"testimonial_rotator_single hreview itemreviewed item {$has_image} cf-tr\">\n";		

// POST THUMBNAIL
if ( $has_image )
{ 
	echo "	<div class=\"testimonial_rotator_img img\">" . get_the_post_thumbnail( get_the_ID(), $img_size ) . "</div>\n"; 
}

// DESCRIPTION
echo "	<div class=\"text testimonial_rotator_description\">\n";

// RATING
if($rating)
{
	echo "<div class=\"testimonial_rotator_stars cf-tr\">\n";
	for($r=1; $r <= $rating; $r++)
	{
		echo "	<span class=\"testimonial_rotator_star testimonial_rotator_star_$r\"><i class=\"fa {$testimonial_rotator_star}\"></i></span>";
	}
	echo "</div>\n";
}

// CONTENT
echo "<div class=\"testimonial_rotator_quote\">\n";
echo get_the_content();
echo "</div>\n";

// AUTHOR INFO
if( $cite )
{
	echo "<div class=\"testimonial_rotator_author_info cf-tr\">\n";
	echo wpautop($cite);
	echo "</div>\n";				
}

echo "	</div>\n";
	
// MICRODATA
echo "	<div class=\"testimonial_rotator_microdata\">\n";

	if($itemreviewed) echo "\t<div class=\"fn\">{$itemreviewed}</div>\n";
	if($rating) echo "\t<div class=\"rating\">{$rating}.0</div>\n";
	
	echo "	<div class=\"dtreviewed\"> " . get_the_date('c') . "</div>";
	echo "	<div class=\"reviewer\"> ";
		echo "	<div class=\"fn\"> " . wpautop($cite) . "</div>";
		if ( has_post_thumbnail() ) { echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array('class' => 'photo' )); }
	echo "	</div>";
	echo "	<div class=\"summary\"> " . $post->post_excerpt . "</div>";
	echo "	<div class=\"permalink\"> " . get_permalink() . "</div>";

echo "	</div> <!-- .testimonial_rotator_microdata -->\n";

echo "</div>\n";