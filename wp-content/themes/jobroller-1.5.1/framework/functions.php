<?php

define( 'APP_TD', 'appthemes' );

/**
 * Loads the appropriate .mo file from wp-content/themes-lang
 */
function appthemes_load_textdomain() {
	$locale = apply_filters( 'theme_locale', get_locale(), APP_TD );

	$base = basename( get_template_directory() );

	load_textdomain( APP_TD, WP_LANG_DIR . "/themes/$base-$locale.mo" );
}

/**
 * Checks if a user is logged in, if not redirect them to the login page.
 */
function appthemes_auth_redirect_login() {
    if ( !is_user_logged_in() ) {
        nocache_headers();
        wp_redirect( get_bloginfo('wpurl') . '/wp-login.php?redirect_to=' . urlencode( $_SERVER['REQUEST_URI'] ) );
        exit();
    }
}

/**
 * Sets the favicon to the default location.
 */
function appthemes_favicon() {
?>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri() . '/images/favicon.ico'; ?>" />
<?php
}

