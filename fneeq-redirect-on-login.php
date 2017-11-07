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
 * @see https://wordpress.org/plugins/bbpress/
 * @TODO need to check that bbpress is active, roles exists, forums exist.
 * 
 * @param	String 	$redirect_to	The redirect destination URL.
 * @param	String 	$request	The requested redirect destination URL.
 * @param	WP_User	$user		WP_User object if login was successful, WP_Error if not.
 * @return	String	$redirect_to	The updated redirect destination URL.
 */

//Register function with Wordpress loading process.
add_filter( 'admin_init', 'fneeq_redirect_user_on_login', 10, 3 );

function fneeq_redirect_user_on_login( $redirect_to, $request, $user  ) {
	
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

	//Check if there's a user
	if ( isset( $user->roles ) && is_array( $user->roles ) ) {
		
		//Only redirect if the user is a participant
		if ( in_array( 'bbp_participant', $user->roles ) ) {
		
			//Check which forum they belong to.
			foreach( $redirect_map as $forum_slug  ) {

				//Redirect them to the first forum in the map that is found in their roles.
				if( in_array( $forum_slug, (array) $user->roles ) ) {
					
					$redirect_to = fneeq_get_forum_uri( $redirect_map[$primary_role] );
					
					return $redirect_to;				
				}
			}
		}
	}

	//Redirect the user if
	return $redirect_to;
}

/**
 * Return the URI of the requested forum.
 *
 * @param	String $forum_slug 	The slug of the forum we are requesting the URI for.
 * @return 	String $forum_uri	The complete URI for the forum.
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
