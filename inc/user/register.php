<?php
/**
 * @package ST User
 * 
 * Class ST Register
 * + Proccess register form
 * [st_register login_link="" success_redirect=""]
 * + Proccess profile form
 * [st_profile login_link=""]
 */
if (!class_exists('ST_Register')) {
    class ST_Register {
        /**
    	 * __construct function.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function ST_Register() {
    		// Hook-in
    		add_action( 'wp_enqueue_scripts', array( $this, 'st_register_enqueue' ), 56 );
            
            // Add shortcode
            add_shortcode('st_register', array($this, 'st_register_func'));
            add_shortcode('st_profile', array($this, 'st_profile_func'));
    
    		// Ajax events
            // Register proccess
    		add_action( 'wp_ajax_st_register_process', array($this, 'st_register_ajax_handler') );
            add_action( 'wp_ajax_nopriv_st_register_process', array($this, 'st_register_ajax_handler') );
            // Profile proccess
    	}
        
        /**
         * Register ajax URL
         */
        function st_register_ajax_url() {
        	if ( is_ssl() ) {
        		return str_replace( 'http:', 'https:', admin_url( 'admin-ajax.php' ) );
        	} else {
        		return str_replace( 'https:', 'http:', admin_url( 'admin-ajax.php' ) );
        	}
        }
        
        /**
         * Register params
         */
        function st_register_enqueue() {
        	// Pass variables
        	$st_register_params = array(
        		'ajax_url'         => self::st_register_ajax_url(),
        		'username_required' => __( 'Please enter your username', 'smooththemes' ),
        		'email_required'    => __( 'Please enter your email', 'smooththemes' ),
        		'error_class'      => apply_filters( 'st_register_error_class', 'alert alert-warning' )
        	);
            wp_enqueue_script('st-user', ST_PAGEBUILDER_URL. 'frontend/js/st-user.js', array('jquery'), false, true);
        	wp_localize_script( 'st-user', 'st_register_params', $st_register_params );
        }
        
        /**
         * Shortcode form register
         */
        function st_register_func($atts, $content='') {
            $atts = shortcode_atts( array(
                'login_link'         => '',
                'success_redirect'   => '',
                'disable_link'       => ''
             ), $atts );
            extract($atts);
            $html = '';
            $login_link = empty( $login_link ) ? wp_login_url() : $login_link;
            $success_redirect = empty( $success_redirect ) ? $login_link : $success_redirect;


            $args = array(
        		'echo' => true,
        		'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // Default redirect is back to the current page
        		'form_id' => 'registerform',
        		'label_username' => __( 'Username', 'smooththemes' ),
                'label_email' => __( 'E-mail', 'smooththemes' ),
                'label_submit' => __( 'Register', 'smooththemes' ),
                'help_desc' => __('A password will be e-mailed to you.', 'smooththemes'),
                'placeholder_username'  => __('Enter Username', 'smooththemes'),
                'placeholder_email'  => __('Enter E-mail', 'smooththemes'),        		
        		'id_username' => 'st-register-u',
        		'id_email' => 'st-register-e',
                'id_redirect' => 'st-register-redirect',
        		'id_submit' => 'st-register-submit'
        	);


            if(!get_option('users_can_register')){
                $html.='<p class="alert alert-warning">'.__( '<strong>ERROR</strong>: User registration is currently not allowed.', 'smooththemes' ).'</p>';
            }else{
            $html .= '
                <div class="st-register">
                    <form class="form-horizontal" role="form" name="registerform" id="'. esc_attr($args['form_id']) .'" class="registerform" action="" method="post">
                    	<div class="form-group">
                    		<!--<label class="col-lg-2 control-label" for="'. esc_attr($args['id_username']) .'">'. esc_attr($args['label_username']) .'</label>-->
                            <div class="">
                    		  <input type="text" name="user_login" id="'. esc_attr($args['id_username']) .'" class="form-control input" value="'. esc_attr(wp_unslash($user_login)) .'" size="20" placeholder="'. esc_attr($args['placeholder_username']) .'" />
                            </div>
                    	</div>
                    	<div class="form-group">
                    		<!--<label class="col-lg-2 control-label" for="'. esc_attr($args['id_email']) .'">'. esc_attr($args['label_email']) .'</label> -->
                            <div class="">
                    		  <input type="text" name="user_email" id="'. esc_attr($args['id_email']) .'" class="form-control input" value="'. esc_attr(wp_unslash($user_email)) .'" size="25" placeholder="'. esc_attr($args['placeholder_email']) .'" />
                            </div>
                    	</div>
                        <div class="form-group">
                            <div class="">
                    	       <p id="reg-passmail">'. esc_attr($args['help_desc']) .'</p>
                            </div>
                        </div>
                    	<input id="'. esc_attr($args['id_redirect']) .'" type="hidden" name="redirect_to" value="'. esc_attr( $success_redirect ) .'" />
                    	<div class="">
                            <div class="">
                                <input type="submit" name="st-register-submit" id="'. esc_attr($args['id_submit']) .'" class="btn btn-color btn-block btn-lg" value="'. esc_attr($args['label_submit']) .'" />
                            </div>
                        </div>
                    </form>
                </div><!-- end .st-register -->
            ';

            }

            $links = array(
                'login'     => array(
                    'text'  => __('Login', 'smooththemes'),
                    'href'  => $login_link
                )
            );
            if ( ! empty( $links ) && is_array( $links ) && sizeof( $links > 0 ) && $disable_link != 'y') {
    			$html .= '<ul class="pagenav st-register-links col-lg-offset-2 col-sm-offset-2 col-md-offset-2">';
    			foreach ( $links as $id => $link )
    				$html .= '<li class="' . esc_attr( $id ) . '-link"><a href="' . esc_url( $link['href'] ) . '">' . wp_kses_post( $link['text'] ) . '</a></li>';
    			$html .= '</ul>';
    		}
            $html = str_replace(array("\r\n","\n","\r"), ' ', $html);
            $html = apply_filters('st_register_func', $html, $atts);
            return $html;
        }
        
        /**
         * Shortcode profile form
         */
        function st_profile_func($atts, $content='') {
            $atts = shortcode_atts( array(
                'login_link'         => '',
                'disable_link'       => ''
             ), $atts );
            extract($atts);
            $html = '';
            $login_link = empty( $login_link ) ? wp_login_url() : $login_link;
            if(!defined('ST_REGISTER')) define('ST_REGISTER', true);
            $links = array(
                'login-link'     => array(
                    'text'  => __('Login', 'smooththemes'),
                    'href'  => $login_link
                )
            );
            $html .= st_get_content_from_file(ST_PAGEBUILDER_PATH.'inc/user/profile.php', array('links'=>$links, 'disable_link'=>$disable_link));
            $html .= '';
            $html = apply_filters('st_profile_func', $html, $atts);
            return $html;
        }
        
        /**
         * Proccess register form with ajax
         */
        function st_register_ajax_handler() {
            $user_login = $_POST['user_login'];
            $user_email = $_POST['user_email'];
        	$errors = array();
        	$sanitized_user_login = sanitize_user( $user_login );
        	$user_email = apply_filters( 'user_registration_email', $user_email );
        	// Check the username
        	if ( $sanitized_user_login == '' ) {
        	    $errors['empty_username'] = __( '<strong>ERROR</strong>: Please enter a username.', 'smooththemes' );
        	} elseif ( ! validate_username( $user_login ) ) {
        	    $errors['invalid_username'] = __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.', 'smooththemes' );
        		$sanitized_user_login = '';
        	} elseif ( username_exists( $sanitized_user_login ) ) {
        	    $errors['username_exists'] = __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.', 'smooththemes' );
        	}
        	// Check the e-mail address
        	if ( $user_email == '' ) {
        	    $errors['empty_email'] = __( '<strong>ERROR</strong>: Please type your e-mail address.', 'smooththemes' );
        	} elseif ( ! is_email( $user_email ) ) {
        	    $errors['invalid_email'] = __( '<strong>ERROR</strong>: The email address is incorrect.', 'smooththemes' );
        		$user_email = '';
        	} elseif ( email_exists( $user_email ) ) {
        	    $errors['email_exists'] = __( '<strong>ERROR</strong>: This email is already registered, please choose another one.', 'smooththemes' );
        	}

            if(!get_option('users_can_register')){
                $errors = array();
                $errors['email_exists'] = __( '<strong>ERROR</strong>: User registration is currently not allowed.', 'smooththemes' );
            }


        	if ( is_array($errors) && count($errors) > 0 ) {
        	   echo json_encode($errors);
               exit();
        	}

        	$user_pass = wp_generate_password( 12, false);
        	$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
        	if ( ! $user_id ) {
        	    $errors['registerfail'] = sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you&hellip; please contact the <a href="mailto:%s">webmaster</a> !', 'smooththemes' ), get_option( 'admin_email' ) );
        		echo json_encode($errors);
                exit();
        	}

        	update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
        	wp_new_user_notification( $user_id, $user_pass );
        	echo json_encode($user_id);
            die();
        }  
    }
    new ST_Register();
}