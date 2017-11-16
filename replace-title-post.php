
<?php
// Do not load directly...
if ( ! defined( 'ABSPATH' ) ) { die( 'Direct access forbidden.' ); }
/*
 
* Plugin Name: Replace Post Title by Kentos

* Description: You can replace word, character or simbol in title post.
 
* Version: 1.0
 
* Author: Amri Karisma
 
* Author URI: https://amrikarisma.com
 
*/



add_filter('wp_footer','get_update_title');
add_filter('the_content','wp_update_title');



function get_update_title($content)
{
	$content = wp_update_title() . $content;
	return $content;

}
// this function to change all title to myself
function wp_update_title(){
	if(is_single() || is_attachment()) :
	$my_postid = get_the_ID();//This is page id or post id
	$content_post = get_post($my_postid);
	    // Update post 37
	$my_post = array(
		'ID'           => $content_post->ID,
		'post_title'   => to_title_case( $content_post->post_title ) );

	if($content_post->post_title!=to_title_case($content_post->post_title )){


	// Update the post into the database
		wp_update_post( $my_post );

	}
	endif;
}

function to_title_case( $string ) {
	if(is_single() || is_attachment()) :
	/* Words that should be entirely lower-case */
	$articles_conjunctions_prepositions = array(
		'a','an','the',
		'and','but','or','nor',
		'if','then','else','when',
		'at','by','from','for','in',
		'off','on','out','over','to','into','with','of'
		);
	/* Words that should be entirely upper-case (need to be lower-case in this list!) */
	$acronyms_and_such = array(
		'3d', 'gt', 'wpse', 'wtf','hd','pc','bmw'
		);
	/* Identify comma in string*/
	$comma = array(',');

	/* delimiter string*/
	$delimiter = ' ';
	/* split title string into array of words */
	$words = explode( $delimiter, mb_strtolower( $string ) );
	/* iterate over words */
	foreach ( $words as $position => $word ) {
		if( in_array( $word, $comma ) ) {
             $words[$position] = preg_replace('/[^A-Za-z0-9\-]/', ' ', $word); // Removes special chars.
             /* re-capitalize acronyms */
         }
         if( in_array( $word, $acronyms_and_such ) ) {
         	$words[$position] = mb_strtoupper( $word );
         	/* capitalize first letter of all other words, if... */
         } elseif (
         	/* ...first word of the title string... */
         	0 === $position ||
         	/* ...or not in above lower-case list*/
         	! in_array( $word, $articles_conjunctions_prepositions ) 
         	) {
         	$words[$position] = ucwords( $word );
         }
     }         
     /* re-combine word array */
     $string = implode( ' ', $words );
     /* return title string in title case */
     return $string;
	endif;

 }