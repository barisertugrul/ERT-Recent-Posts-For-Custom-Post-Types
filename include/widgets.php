<?php
/*************************************
<<<<<<< HEAD
	Özel Yazý Türü Son Eklenenler
*************************************/

/*
 * Özel Yazý Türü Son Eklenenler Bileþeni
=======
	Ã–zel YazÄ± TÃ¼rÃ¼ Son Eklenenler
*************************************/

/*
 * Ã–zel YazÄ± TÃ¼rÃ¼ Son Eklenenler BileÅŸeni
>>>>>>> origin/master
 */
class ert_RecentCustomPosts extends WP_Widget {

	public function __construct() {
		$widget_ops = array(
			'description' => __( 'Your site&#8217;s most recent posts for custom post types.', 'ert_rcp' ),
		);
		parent::__construct( 'ert-custom-recent-posts', __( 'ERT - Recent Posts For Custom Post Types', 'ert_rcp' ), $widget_ops );
	}

	public function widget( $args, $instance ) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'custom_widget_recent_posts', 'ert_rcp' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();

		$sayi = ! empty( $instance['sayi'] ) ? absint( $instance['sayi'] ) : 5;
		if ( ! $sayi ) {
			$sayi = 5;
		}
		$show_date = isset( $instance['show_date'] ) ? $instance['show_date'] : false;
		$show_thumb = isset( $instance['show_thumb'] ) ? $instance['show_thumb'] : false;
		$show_categories = isset( $instance['show_categories'] ) ? $instance['show_categories'] : false;
		$posttype = ! empty( $instance['posttype'] ) ? $instance['posttype'] : 'post';
		$taxonomi = ! empty( $instance['taxonomi'] ) ? $instance['taxonomi'] : 'category';
		
		$typename = "Post";
		$types = get_post_types( array( 'exclude_from_search' => false ), 'objects' );
foreach ( $types as $type ) {
		          if($posttype == $type->name) $typename = $type->labels->name;
}
   
		$category = ! empty( $instance['category'] ) ? $instance['category'] : '';
		$title = ! empty( $instance['title'] ) ? $instance['title'] : sprintf(__('Recent %s','ert_rcp'), $typename);

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		 $cat_name = ($taxonomi == 'category')? 'category_name': $taxonomi;
<<<<<<< HEAD
		 /*
		 if(($taxonomi == 'category')){
			 $cat_args = array(
			'posts_per_page'      => $sayi,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'post_type' => 'post',
		)  ;
			if($category != "") $cat_args['category_name'] = $category;
			 $r = new WP_Query( $cat_args );
			 /*
			$cat_array = explode(',',$category);
			if(!empty($cat_array)){
		 $cat_args = array(
	'post_type' => $posttype,
	'posts_per_page'		=> $sayi,
			'no_found_rows'			=> false,
			'post_status'			=> 'publish',
	'tax_query' => array(
		array(
			'taxonomy' => $taxonomi,
			'field'    => 'slug',
			'terms'    => $cat_array,
		),
	),
);
$r = new WP_Query( apply_filters( 'widget_custom_posts_args', $cat_args));
			}else{
$r = new WP_Query( apply_filters( 'widget_custom_posts_args', array(
			'posts_per_page'		=> $sayi,
			'no_found_rows'			=> false,
			'post_status'			=> 'publish',
			'ignore_sticky_posts'	=> true,
			'post_type' 			=> 'post',
		) ) );		
			}
		 }else{

		 */
=======
>>>>>>> origin/master
		$r = new WP_Query( array(
			'posts_per_page'		=> $sayi,
			'no_found_rows'			=> false,
			'post_status'			=> 'publish',
			'ignore_sticky_posts'	=> true,
			"$cat_name"		=> "$category",
			'post_type' 			=> $posttype,
		) ) ;
<<<<<<< HEAD

		 //}
=======
>>>>>>> origin/master
		if ($r->have_posts()) :
?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) echo $args['before_title'] . $title . $args['after_title']; ?>
		<ul class="widget_list">
		<?php while ( $r->have_posts() ) : $r->the_post(); ?>
			<li>
				<?php
				
<<<<<<< HEAD
				//Resimli versiyon için
=======
				//Resimli versiyon iÃ§in
>>>>>>> origin/master
				//$color = ert_get_post_category_color();
				//$post_format = ert_get_post_format();

				if ( $show_thumb ) {
<<<<<<< HEAD
					/*
					if ( in_array( $post_format, array( $posttype, 'video', 'quote', 'link', 'audio', 'map', 'text' ) ) ) {
						$thumb_src = ert_get_post_format_thumb( $post_format );
						$img_style = sprintf( 'background-color:%s', 'red' );
					} else if ( 'gallery' == $post_format ) {
						$thumb_src = ert_get_gallery_post_format_thumb();
					} else if ( !get_post_thumbnail_id() ) {
						$thumb_src = ert_get_post_format_thumb( 'text', 'icon' );
						$img_style = sprintf( 'background-color:%s', $color );
					}
					*/
=======
>>>>>>> origin/master
					$thumb_args = array(
						'a_class'   => array('widget_list_thumbnail'),
						'size'      => 'ert_RPPT_thumb',
						'thumb_src' => !empty( $thumb_src ) ? $thumb_src : '',
						'img_style' => !empty( $img_style ) ? $img_style : '',
					);

					echo ert_get_post_thumb( $thumb_args ); 
<<<<<<< HEAD

=======
>>>>>>> origin/master
				}
				
				?>
				<div class="post_info">
					<a href="<?php the_permalink(); ?>" class="title"><?php get_the_title() ? the_title() : the_ID(); ?></a></br>
					<?php
					
<<<<<<< HEAD
					//Meta bilgilerinin gösterimi için
=======
					//Meta bilgilerinin gÃ¶sterimi iÃ§in
>>>>>>> origin/master
					$meta_args = array(
						'post_date'     => $show_date,
						'categories'    => $show_categories,
						'author_link'   => false,
						'comment_count' => false,
						'rating_stars'  => false,
						'taxonomi'		=> $taxonomi,
					);
					?>
					<div class="post-meta">
						<?php echo ert_display_post_meta( $meta_args ); ?>
					</div>
				</div>
			</li>
		<?php endwhile; ?>
		<?php wp_reset_postdata(); ?>
		</ul>
		<?php echo $args['after_widget']; ?>
<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_postdata();

		endif;

		ob_end_flush();

	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['sayi'] = (int) $new_instance['sayi'];
		$instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
		$instance['show_thumb'] = isset( $new_instance['show_thumb'] ) ? (bool) $new_instance['show_thumb'] : false;
		$instance['show_categories'] = isset( $new_instance['show_categories'] ) ? (bool) $new_instance['show_categories'] : false;
		$instance['posttype'] = strip_tags( $new_instance['posttype'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['taxonomi'] = strip_tags( $new_instance['taxonomi'] );
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['et_widget_custom_recent_posts_entries'] ) ) {
			delete_option( 'et_widget_custom_recent_posts_entries' );
		}

		return $instance;
		
	}
	
	function flush_widget_cache() {
		wp_cache_delete( 'custom_widget_recent_posts', 'ert_rcp' );
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$category = isset( $instance['category'] ) ? esc_attr( $instance['category'] ) : '';
		$taxonomi = isset( $instance['taxonomi'] ) ? esc_attr( $instance['taxonomi'] ) : '';
		$posttype = isset( $instance['posttype'] ) ? esc_attr( $instance['posttype'] ) : '';
		$sayi = isset( $instance['sayi'] ) ? absint( $instance['sayi'] ) : 5;
		$show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
		$show_thumb = isset( $instance['show_thumb'] ) ? (bool) $instance['show_thumb'] : true;
		$show_categories = isset( $instance['show_categories'] ) ? (bool) $instance['show_categories'] : true;
?>
		<p><label for="<?php echo $this->get_field_id( 'sayi' ); ?>"><?php esc_html_e( 'Number of posts to show:', 'ert_rcp' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'sayi' ); ?>" name="<?php echo $this->get_field_name( 'sayi' ); ?>" type="text" value="<?php echo $sayi; ?>" size="3" /></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_date' ); ?>"><?php esc_html_e( 'Display post date?', 'ert_rcp' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_thumb ); ?> id="<?php echo $this->get_field_id( 'show_thumb' ); ?>" name="<?php echo $this->get_field_name( 'show_thumb' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_thumb' ); ?>"><?php esc_html_e( 'Display post thumbnail?', 'ert_rcp' ); ?></label></p>

		<p><input class="checkbox" type="checkbox" <?php checked( $show_categories ); ?> id="<?php echo $this->get_field_id( 'show_categories' ); ?>" name="<?php echo $this->get_field_name( 'show_categories' ); ?>" />
		<label for="<?php echo $this->get_field_id( 'show_categories' ); ?>"><?php esc_html_e( 'Display post categories?', 'ert_rcp' ); ?></label></p>

		<p>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'ert_rcp' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		</p>
		<p>
		<?php
             $types = get_post_types( array( 'exclude_from_search' => false ), 'object' );

             $select = "";
			 
foreach ( $types as $type ) {
             $selected = ($posttype == $type->name)? " selected":"";
    $select .=  '<option value="' . $type->name . '"'.$selected.'>' . $type->labels->name . '</option>';
}
?>

<label for="<?php echo $this->get_field_id( 'posttype' ) ;?>"><?php _e( 'Post Type:', 'ert_rcp' ); ?></label>
<select  id="<?php echo $this->get_field_id( 'posttype' ) ;?>"
                   class="widefat" name="<?php echo $this->get_field_name( 'posttype' ) ;?>"><?php echo $select; ?></select>
		</p>
		<p>

		<?php
            $tax_args = array(
  'public'   => true,
  '_builtin' => false
  
); 
$output = 'object'; // or objects
$operator = 'or'; // 'and' or 'or'
$taxonomies = get_taxonomies( $tax_args, $output, $operator );

             $select = "";
			 
foreach ( $taxonomies as $tax ) {
	if($tax->meta_box_cb == 'post_categories_meta_box'){
             $selected = ($taxonomi == $tax->name)? " selected":"";
    $select .=  '<option value="' . $tax->name . '"'.$selected.'>' . $tax->labels->name . '</option>';
	}
}
?>

<label for="<?php echo $this->get_field_id( 'taxonomi' ) ;?>"><?php _e( 'Taxonomy Filter:', 'ert_rcp' ); ?></label>
<select  id="<?php echo $this->get_field_id( 'taxonomi' ) ;?>"
                   class="widefat" name="<?php echo $this->get_field_name( 'taxonomi' ) ;?>"><?php echo $select; ?></select>
		</p>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php esc_html_e( 'Category Slug:','ert_rcp' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'category' ); ?>" name="<?php echo $this->get_field_name( 'category' ); ?>"
        type="text" value="<?php echo esc_attr( $category ); ?>" />
        <small><?php _e( 'For multiple category use separate by commas', 'ert_rcp' ); ?></small>
		</p>
		<?php 
	}

}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> origin/master
