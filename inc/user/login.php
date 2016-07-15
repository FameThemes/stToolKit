<?php
/**
 * @package ST User
 * 
 * Class ST Login
 * + Proccess login form
 * [st_login login_redirect="" logout_redirect="" register_link="" profile_link="" lost_pass_link=""]
 * + Proccess lostpass form
 * [st_lost_password]
 */
if (!class_exists('ST_Login')) {
    class ST_Login {
        /**
    	 * __construct function.
    	 *
    	 * @access public
    	 * @return void
    	 */
    	public function __construct() {
    		// Hook-in
    		add_action( 'wp_print_scripts', array( $this, 'st_login_enqueue' ),56 );
            
            // Add shortcode
            add_shortcode('st_login', array($this, 'st_login_func'));
            add_shortcode('st_lost_password', array($this, 'st_lost_password_func'));
            add_shortcode('st_login_modal', array($this, 'st_login_modal_func'));
    
    		// Ajax events
            // Login proccess
    		add_action( 'wp_ajax_st_login_process', array($this, 'st_login_ajax_handler') );
            add_action( 'wp_ajax_nopriv_st_login_process', array($this, 'st_login_ajax_handler') );
            // Lostpass proccess

            add_action( 'wp_ajax_st_lostpassword', array($this, 'st_lostpassword_handler') );
            add_action( 'wp_ajax_nopriv_st_lostpassword', array($this, 'st_lostpassword_handler') );
            
    	}
        
        /**
         * Get current URL
         */
        function st_current_url( $url = '' ) {
    		$pageURL  = force_ssl_admin() ? 'https://' : 'http://';
    		$pageURL .= esc_attr( $_SERVER['HTTP_HOST'] );
    		$pageURL .= esc_attr( $_SERVER['REQUEST_URI'] );
    
    		if ( $url != "nologout" ) {
    			if ( ! strpos( $pageURL, '_login=' ) ) {
    				$rand_string = md5( uniqid( rand(), true ) );
    				$rand_string = substr( $rand_string, 0, 10 );
    				$pageURL = add_query_arg( '_login', $rand_string, $pageURL );
    			}
    		}
    
    		return esc_url_raw( $pageURL );
    	}
        
        /**
         * Custom login form
         */
        function st_login_form( $args = array() ) {
        	$defaults = array(
        		'echo' => true,
        		'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // Default redirect is back to the current page
        		'form_id' => 'loginform',
        		'label_username' => __( 'Username', 'smooththemes' ),
                'placeholder_username'  => __('Enter Username', 'smooththemes'),
        		'label_password' => __( 'Password', 'smooththemes' ),
                'placeholder_password'  => __('Password', 'smooththemes'),
        		'label_remember' => __( 'Remember Me', 'smooththemes' ),
        		'label_log_in' => __( 'Log In', 'smooththemes' ),
        		'id_username' => 'st-login-u',
        		'id_password' => 'st-login-p',
        		'id_remember' => 'rememberme',
        		'id_submit' => 'st-login-submit',
                'id_redirect' => 'st-login-redirect',
        		'remember' => true,
        		'value_username' => '',
        		'value_remember' => false, // Set this to true to default the "Remember me" checkbox to checked
        	);
        	$args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
        
        	$form = '
        		<form class="form-horizontal" role="form" name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" class="loginform" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post">
        			' . apply_filters( 'login_form_top', '', $args ) . '
        			<div class="form-group login-username">
        				<!--<label class="col-sm-2 col-md-2 col-lg-2 control-label" for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>-->
                        <div class="">
        				    <input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="form-control input" value="' . esc_attr( $args['value_username'] ) . '" size="20" placeholder="'. esc_attr( $args['placeholder_username'] ) .'" />
                        </div>
        			</div>
        			<div class="form-group login-password">
        				<!--<label class="col-sm-2 col-md-2 col-lg-2 control-label" for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>-->
                        <div class="">
        				    <input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="form-control input" value="" size="20" placeholder="'. esc_attr( $args['placeholder_password'] ) .'" />
                        </div>
        			</div>
        			' . apply_filters( 'login_form_middle', '', $args ) . '
                    <div class="form-group">
                        <div class="">
                    ' . ( $args['remember'] ? '<div class="checkbox login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></div>' : '' ) . '
                        </div>
                    </div>
        			<div class="">
                        <div class="">
            				<input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="btn btn-color btn-block btn-lg" value="' . esc_attr( $args['label_log_in'] ) . '" />
            				<input id="' . esc_attr( $args['id_redirect'] ) . '" type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
                        </div>
        			</div>
        			' . apply_filters( 'login_form_bottom', '', $args ) . '
        		</form>';
        
        	if ( $args['echo'] )
        		echo $form;
        	else
        		return $form;
        }
        
        
        /**
         * Login ajax URL
         */
        function st_login_ajax_url() {
        	if ( is_ssl() ) {
        		return str_replace( 'http:', 'https:', admin_url( 'admin-ajax.php' ) );
        	} else {
        		return str_replace( 'https:', 'http:', admin_url( 'admin-ajax.php' ) );
        	}
        }
        
        /**
         * Login params
         */
        function st_login_enqueue() {
        	// Pass variables
        	$st_login_params = array(
        		'ajax_url'         => self::st_login_ajax_url(),
        		'force_ssl_login'  => force_ssl_login() ? 1 : 0,
        		'force_ssl_admin'  => force_ssl_admin() ? 1 : 0,
        		'is_ssl'           => is_ssl() ? 1 : 0,
        		'username_required' => __( 'Please enter your username', 'smooththemes' ),
        		'password_required' => __( 'Please enter your password', 'smooththemes' ),
        		'error_class'      => apply_filters( 'st_login_error_class', 'alert alert-warning' )
        	);
            
            wp_enqueue_script('st-user', ST_PAGEBUILDER_URL. 'frontend/js/st-user.js', array('jquery'), false, true);
        	wp_localize_script( 'st-user', 'st_login_params', $st_login_params );
        }
        
        /**
         * Shortcode login form
         */
        function st_login_func($atts, $content='') {
            $atts = shortcode_atts( array(
                'login_redirect'     => '',
                'logout_redirect'    => '',
                'register_link'      => '',
                'lost_pass_link'     => '',
                'profile_link'       => '',
                'disable_link'       => ''
             ), $atts );


            extract($atts);


            $html = '';
            $html .= '<div class="st-login">';
            $links = array();
            // Logged in user
    		if ( is_user_logged_in() ) {
                $user = get_user_by( 'id', get_current_user_id() );
                $logout_redirect = wp_logout_url( empty( $logout_redirect ) ? self::st_current_url( 'nologout' ) : $logout_redirect );

                if($profile_link==''){
                    $profile_link = admin_url( 'user-edit.php?user_id=' . $user->ID );
                }

                $links['profile']  = array(
                    'text'  => __('Profile', 'smooththemes'),
                    'href'  => $profile_link
                );

                $links['logout'] =array(
                                'text'  => __('Logout', 'smooththemes'),
                                'href'  => $logout_redirect
                            );


                $html .='<div class="logged-in">';
                $html .= '<h3 class="wellcome-use">'. __('Welcome', 'smooththemes') .' '. ucwords( $user->display_name ) .'</h3>';
                $html .= '<div class="avatar_container">' . get_avatar( $user->ID, apply_filters( 'st_login_avatar_size', 38 ) ) . '</div>';

                if ( ! empty( $links ) && is_array( $links ) && sizeof( $links > 0 ) && $disable_link != 'y' ) {
                    $html .= '<ul class="pagenav st-login-links">';

                    foreach ( $links as $id => $link ){
                        if($link['href']!=''){
                            $html .= '<li class="' . esc_attr( $id ) . '-link"><a href="' . esc_url( $link['href'] ) . '">' . wp_kses_post( $link['text'] ) . '</a></li>';
                        }

                    }

                    $html .= '</ul>';
                }
                $html.='</div>';


            }
            else {
                $redirect = empty( $login_redirect ) ? self::st_current_url( 'nologout' ) : $login_redirect;
                $login_form_args = apply_filters( 'st_login_form_args', array(
        	        'echo' 				=> false,
        	        'redirect' 			=> esc_url( apply_filters( 'st_login_redirect', $redirect ) ),
        	        'label_username' 	=> __( 'Username', 'smooththemes' ),
        	        'label_password' 	=> __( 'Password', 'smooththemes' ),
        	        'label_remember' 	=> __( 'Remember Me', 'smooththemes' ),
        	        'label_log_in' 		=> __( 'Login', 'smooththemes' ),
        	        'remember' 			=> true,
        	        'value_remember' 	=> true
        	    ) );
                
                $html .= self::st_login_form( $login_form_args );
                
                $links = array(
                    'lost-pass'     => array(
                        'text'  => __('Lost Password', 'smooththemes'),
                        'href'  => $lost_pass_link
                    ),
                    'reset-pass'    => array(
                        'text'  => __('Reset Password', 'smooththemes'),
                        'href'  => ''
                    )
                );
                
                if (get_option('users_can_register')) {
                    $register_link = (empty($register_link) ? apply_filters( 'st_login_register_url', site_url( 'wp-login.php?action=register', 'login' ) ) : $register_link);
                    $links['register'] = array(
                        'text'  => __('Register', 'smooththemes'),
                        'href'  => $register_link
                    );
                }



                if ( ! empty( $links ) && is_array( $links ) && sizeof( $links > 0 ) && $disable_link != 'y' ) {
                    $html .= '<ul class="pagenav st-login-links col-lg-offset-2 col-sm-offset-2 col-md-offset-2">';

                    foreach ( $links as $id => $link ){
                        if( $link['href'] !=''){
                            $html .= '<li class="' . esc_attr( $id ) . '-link"><a href="' . esc_url( $link['href'] ) . '">' . wp_kses_post( $link['text'] ) . '</a></li>';
                        }

                    }

                    $html .= '</ul>';
                }


            }
            

            
            $html .= '</div><!-- end .st-login -->';
            
            $html = str_replace(array("\n","\r"), ' ', $html);
            
            $html = apply_filters('st_login_func', $html, $atts);
            return $html;
        }
        
        
        /**
         * Form login modal
         */
        function st_login_modal_func($atts, $content='') {
            $atts = shortcode_atts( array(
                'logout_redirect'    => '',
                'login_success_redirect'    => '',
                'register_success_redirect' => ''
             ), $atts );
            extract($atts);
            $html = '';
            $logout_redirect = wp_logout_url( empty( $logout_redirect ) ? self::st_current_url( 'nologout' ) : $logout_redirect );
            $atts['logout_redirect'] = $logout_redirect;
            $html .= st_get_content_from_file(ST_PAGEBUILDER_PATH.'inc/user/login-modal.php', $atts);
            $html = apply_filters('st_login_modal_func', $html, $atts);
            return $html;
        }
        
        
        /**
         * Shortcode lostpass form
         */
        function st_lost_password_func($atts, $content='') {
            $atts = shortcode_atts( array(
                'redirect'    => ''
             ), $atts );
            extract($atts);
            $html = '';
            $http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
            if ( $http_post ) {
        		$wp_error = self::st_retrieve_password();
        		if ( !is_wp_error($wp_error) ) {
        			if ( $wp_error == true )
                        $messages = __('Check your e-mail for the confirmation link.', 'smooththemes');
                        $html .= '<div class="alert alert-success">' . apply_filters('login_messages', $messages) . "</div>\n";
        		}
                else {
                    if ( $wp_error->get_error_code() ) {
                		$errors = '';
                		foreach ( $wp_error->get_error_codes() as $code ) {
                			$severity = $wp_error->get_error_data($code);
                			foreach ( $wp_error->get_error_messages($code) as $error ) {
              					$errors .= '	' . $error . "<br />\n";
                			}
                		}
                		if ( !empty($errors) )
                			$html .= '<div class="alert alert-warning" id="login_error">' . apply_filters('login_errors', $errors) . "</div>\n";
                	}
                }
        	}


            $html .= '
                <form role="form" name="lostpasswordform" class="lostpasswordform" action="" method="post">
                	<div class="form-group">
                		<!-- <label for="user_login" >'. __('Username or E-mail:', 'smooththemes') .'</label> -->
                		<input type="text" name="user_login" id="user_login" class="form-control input" value="'. esc_attr($user_login) .'" size="20" placeholder="'. __('Enter Username or E-mail', 'smooththemes') .'" />
                	</div>
                    <input type="hidden" name="redirect_to" value="'. esc_attr( $redirect_to ) .'" />
                	<div class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="btn btn-color btn-block btn-lg" value="'. esc_attr('Get New Password', 'smooththemes') .'" /></div>
                </form>';
            return $html;
        }
        
        /**
         * Proccess login form with ajax
         */
        function st_login_ajax_handler() {
        	// Get post data
        	$creds                  = array();
        	$creds['user_login']    = stripslashes( trim( $_POST['user_login'] ) );
        	$creds['user_password'] = stripslashes( trim( $_POST['user_password'] ) );
        	$creds['remember']      = sanitize_text_field( $_POST['remember'] );
        	$redirect_to            = esc_url_raw( $_POST['redirect_to'] );
        	$secure_cookie          = null;
        
        	// If the user wants ssl but the session is not ssl, force a secure cookie.
        	if ( ! force_ssl_admin() ) {
        		$user_name = sanitize_user( $_POST['user_login'] );
        		if ( $user = get_user_by('login',  $user_name ) ) {
        			if ( get_user_option( 'use_ssl', $user->ID ) ) {
        				$secure_cookie = true;
        				force_ssl_admin( true );
        			}
        		}
        	}
        
        	if ( force_ssl_admin() )
        		$secure_cookie = true;
        
        	if ( is_null( $secure_cookie ) && force_ssl_login() )
        		$secure_cookie = false;
        
        	// Login
        	$user = wp_signon( $creds, $secure_cookie );
        
        	// Redirect filter
        	if ( $secure_cookie && strstr( $redirect_to, 'wp-admin' ) )
        		$redirect_to = str_replace( 'http:', 'https:', $redirect_to );
        
        	// Result
        	$result = array();
        
        	if ( ! is_wp_error($user) ) {
        		$result['success']  = 1;
        		$result['redirect'] = $redirect_to;
        	} else {
        		$result['success'] = 0;
        		if ( $user->errors ) {
        			foreach ( $user->errors as $error ) {
        				$result['error'] = $error[0];
        				break;
        			}
        		} else {
        			$result['error'] = __( 'Please enter your username and password to login.', 'sidebar_login' );
        		}
        	}
        
        	//echo '<!--SBL-->';
        	echo json_encode( $result );
        	//echo '<!--SBL_END-->';
        
        	die();
        }

        function st_lostpassword_handler(){
            $json = array(
                'status'=>false,
                'msg'=>''
            );

            $html = '';
            $http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
            if ( $http_post ) {
                $wp_error = self::st_retrieve_password();
                if ( !is_wp_error($wp_error) ) {
                    if ( $wp_error == true ){
                        $json['status'] = true;
                        $messages = __('Check your e-mail for the confirmation link.', 'smooththemes');
                        $html .= '<div class="alert alert-success">' . apply_filters('login_messages', $messages) . "</div>\n";
                    }
                }
                else {
                    if ( $wp_error->get_error_code() ) {
                        $errors = '';
                        foreach ( $wp_error->get_error_codes() as $code ) {
                            $severity = $wp_error->get_error_data($code);
                            foreach ( $wp_error->get_error_messages($code) as $error ) {
                                $errors .= '	' . $error . "<br />\n";
                            }
                        }
                        if ( !empty($errors) )
                            $html .= '<div class="alert alert-warning" id="login_error">' . apply_filters('login_errors', $errors) . "</div>\n";
                    }
                }
            }

            $json['msg'] = $html;


            echo json_encode($json);
            die();

        }
        
        
        /**
         * Proccess lostpass form
         */
        function st_retrieve_password() {
        	global $wpdb, $current_site;
        
        	$errors = new WP_Error();
            $login = trim($_POST['user_login']);

        	if ( empty( $login ) ) {
        		$errors->add('empty_username', __('<strong>ERROR</strong>: Enter a username or e-mail address.'));
        	} else if ( strpos($login , '@' ) ) {

                if(is_email($login)){
                    $user_data = get_user_by( 'email', $login);
                    if ( empty( $user_data ) ){
                        $errors->add('invalid_email', __('<strong>ERROR</strong>: There is no user registered with that email address.'));
                    }
                }else{
                    $user_data = get_user_by('login', $login);
                }

        	} else {

        		$user_data = get_user_by('login', $login);
        	}
        
        	do_action('lostpassword_post');
        
        	if ( $errors->get_error_code() )
        		return $errors;
        
        	if ( !$user_data ) {
        		$errors->add('invalidcombo', __('<strong>ERROR</strong>: Invalid username or e-mail or no user registered with that email address.'));
        		return $errors;
        	}
        
        	// redefining user_login ensures we return the right case in the email
        	$user_login = $user_data->user_login;
        	$user_email = $user_data->user_email;
        
        	do_action('retreive_password', $user_login);  // Misspelled and deprecated
        	do_action('retrieve_password', $user_login);
        
        	$allow = apply_filters('allow_password_reset', true, $user_data->ID);
        
        	if ( ! $allow )
        		return new WP_Error('no_password_reset', __('Password reset is not allowed for this user'));
        	else if ( is_wp_error($allow) )
        		return $allow;
        
        	$key = $wpdb->get_var($wpdb->prepare("SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $user_login));
        	if ( empty($key) ) {
        		// Generate something random for a key...
        		$key = wp_generate_password(20, false);
        		do_action('retrieve_password_key', $user_login, $key);
        		// Now insert the new md5 key into the db
        		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $user_login));
        	}
        	$message = __('Someone requested that the password be reset for the following account:') . "\r\n\r\n";
        	$message .= network_home_url( '/' ) . "\r\n\r\n";
        	$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        	$message .= __('If this was a mistake, just ignore this email and nothing will happen.') . "\r\n\r\n";
        	$message .= __('To reset your password, visit the following address:') . "\r\n\r\n";
        	$message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . ">\r\n";
        
        	if ( is_multisite() )
        		$blogname = $GLOBALS['current_site']->site_name;
        	else
        		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
        		// we want to reverse this for the plain text arena of emails.
        		$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        
        	$title = sprintf( __('[%s] Password Reset'), $blogname );
        
        	$title = apply_filters('retrieve_password_title', $title);
        	$message = apply_filters('retrieve_password_message', $message, $key);
        
        	if ( $message && !wp_mail($user_email, $title, $message) )
        		wp_die( __('The e-mail could not be sent.') . "<br />\n" . __('Possible reason: your host may have disabled the mail() function.') );
        	return true;
        }
    }
    new ST_Login();
}