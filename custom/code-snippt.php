<?php
/**
 * This file is Copyright (c).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

/** code snippet for length of xprofile control */
function bpfr_custom_textfield_length() {

    //Check if user is logged in & if xprofile component is activated
    if ( is_user_logged_in() && bp_is_active( 'xprofile' ) ) :
        $my_custom_textfield = bp_get_member_profile_data( 'field=Brief Biography&user_id='.bp_get_member_user_id() );

        /*
		 * The length = number of characters, not words.
		 * Set the number of caracters to show.
		 * The 3 dots are the appended text ending the excerpt.
		 * Don't remove the quotes if you change this
		 * BuddyPress 2.1 will add new class and id for custom fields.
		 * The span can be omited to style this part. See ticket #5741
		 */
        if ( strlen($my_custom_textfield) > 20) :  //adjust to your need
            $my_custom_textfield = substr($my_custom_textfield, 20).'...'; //adjust to your need
        endif;
        // uncomment the following line to get a span around the displayed field content
        // echo '<span class="short-custom">'. $my_custom_textfield; .'</span>';
        // comment the following line if you use the span
        echo $my_custom_textfield;


    endif; // is_user_logged_in
}
add_action( 'bp_directory_members_item', 'bpfr_custom_textfield_length' );
/** END - controlling length of xprofile */

/**
 * Disables BuddyPress' registration process and fallsback to WordPress' one.
 */
function my_disable_bp_registration() {
    remove_action( 'bp_init',    'bp_core_wpsignup_redirect' );
    remove_action( 'bp_screens', 'bp_core_screen_signup' );
}
add_action( 'bp_loaded', 'my_disable_bp_registration' );

function firmasite_redirect_bp_signup_page($page ){
    return bp_get_root_domain() . '/wp_signup.php';
}
add_filter( 'bp_get_signup_page', "firmasite_redirect_bp_signup_page");
/** END - falls back to wordpress standard registration process **/

/**
 * add placeholders for xprofile fields
 * @param $elements
 *
 * @return mixed
 */
function bp_xprofile_field_add_placeholder($elements) {
    $attributes = [
        "field_1" => ["placeholder" => "名字"],                 // qq
        "field_69" => ["placeholder" => "QQ号"],                 // qq
        "field_6" => ["placeholder" => "微信号"],                // webchat
        "field_7" => ["placeholder" => "Facebook"],             // Facebook
        "field_39" => ["placeholder" => "Twitter"],             // Twitter
        "field_8" => ["placeholder" => "如：0123456789"],         // mobile
        "field_64" => ["placeholder" => "如：123 Swanstone Street"], // address
        "field_66" => ["placeholder" => "如：墨尔本"],       // city
        "field_65" => ["placeholder" => "如：VIC"],          // state
        "field_67" => ["placeholder" => "如：澳大利亚"]          // nation
    ];

    foreach($attributes as $key => $value) {
        if ($elements["id"] === $key) {
            $elements['placeholder'] = $value["placeholder"];
        }
    }

    return $elements;
}
add_action('bp_xprofile_field_edit_html_elements','bp_xprofile_field_add_placeholder');
/** END - adding placeholder */

/**
 * @param $wp_query_obj
 */
function ml_restrict_media_library( $wp_query_obj ) {
    global $pagenow;

    if( current_user_can("administrator") ) return;
//    if( 'admin-ajax.php' != $pagenow && 'upload.php' != $pagenow && isset($_REQUEST['action']) && $_REQUEST['action'] != 'query-attachments' ) return;
    if( 'upload.php' != $pagenow && isset($_REQUEST['action']) && $_REQUEST['action'] != 'query-attachments' ) return;

    if( current_user_can('edit_posts') )
        $wp_query_obj->set('author', wp_get_current_user()->ID );

    return;
}
add_action('pre_get_posts','ml_restrict_media_library');
/** END - getting your own posts */

/**
 * exclude logged in user from member directory
 * @param bool $qs
 * @param bool $object
 *
 * @return bool|string
 */
function bpdev_exclude_users($qs = false, $object = false) {
    //list of users to exclude

    // only for logged in user
    if (!is_user_logged_in()) {
        return $qs;
    }

    //comma separated ids of users whom you want to exclude
    $excluded_user = bp_loggedin_user_id();

    //hide for members only
    if($object != 'members') {
        return $qs;
    }

    $args = wp_parse_args($qs);

    //check if we are searching  or we are listing friends?, do not exclude in this case
    if(!empty($args['user_id']) || !empty($args['search_terms'])) {
        return $qs;
    }

    if(!empty($args['exclude'])) {
        $args['exclude'] = $args['exclude'] . ',' . $excluded_user;
    } else {
        $args['exclude'] = $excluded_user;
    }

    return build_query($args);
}
add_action('bp_ajax_querystring', 'bpdev_exclude_users', 20, 2);

function update_the_total_members_count($count) {
    // exclude the logged in user
    return $count > 0 ? $count - 1 : $count;
}
add_filter('bp_core_get_active_member_count', 'update_the_total_members_count', 10, 1);
/** END - excluding logged in user */