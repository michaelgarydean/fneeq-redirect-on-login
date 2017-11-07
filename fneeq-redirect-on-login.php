<?php
/**
 * Plugin Name:     FNEEQ Redirect User on Login
 * Plugin URI:      https://github.com/mykedean/fneeq-redirect-on-login
 * Description:     PLUGIN DESCRIPTION HERE
 * Author:          Michael Dean
 * Author URI:      https://github.com/mykedean
 * Text Domain:     fneeq-redirect-on-login
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Fneeq_Redirect_On_Login
 */

/**
 * Redirect a user when they log in to the FNEEQ site to a forum associated with their role.
 *
 * We have assumed the paths to the forums are as follows: 
 * http://fneeqforum.wpdev0.koumbit.net/forums/forum/cegep/
 *
 * @see https://wordpress.org/plugins/bbpress/
 * @TODO need to check that bbpress is active, roles exists, forums exist.
 */

//Register function with Wordpress loading process.
add_action( 'admin_init', 'fneeq_redirect_user_on_login' );

function fneeq_redirect_user_on_login() {
	
	/**
	 * A map of where each role should lead.
	 * The roles are used as keys and slugs for the forums they lead to are the elements.
	 * @TODO This should be exposed to the user interface.
	 */
	$redirect_map = array(
		'cegep'		=> 'cegep',
		'prive'		=> 'prive',
		'universite'	=> 'universite',
	);

	//Get the current user object. Returns 0 if no user.
	//@TODO error checking for no user.
	$current_user = wp_get_current_user();

	//Get a list of roles the user is a member of
	$roles = $current_user->roles;

	//For testing, lets just get the first role and see what's up
	$roles_primary = $roles[0];

	//if( '' )
}

/**
 * Return the URI of the requested forum.
 *
 * @return String	The complete URI for the forum.
 *
 * String $forum_slug 	The slug of the forum we are requesting the URI for.
 */
function fneeq_get_forum_uri( $single_forum_slug ) {

	//Get the base URL
	$site_url = get_site_url();

        //Get the slugs set in wp-admin/options-general.php?page=bbpress.
        $root_slug = get_option( '_bbp_root_slug', 'forums' );
        $forums_slug = get_option( '_bbp_forum_slug', 'forum' );

	//Build the URI from the site URI.
	$forum_uri = $site_url . '/' . $root_slug . '/' . $forums_slug . '/' . $single_forum_slug;		
	return $forum_uri;
}
