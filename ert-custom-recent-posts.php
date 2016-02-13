<?php
/*
* Plugin Name: ERT Recent Custom Posts
* Plugin URI: https://github.com/barisertugrul/ERT-Recent-Posts-For-Custom-Post-Types
* Description: This plugin, allows you to insert a "Recent Posts" widget in sidebar for your custom posts. İt includes date, category and thumbnail display options. Simple and easy to use. Thanks for using.
* Author: Barış ERTUĞRUL
* Version: 1.0
* Author URI: http://www.barisertugrul.com/ 
* Text Domain: ert_rcp
* Domain Path: /languages
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit( __( 'Sorry, you are not allowed to access this page directly.','ert_rcp' ) );
}

define( 'PLUGIN_DIR', dirname(__FILE__).'/' );

require_once(PLUGIN_DIR . '/include/widgets.php');

add_action("init","ert_add_script");
    function ert_add_script(){
        wp_register_style( "ert_css_file", plugins_url("css/style.css", __FILE__) );
        wp_enqueue_style( "ert_css_file" );
    }
function ert_load_plugin_textdomain() {
    load_plugin_textdomain( 'ert_rcp', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'ert_load_plugin_textdomain' );
	
function ert_widgets_init() {
	register_widget( 'ert_RecentCustomPosts' );
set_post_thumbnail_size( 50, 50);
add_image_size( 'ert_RPPT_thumb', 50, 50);
}
add_action( 'widgets_init', 'ert_widgets_init' );

function ert_display_post_meta( $args = array() ) {
	$default_args = array(
		'post_id'        => get_the_ID(),
		'author_link'    => true,
		'author_link_by' => __( 'by %s', 'ert_rcp' ),
		'post_date'      => true,
		'date_format'    => ert_get_option( 'ert_date_format', '' ),
		'categories'     => true,
		'comment_count'  => true,
		'rating_stars'   => true,
	);

	$args = wp_parse_args( $args, $default_args );

	$meta_pieces = array();

	if ( $args['author_link'] ) {
		$meta_pieces[] = sprintf( $args['author_link_by'], ert_get_post_author_link( $args['post_id'] ) );
	}

	if ( $args['post_date'] ) {
		$meta_pieces[] = ert_get_the_post_date( $args['post_id'], $args['date_format'] );
	}

	if ( $args['categories'] ) {
		$meta_piece_categories = ert_get_the_post_categories( $args['post_id'], $args['taxonomi'] );
		if ( !empty( $meta_piece_categories ) ) {
			$meta_pieces[] = $meta_piece_categories;
		}
	}

	if ( $args['comment_count'] ) {
		$meta_piece_comments = ert_get_the_post_comments_link( $args['post_id'] );
		if ( !empty( $meta_piece_comments ) ) {
			$meta_pieces[] = $meta_piece_comments;
		}
	}

	if ( $args['rating_stars'] && ert_is_post_rating_enabled( $args['post_id'] ) ) {
		$meta_piece_rating_stars = ert_get_post_rating_stars( $args['post_id'] );
		if ( !empty( $meta_piece_rating_stars ) ) {
			$meta_pieces[] = $meta_piece_rating_stars;
		}
	}

	$output = implode( ' | ', $meta_pieces );

	return $output;
}

function ert_get_post_author_link( $post_id = 0 ) {
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
	$post_author_id = get_post( $post_id )->post_author;
	$author = get_user_by( 'id', $post_author_id );
	$link = sprintf(
		'<a href="%1$s" class="url fn" title="%2$s" rel="author">%3$s</a>',
		esc_url( get_author_posts_url( $author->ID, $author->user_nicename ) ),
		esc_attr( sprintf( __( 'Posts by %s', 'ert_rcp' ), $author->display_name ) ),
		esc_html( $author->display_name )
	);
	return $link;
}

function ert_get_the_post_categories( $post_id = 0, $taxonomi, $before = null, $sep = ', ', $after = '' ) {
	return get_the_term_list( $post_id, $taxonomi, $before, $sep, $after );
}

function ert_get_the_post_date( $post = null, $date_format = '' ) {
	$date_format = !empty( $date_format ) ? $date_format : get_option( 'date_format' );
	return '<span class="updated">' . get_the_time( $date_format, $post ) . '</span>';
}

function ert_get_the_post_comments_link( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();

	return sprintf(
		'<a class="comments-link" href="%s">%d <span title="%s" class="comment-bubble post-meta-icon"></span></a>',
		esc_attr( get_the_permalink() . '#comments' ),
		esc_html( get_comments_number( $post_id ) ),
		esc_attr( __( 'comment count', 'ert_rcp' ) )
	);
}

function ert_get_post_rating_stars( $post_id = 0 ) {
	$rating = extra_get_post_rating( $post_id );
	$output = '<span class="rating-stars" title="' . esc_attr( sprintf( __( 'Rating: %0.2f', 'ert_rcp' ), $rating ) ) .'">' . ert_make_mini_stars( $rating ) . '</span>';
	return $output;
}

function ert_is_post_rating_enabled( $post_id = 0 ) {
	if ( false === ert_get_post_meta_setting( 'all', 'rating_stars' ) ) {
		return false;
	}

	if ( is_single() && false === ert_get_post_meta_setting( 'post', 'rating_stars' ) ) {
		return false;
	}

	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

	$hide_rating = get_post_meta( $post_id, '_post_rating_hide', true );

	$has_post_rating = $hide_rating ? false : true;

	return apply_filters( 'ert_is_post_rating_enabled', $has_post_rating, $post_id );
}

function ert_get_post_category_color( $post_id = 0 ) {
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

	$categories = wp_get_post_categories( $post_id );

	$color = '';
	if ( !empty( $categories ) ) {
		$first_category_id = $categories[0];
		if ( function_exists( 'ert_get_childmost_taxonomy_meta' ) ) {
			$color = ert_get_childmost_taxonomy_meta( $first_category_id, 'color', true, ert_global_accent_color() );
		} else {
			$color = ert_global_accent_color();
		}

	}
	return $color;
}

function ert_get_post_format_thumb( $post_format, $size =  'icon' ) {
	$template_dir = get_template_directory_uri();

	$size = 'icon' == $size ? 'icon' : 'thumb';

	if ( in_array( $post_format, array( 'video', 'quote', 'link', 'audio', 'map', 'text' ) ) ) {
		$img = 'post-format-' . $size . '-' . $post_format . '.svg';
	} else {
		$img = 'post-format-' . $size . '-text.svg';
	}

	return $template_dir . '/images/' . $img;
}

function ert_get_gallery_post_format_thumb() {
	$attachment_ids = get_post_meta( get_the_ID(), '_gallery_format_attachment_ids', true );
	$attachment_ids = explode( ',', $attachment_ids );
	if ( count( $attachment_ids ) ) {
		foreach ( $attachment_ids as $attachment_id ) {
			list($thumb_src, $thumb_width, $thumb_height) = wp_get_attachment_image_src( $attachment_id, 'full' );
			return $thumb_src;
		}
	} else {
		return ert_get_post_format_thumb( 'gallery', 'thumb' );
	}
}

function ert_get_post_format( $post = null ) {
	if ( ! $post = get_post( $post ) ) {
		return false;
	}

	if ( ! post_type_supports( $post->post_type, 'ert-post-formats' ) ) {
		return false;
	}

	$_format = get_the_terms( $post->ID, ERT_POST_FORMAT );

	if ( empty( $_format ) ) {
		return false;
	}

	$format = array_shift( $_format );

	$post_format_string = str_replace( ERT_POST_FORMAT_PREFIX, '', $format->slug );

	$post_format = in_array( $post_format_string, array_keys( ert_get_post_format_strings() ) ) ? $post_format_string : false;

	return apply_filters( 'ert_get_post_format', $post_format, $post->ID );
}


if ( ! function_exists( 'ert_get_option' ) ) {

	function ert_get_option( $option_name, $default_value = '', $used_for_object = '', $force_default_value = false ){
		global $ert_plugin_options, $shortname;

		if ( ert_options_stored_in_one_row() ) {
			$ert_plugin_options_name = 'ert_' . $shortname;

			if ( ! isset( $ert_plugin_options ) || isset( $_POST['wp_customize'] ) ) {
				$ert_plugin_options = get_option( $ert_plugin_options_name );
			}
			$option_value = isset( $ert_plugin_options[$option_name] ) ? $ert_plugin_options[$option_name] : false;
		} else {
			$option_value = get_option( $option_name );
		}

		// option value might be equal to false, so check if the option is not set in the database
		if ( ! isset( $ert_theme_options[ $option_name ] ) && ( '' != $default_value || $force_default_value ) ) {
			$option_value = $default_value;
		}

		if ( '' != $used_for_object && in_array( $used_for_object, array( 'page', 'category' ) ) && is_array( $option_value ) )
			$option_value = ert_generate_wpml_ids( $option_value, $used_for_object );

		return $option_value;
	}

}



if ( ! function_exists( 'ert_options_stored_in_one_row' ) ) {

	function ert_options_stored_in_one_row(){
		global $ert_store_options_in_one_row;

		return isset( $ert_store_options_in_one_row ) ? (bool) $ert_store_options_in_one_row : false;
	}

}

function ert_get_post_thumb( $args = array() ) {
	$default_args = array(
		'post_id'                    => 0,
		'size'                       => '',
		'height'                     => 50,
		'width'                      => 50,
		'title'                      => '',
		'link_wrapped'               => true,
		'permalink'                  => '',
		'a_class'                    => array(),
		'img_class'                  => array(),
		'img_style'                  => '',
		'img_after'                  => '', // Note: this value is not escaped/sanitized, and should be used for internal purposes only, not any user input
		'post_format_thumb_fallback' => false,
		'fallback'                   => '',
		'thumb_src'                  => '',
		'return'                     => 'img',
	);

	$args = wp_parse_args( $args, $default_args );

	$post_id = $args['post_id'] ? $args['post_id'] : get_the_ID();
	$permalink = !empty( $args['permalink'] ) ? $args['permalink'] : get_the_permalink( $post_id );
	$title = !empty( $args['title'] ) ? $args['title'] : get_the_title( $post_id );

	$width = '50'; //(int) apply_filters( 'ert_post_thumbnail_width', $args['width'] );
	$height = '50'; //(int) apply_filters( 'ert_post_thumbnail_height', $args['height'] );
	$size = !empty( $args['size'] ) ? $args['size'] : 'ert_RPPT_thumb';
	$thumb_src = $args['thumb_src'];
	$img_style = $args['img_style'];

	$thumbnail_id = get_post_thumbnail_id( $post_id );
	/*
	if ( !$thumbnail_id && !$args['thumb_src'] ) {
		if ( $args['post_format_thumb_fallback'] ) {
			$post_format = ert_get_post_format();
			if ( in_array( $post_format, array( 'video', 'quote', 'link', 'audio', 'map', 'text' ) ) ) {
				$thumb_src = ert_get_post_format_thumb( $post_format, 'thumb' );
			} else {
				$thumb_src = ert_get_post_format_thumb( 'text', 'thumb' );
			}
		} else if ( !empty( $args['fallback'] ) ) {
			return $args['fallback'];
		} else {
			$thumb_src = ert_get_post_format_thumb( 'text', 'icon' );
		}
	}
	*/
	if ( $thumbnail_id ) {
		list($thumb_src, $thumb_width, $thumb_height) = wp_get_attachment_image_src( $thumbnail_id, $size );
	}

	if ( 'thumb_src' === $args['return'] ) {
		return $thumb_src;
	}

	$image_output = sprintf(
		'<img src="%1$s" alt="%2$s"%3$s %4$s/>%5$s',
		esc_attr( $thumb_src ),
		esc_attr( $title ),
		( !empty( $args['img_class'] ) ? sprintf( ' class="%s"', esc_attr( implode( ' ', $args['img_class'] ) ) ) : '' ),
		( !empty( $img_style ) ? sprintf( ' style="%s"', esc_attr( $img_style ) ) : '' ),
		$args['img_after']
	);

	if ( $args['link_wrapped'] ) {
		$image_output = sprintf(
			'<a href="%1$s" title="%2$s"%3$s%5$s>
				%4$s
			</a>',
			esc_attr( $permalink ),
			esc_attr( $title ),
			( !empty( $args['a_class'] ) ? sprintf( ' class="%s"', esc_attr( implode( ' ', $args['a_class'] ) ) ) : '' ),
			$image_output,
			( !empty( $img_style ) ? sprintf( ' style="%s"', esc_attr( $img_style ) ) : '' )
		);
	}

	return $image_output;
}
?>
