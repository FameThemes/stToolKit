<?php 

class STLoginWidget extends WP_Widget {

	public function __construct() {
		// widget actual processes
        parent::__construct(
	 		'stloginwidget', // Base ID
			__('ST Login','smooththemes'), // Name
			array( 'description' => __( 'Display Login form', 'smooththemes' )
            ),
            array( 'width' => 400, 'height' => 0, 'id_base' => 'stloginwidget' )
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

		?>
        <?php /*
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','smooththemes'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
        */ ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'login_redirect' ); ?>"><?php echo __('Logged in success redirect to URL: ' ,'smooththemes') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'login_redirect' ); ?>" name="<?php echo $this->get_field_name( 'login_redirect' ); ?>" type="text" value="<?php echo esc_attr( $instance['login_redirect'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'logout_redirect' ); ?>"><?php echo __('Logout redirect to URL: ' ,'smooththemes') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'logout_redirect' ); ?>" name="<?php echo $this->get_field_name( 'logout_redirect' ); ?>" type="text" value="<?php echo esc_attr( $instance['logout_redirect'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'register_link' ); ?>"><?php echo __('Register URL: ' ,'smooththemes') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'register_link' ); ?>" name="<?php echo $this->get_field_name( 'register_link' ); ?>" type="text" value="<?php echo esc_attr( $instance['register_link'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'profile_link' ); ?>"><?php echo __('Profile URL: ' ,'smooththemes') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'profile_link' ); ?>" name="<?php echo $this->get_field_name( 'profile_link' ); ?>" type="text" value="<?php echo esc_attr( $instance['profile_link'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'lost_pass_link' ); ?>"><?php echo __('Lost password URL: ' ,'smooththemes') ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'lost_pass_link' ); ?>" name="<?php echo $this->get_field_name( 'lost_pass_link' ); ?>" type="text" value="<?php echo esc_attr( $instance['lost_pass_link'] ); ?>" />
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

            extract( $args );
    		$title = apply_filters( 'widget_title', $instance['title'] );
             $class= (is_user_logged_in()) ? 'logged-in' : 'not-login-yet';

            echo '<div class="widget widget-st-login '.$class.'">';
            echo do_shortcode('[st_login login_redirect="" logout_redirect="" register_link="" profile_link="" lost_pass_link="" disable_link=""]');
        	echo '</div>';
	}

}

function register_STLoginWidget() {
    if(current_theme_supports('st-widgets','login')){
        register_widget( 'STLoginWidget' );
    }
}
add_action( 'widgets_init', 'register_STLoginWidget' );