<?php
/**
 * @package ST User
 * 
 * Form login modal
 */
?>
<?php
if (is_user_logged_in()) {
    $user_id = (int) get_current_user_id();
    $profileuser = get_userdata($user_id);
    echo sprintf(__('Hi <span class="user-loged">%1$s</span> <a href="%2$s">Logout</a>', 'smooththemes'), ucwords( $profileuser->display_name ), $parameters['logout_redirect']);
}
else {
?>
<div class="st-login-modal">
    <ul class="st-login-act">
        <li><a alt="signin-tab" href="#loginModal" class="open-login-modal" data-toggle="modal"><strong><?php _e('Sign In', 'smooththemes') ?></strong></a></li>
        <li class="sep"><?php _e('or', 'smooththemes') ?></li>
        <li><a alt="signup-tab" href="#loginModal" class="open-login-modal" data-toggle="modal"><strong><?php _e('Create Account', 'smooththemes') ?></strong></a></li>
    </ul>
</div>

<div id="loginModal" class="loginModal modal fade modal-mini text-left" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div id="signup-tab" class="tab active">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 id="modal-header-label"><?php _e('Create new account', 'smooththemes') ?></h3>
            </div>
            <div class="modal-body">
                <?php echo do_shortcode('[st_register disable_link="y" success_redirect="'. $parameters['register_success_redirect'] .'"]') ?>
            </div>
            <div class="modal-footer">
                <?php _e('Already a member?', 'smooththemes') ?>
                <a alt="signin-tab" class="modal-form-change" href="#"><?php _e('Sign In', 'smooththemes') ?></a>
                <br/>
                <a alt="lostpwd-tab" class="modal-form-change" href="#"><?php _e('Lost your password?', 'smooththemes') ?></a>
            </div>
        </div>
    </div>
  </div>

  <div id="signin-tab" class="tab">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h3 class="modal-header-label"><?php _e('Sign In', 'smooththemes') ?></h3>
            </div>
            <div class="modal-body">
                <?php echo do_shortcode('[st_login disable_link="y" login_redirect="'. $parameters['login_success_redirect'] .'"]') ?>
            </div>
            <div class="modal-footer">
                <?php printf(__('New to %s ?', 'smooththemes'),get_bloginfo('name')); ?> <a alt="signup-tab" class="modal-form-change" href="#"><?php _e('Create Account', 'smooththemes') ?></a>
                <br/>
                <a alt="lostpwd-tab" class="modal-form-change" href="#"><?php _e('Lost your password?', 'smooththemes') ?></a>
            </div>
        </div>
    </div>
  </div>


    <div id="lostpwd-tab" class="tab">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-header-label"><?php _e('Lost your password', 'smooththemes') ?></h3>
                </div>
                <div class="modal-body">
                    <?php echo do_shortcode('[st_lost_password]') ?>
                </div>
                <div class="modal-footer">
                    <a alt="signin-tab" class="modal-form-change" href="#"><?php _e('Sign In', 'smooththemes') ?></a> <br/>
                    <?php printf(__('New to %s ?', 'smooththemes'),get_bloginfo('name')); ?> <a alt="signup-tab" class="modal-form-change" href="#"><?php _e('Create Account', 'smooththemes') ?></a>
                </div>
            </div>
        </div>
    </div>


</div>
<?php
}