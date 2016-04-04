<?php
/**
 * Class Post Scroll Widget
 */
class gcz_post_scroll_widget extends WP_Widget {
	public function __construct(){
		parent::__construct(
			'gcz_post_scroll_widget_id',
			__('Post Scroll Widget', 'gcz'),
			array(
				'description' => __('This is a Post Scroll Widget.', 'gcz')
			)
		);

	}

	function form( $instance ) {
		$gcz_defaults['title'] = '';
		$gcz_defaults['vertical_horizontal'] = 'vertical';
		$gcz_defaults['post_scroll_height'] = '340';
		$gcz_defaults['gcz_post_number'] = 5;
		$gcz_defaults['gcz_post_type'] = 'latest';
		$gcz_defaults['gcz_post_category'] = '';
		$instance = wp_parse_args( (array) $instance, $gcz_defaults );
		$title = esc_attr( $instance[ 'title' ] );
		$vertical_horizontal = esc_attr( $instance[ 'vertical_horizontal' ] );
		$post_scroll_height = esc_attr( $instance[ 'post_scroll_height' ] );
		$gcz_post_number = $instance['gcz_post_number'];
		$gcz_post_type = $instance['gcz_post_type'];
		$gcz_post_category = $instance['gcz_post_category'];
    ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:', 'gcz' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('vertical_horizontal')?>"><?php _e( 'Style', 'gcz' ) ?></label>
			<select class="widefat" name="<?php echo $this->get_field_name('vertical_horizontal');?>" id="<?php echo $this->get_field_id('vertical_horizontal'); ?>">
				<option <?php if(isset($vertical_horizontal)){ selected( $vertical_horizontal, 'vertical' ); }?> value="vertical">Vertical</option>
				<option <?php if(isset($vertical_horizontal)){ selected( $vertical_horizontal, 'horizontal' ); }?> value="horizontal">Horizontal</option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_scroll_height')?>"><?php _e( 'Height', 'gcz' ) ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('post_scroll_height'); ?>" name="<?php echo $this->get_field_name('post_scroll_height')?>" value="<?php echo $post_scroll_height; ?>">
		</p>		
        <p>
			<label for="<?php echo $this->get_field_id('gcz_post_number'); ?>"><?php _e( 'Number of posts to display:', 'gcz' ); ?></label>
			<input id="<?php echo $this->get_field_id('gcz_post_number'); ?>" name="<?php echo $this->get_field_name('gcz_post_number'); ?>" type="text" value="<?php echo $gcz_post_number; ?>" size="3" />
        </p>
		<p>
			<input type="radio" <?php checked($gcz_post_type, 'latest') ?> id="<?php echo $this->get_field_id( 'gcz_post_type' ); ?>" name="<?php echo $this->get_field_name( 'gcz_post_type' ); ?>" value="latest"/><?php _e( 'Show latest Posts', 'gcz' );?><br />
			<input type="radio" <?php checked($gcz_post_type,'category') ?> id="<?php echo $this->get_field_id( 'gcz_post_type' ); ?>" name="<?php echo $this->get_field_name( 'gcz_post_type' ); ?>" value="category"/><?php _e( 'Show posts from a category', 'gcz' );?><br />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'gcz_post_category' ); ?>"><?php _e( 'Select category', 'gcz' ); ?>:</label>
			<?php wp_dropdown_categories( array( 'show_option_none' =>' ','name' => $this->get_field_name( 'gcz_post_category' ), 'selected' => $gcz_post_category ) ); ?>
		</p>
      <?php
   }

   function update( $new_instance, $old_instance ) {
      $instance = $old_instance;
      $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );

      $instance[ 'gcz_post_number' ] = absint( $new_instance[ 'gcz_post_number' ] );
      $instance[ 'gcz_post_type' ] = $new_instance[ 'gcz_post_type' ];
      $instance[ 'gcz_post_category' ] = $new_instance[ 'gcz_post_category' ];
	  
      $instance['vertical_horizontal'] = $new_instance['vertical_horizontal'];
      $instance['post_scroll_height'] = $new_instance['post_scroll_height'];

      return $instance;
   }

   function widget( $args, $instance ) {
		extract( $args );
		extract( $instance );

		global $post;
		$title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : '';
		$gcz_post_number = empty( $instance[ 'gcz_post_number' ] ) ? 4 : $instance[ 'gcz_post_number' ];
		$gcz_post_type = isset( $instance[ 'gcz_post_type' ] ) ? $instance[ 'gcz_post_type' ] : 'latest' ;
		$gcz_post_category = isset( $instance[ 'gcz_post_category' ] ) ? $instance[ 'gcz_post_category' ] : '';

		$vertical_horizontal = isset($instance['vertical_horizontal']) ? $instance['vertical_horizontal'] : 'vertical';
		$post_scroll_height = isset($instance['post_scroll_height']) ? $instance['post_scroll_height'] : '340';
	  

		if( $gcz_post_type == 'latest' ) {
			$get_featured_posts = new WP_Query( array(
				'posts_per_page'        => $gcz_post_number,
				'post_type'             => 'post',
				'orderby '				=> 'date',
				'ignore_sticky_posts'   => true
			) );
		}
		else {
			$get_featured_posts = new WP_Query( array(
				'posts_per_page'        => $gcz_post_number,
				'post_type'             => 'post',
				'orderby '				=> 'date',
				'category__in'          => $category
			) );
		}
		echo $before_widget;
	  	echo $args['before_title'].$title.$args['after_title'];
		?>
		
		<style>
			.gcz-post-item {
				height: <?php echo esc_attr($post_scroll_height);?>px !important;
			}
		</style>
		<?php 
			if($vertical_horizontal == 'vertical'){
		?>
		<div class="vertical-post">
			<ul id="vertical-scroll" class="gcz-post-item">
				<?php
					while( $get_featured_posts->have_posts() ):$get_featured_posts->the_post();
				 ?> 
					<li>
						<a target="_blank" href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>">
							<span class="post-date"><?php echo get_the_date('d M') ?></span>
							<?php 
								the_title();
							?>
						</a>
					</li>
				<?php
					endwhile;
				?>
			</ul>
        </div>
		<?php } ?>
		<?php if($vertical_horizontal == 'horizontal') {?>
		<div class="gcz-news-scroll">
			<div>
				<span class="scroll-label">Latest News</span>
				<ul id="horizontal-scroll">
					<?php
						while( $get_featured_posts->have_posts() ):$get_featured_posts->the_post();
					?> 
						<li>
							<span class="post-date"><?php echo get_the_date('d M') ?></span> 
							<a target="_blank" href="<?php the_permalink(); ?>" title="<?php the_title_attribute();?>">
								<?php the_title();?>
							</a>
						</li>

					<?php
						endwhile;
					?>
				</ul> 
			</div>
		</div>
		<?php } ?>

        <?php
            // Reset Post Data
            wp_reset_query();
        ?>
		<!-- </div> -->
      <?php echo $after_widget;
    }
}