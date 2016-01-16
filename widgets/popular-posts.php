<?php 

class STPopularPosts extends WP_Widget {

	public function __construct() {
		// widget actual processes
        parent::__construct(
	 		'stpopularposts', // Base ID
			'ST Popular Posts', // Name
			array( 'description' => __( 'Display Popular Posts', 'magazon' ), ) // Args
		);
	}

 	public function form( $instance ) {
		// outputs the options form on admin
        
        if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '';
		}
        
        $number = intval($instance[ 'number' ]);
        
        if($number<=0){
            $number = 3; // default  = 3;
        }
        
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','magazon' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        
        	<p>
    		<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php echo __('How many post to show ? ' ,'magazon') ?></label> 
    		<input class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
    		</p>
        
		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
        $instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
        $instance[ 'number' ] = intval($new_instance[ 'number' ]);
		return $instance;
	}

	public function widget( $args, $instance ) {
		// outputs the content of the widget
            global $wpdb,$post;
            
            extract( $args );
    		$title = apply_filters( 'widget_title', $instance['title'] );
            $number = intval($instance['number'] );
            if($number<=0){
                $number = 3; // default  = 3;
            }
            
    		echo $before_widget;
    		if ( ! empty( $title ) )
    			echo $before_title . $title . $after_title;
               
               /* 
        	$now = gmdate("Y-m-d H:i:s",time());
        	$lastmonth = gmdate("Y-m-d H:i:s",gmmktime(date("H"), date("i"), date("s"), date("m")-12,date("d"),date("Y")));
        	$popularposts = "
            SELECT $wpdb->posts.*, COUNT($wpdb->comments.comment_post_ID) AS 'stammy'
            FROM $wpdb->posts, $wpdb->comments 
            WHERE 
            comment_approved = '1' 
                    AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID 
                    AND post_status = 'publish' AND post_date < '$now' AND post_date > '$lastmonth' 
                    AND comment_status = 'open' 
            GROUP BY $wpdb->comments.comment_post_ID 
            ORDER BY stammy DESC LIMIT ".$number;
            
        	$posts = $wpdb->get_results($popularposts);
            
        	$posts = ($posts) ? $posts : get_posts('numberposts='.$number.'&orderby=comment_count');
            */
            
        //	$posts =  get_posts('numberposts='.$number.'&orderby=comment_count');
            
            /**
             * New in ver 1.3
             */ 
            $args = array( 'posts_per_page' => $number );
            $args['post__not_in'] = array($post->ID);
            $args['orderby'] = 'comment_count';
            $args['order'] = 'DESC';
            $args['post_status'] = 'publish';
           
            if(st_is_wpml()){
                 $args['sippress_filters'] = true;
                 $args['language'] = get_bloginfo('language');
            }
            $new_query = new WP_Query($args);
            $posts =  $new_query->posts;
            
            
        	if($posts){ ?>
            <ul class="po_re_container">
                <?php foreach($posts as $post){ setup_postdata($post); ?>
                        
                        <li class="widget-post-wrapper">
                             <div class="widget-post-thumb">
                                 <?php
                                 $thumb = st_theme_post_snall_thumbnail($post->ID, false);
                                 if($thumb!=''){
                                     echo $thumb;
                                 }else{
                                     echo '<span class="no-thumb"></span>';
                                 }
                                 ?>
                              </div>

                        	<div class="widget-post-content">
                                <h3 class="widget_posttitle">
                        		    <span class="widget-post-title"><a <?php echo $title; ?> href="<?php echo get_permalink($post->ID); ?>" title="<?php echo the_title_attribute(); ?>"><?php the_title(); ?></a></span>
                                </h3>
                        		<div class="widget-post-meta"><?php echo get_the_date(); ?> - <span><?php comments_number(__('0 Comment','magazon'),__('1 Comment','magazon'),__('% Comments','magazon') )?></span></div>
                        	</div>
                        </li>
                <?php } ?>
             </ul>
            <?php }	wp_reset_query() ;
            
        	echo $after_widget;
	}

}


function register_STPopularPosts() {
    if(current_theme_supports('st-widgets','popular-posts')){
        register_widget( 'STPopularPosts' );
    }
}
add_action( 'widgets_init', 'register_STPopularPosts' );