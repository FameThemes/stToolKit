<?php
/**
 * @package ST User
 * 
 * Proccess user register
 */
if (!defined('ST_REGISTER')) exit();

if(!function_exists('get_user_to_edit')){
    include_once(ABSPATH.'/wp-admin/includes/user.php');
}
if ( ! defined( 'IS_PROFILE_PAGE' ) ) define( 'IS_PROFILE_PAGE', true );
$user_id = (int) get_current_user_id();
$current_user = wp_get_current_user();
$profileuser = get_userdata($user_id);
$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);
$wp_http_referer = remove_query_arg(array('update', 'delete_count'), $wp_http_referer );
if ( $http_post ) {
    check_admin_referer('update-user_' . $user_id);

    if ( !current_user_can('edit_user', $user_id) )
    	wp_die(__('You do not have permission to edit this user.', 'smooththemes'));
    
    if ( !is_multisite() ) { 
    	$errors = edit_user($user_id);
    } else {
    	$user = get_userdata( $user_id );
    
    	// Update the email address in signups, if present.
    	if ( $user->user_login && isset( $_POST[ 'email' ] ) && is_email( $_POST[ 'email' ] ) && $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM {$wpdb->signups} WHERE user_login = %s", $user->user_login ) ) )
    		$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->signups} SET user_email = %s WHERE user_login = %s", $_POST[ 'email' ], $user_login ) );

    	// WPMU must delete the user from the current blog if WP added him after editing.
    	$delete_role = false;
    	$blog_prefix = $wpdb->get_blog_prefix();
    	if ( $user_id != $current_user->ID ) {
    		$cap = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->usermeta} WHERE user_id = '{$user_id}' AND meta_key = '{$blog_prefix}capabilities' AND meta_value = 'a:0:{}'" );
    		if ( !is_network_admin() && null == $cap && $_POST[ 'role' ] == '' ) {
    			$_POST[ 'role' ] = 'contributor';
    			$delete_role = true;
    		}
    	}
    	if ( !isset( $errors ) || ( isset( $errors ) && is_object( $errors ) && false == $errors->get_error_codes() ) )
    		$errors = edit_user($user_id);
    	if ( $delete_role ) // stops users being added to current blog when they are edited
    		delete_user_meta( $user_id, $blog_prefix . 'capabilities' );

    	if ( is_multisite() && is_network_admin() && !IS_PROFILE_PAGE && current_user_can( 'manage_network_options' ) && !isset($super_admins) && empty( $_POST['super_admin'] ) == is_super_admin( $user_id ) )
    		empty( $_POST['super_admin'] ) ? revoke_super_admin( $user_id ) : grant_super_admin( $user_id );
    }

    if ( !is_wp_error( $errors ) ) {
    	echo '<div class="alert alert-success">'. __('Profile updated.', 'smooththemes') .'</div>';
    }
    else {
        $wp_error = $errors;
        if ( $wp_error->get_error_code() ) {
    		$errors = '';
    		foreach ( $wp_error->get_error_codes() as $code ) {
    			$severity = $wp_error->get_error_data($code);
    			foreach ( $wp_error->get_error_messages($code) as $error ) {
  					$errors .= '	' . $error . "<br />\n";
    			}
    		}
    		if ( !empty($errors) )
    			echo '<div class="alert alert-warning" id="login_error">' . $errors . "</div>\n";
    	}
    }
}

if (is_user_logged_in()) {
?>
<form class="form-horizontal" role="form" method="post" action="">
    <?php wp_nonce_field('update-user_' . $user_id) ?>
    <?php if ( $wp_http_referer ) : ?>
    	<input type="hidden" name="wp_http_referer" value="<?php echo esc_url($wp_http_referer); ?>" />
    <?php endif; ?>
    <h3><?php _e('Name', 'smooththemes') ?></h3>
    <?php
    $name_arr = array(
        'user_login' => array(
            'label'     => __('Username', 'smooththemes'),
            'type'      => 'text',
            'attr'      => 'disabled',
            'value'     => esc_attr($profileuser->user_login)
        ),
        'first_name' => array(
            'label'     => __('First Name', 'smooththemes'),
            'type'      => 'text',
            'value'     => esc_attr($profileuser->first_name)
        ),
        'last_name' => array(
            'label'     => __('Last Name', 'smooththemes'),
            'type'      => 'text',
            'value'     => esc_attr($profileuser->last_name)
        ),
        'nickname' => array(
            'label'     => __('Nickname', 'smooththemes'),
            'type'      => 'text',
            'value'     => esc_attr($profileuser->nickname)
        )
    );
    
    foreach($name_arr as $key => $item) {
        echo '<div class="form-group">';
        echo '<label for="'. $key .'" class="col-lg-2 col-sm-2 col-sm-2 control-label">'. $item['label'] .'</label>';
        echo '<div class="col-lg-10 col-sm-10 col-sm-10">';
        echo '<input type="'. $item['type'] .'" class="form-control" id="'. $key .'" value="'. $item['value'] .'" name="'. $key .'" '. (isset($item['attr']) ? $item['attr'] : '') .' />';
        echo '</div>';
        echo '</div>';
    }
    ?>
    <h3><?php _e('Contact Info', 'smooththemes') ?></h3>
    <?php
    $contact_infor_arr = array(
        'email'     => array(
            'label'     => __('E-mail', 'smooththemes'),
            'type'      => 'email',
            'value'     => esc_attr($profileuser->user_email)
        ),
        'url'       => array(
            'label'     => __('Website', 'smooththemes'),
            'type'      => 'text',
            'value'     => esc_attr($profileuser->user_url)
        )
    );
    
    foreach($contact_infor_arr as $key => $item) {
        echo '<div class="form-group">';
        echo '<label for="'. $key .'" class="col-lg-2 col-sm-2 col-sm-2 control-label">'. $item['label'] .'</label>';
        echo '<div class="col-lg-10 col-sm-10 col-sm-10">';
        echo '<input type="'. $item['type'] .'" class="form-control" id="'. $key .'" value="'. $item['value'] .'" name="'. $key .'" />';
        echo '</div>';
        echo '</div>';
    }
    ?>
    <h3><?php _e('About Yourself', 'smooththemes') ?></h3>
    <?php
    $contact_infor_arr = array(
        'description'   => array(
            'label'     => __('Biographical Info', 'smooththemes'),
            'type'      => 'textarea',
            'value'     => esc_attr($profileuser->description)
        ),
        'pass1'   => array(
            'label'     => __('New Password', 'smooththemes'),
            'type'      => 'password',
            'value'     => ''
        ),
        'pass2'   => array(
            'label'     => __('Repeat New Password', 'smooththemes'),
            'type'      => 'password',
            'value'     => ''
        )
    );
    
    foreach($contact_infor_arr as $key => $item) {
        echo '<div class="form-group">';
        echo '<label for="'. $key .'" class="col-lg-2 col-sm-2 col-sm-2 control-label">'. $item['label'] .'</label>';
        echo '<div class="col-lg-10  col-sm-10 col-sm-10">';
        if ($item['type'] == 'textarea') {
            echo '<textarea class="form-control" id="'. $key .'" name="'. $key .'">'. $item['value'] .'</textarea>';
        }
        else {
            echo '<input type="'. $item['type'] .'" class="form-control" id="'. $key .'" value="'. $item['value'] .'" name="'. $key .'" />';    
        }
        echo '</div>';
        echo '</div>';
    }
    ?>
    <div class="form-group">
        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-2 col-lg-10 col-sm-10 col-sm-10">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo esc_attr($user_id); ?>" />
            <input type="submit" name="submit" id="submit" class="btn btn-default button button-primary" value="<?php _e('Update Profile', 'smooththemes'); ?>" />
        </div>
    </div>
    
</form>
<?php 
}
else {
   echo do_shortcode('[st_login]');
}
