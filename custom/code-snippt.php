<?php
/**
 * This file is Copyright (c).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @return array|null
 */
function get_user_roles() {
    if ( is_user_logged_in() ) {
        global $current_user;

        return $current_user->roles;
    } else {
        return null;
    }
}

/** restrict mime types based on user roles */
/**
 * use this to restrict mime types
 * @param array $existing_mimes
 * @return array
 */
function custom_upload_mimes ( $existing_mimes = array() ) {
    get_user_roles();

	// set role-based mime types here

// Add file extension 'extension' with mime type 'mime/type'
    $existing_mimes['extension'] = 'mime/type';

// add as many as you like e.g.

    $existing_mimes['doc'] = 'application/msword';

// remove items here if desired ...

// and return the new full result
    return $existing_mimes;
}
add_filter('upload_mimes', 'custom_upload_mimes');
/** END - restrict mime types */

/**
 * uncomment the following code for disabling heartbeat if encountering high CPU problem
 *
 * - admin-ajax.php is part of WordPress AJAX API that is used in the backend and frontend.
 * - WordPress heartbeat API also calls this script every 15 seconds to auto save your posts
 * - while you’re editing your posts.  It also calls it on various other pages while you’re
 * - logged in to provide you with information like – what your fellow administrators and
 * - authors are currently working on. There are other things that the heartbeat API will
 * - do as well. Now imagine if you have many contributors and administrators logged into
 * - the backend, with each session sending heartbeat API requests to the server every
 * - 15 seconds.  This could cause some performance problems to your WordPress installation
 * - and admin-ajax.php high CPU problem is directly related to this
 */
add_action( 'init', 'my_deregister_heartbeat', 1 );
function my_deregister_heartbeat() {
    global $pagenow;

    if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow )
        wp_deregister_script('heartbeat');
}

/**
 * shorten the group description further down to MAX_CHAR_TO_SHOW_GROUP_DESC
 *
 * @param $value
 * @param $group
 *
 * @return string
 */
function reset_group_description_excerpt($value, $group) {
    return bp_create_excerpt($group->description, 10);
}
add_filter("bp_get_group_description_excerpt", "reset_group_description_excerpt", 10, 2);