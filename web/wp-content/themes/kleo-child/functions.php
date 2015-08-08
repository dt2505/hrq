<?php
/**
 * @package WordPress
 * @subpackage Kleo
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since Kleo 1.0
 */

/**
 * Kleo Child Theme Functions
 * Add custom code below
*/
const FOLLOWER_ZH_CN = "粉丝";
const FOLLOWING_ZH_CN = "关注";
const MEDIA_NAME_ZH_CH = "媒体";
const MEDIA_NAME_REPLACEMENT_ZH_CH = "照片";

// this will restrict how many characters should be shown on member profile header
const MAX_CHAR_TO_SHOW_THE_LATEST_UPDATE = 50;

/* my members landing tab */
define('BP_DEFAULT_COMPONENT', 'profile' );

/**
 * remove wp logo from admin toolbar
 */
add_action( 'admin_bar_menu', 'remove_wp_logo', 999 );

function remove_wp_logo( $wp_admin_bar ) {
    $wp_admin_bar->remove_node( 'wp-logo' );
}
/** END - remove wp logo from admin toolbar */

/**
 * rearrange menu buttons
 */
function reset_bp_nav_order() {
    global $bp;

    $bp->bp_nav['activity']['position'] = 10;
    $bp->bp_nav['messages']['position'] = 20;
    $bp->bp_nav['notifications']['position'] = 30;

    /** -=======wp follower plugin======- */
    unset($bp->bp_nav['following']); // hide following
    unset($bp->bp_nav['followers']); // hide follower
    /** -==============================- */
    unset($bp->bp_nav['blogs']);     // hide sites (it is called "blogs" by theme)

    $bp->bp_nav['friends']['position'] = 60;
    // rtMedia = 70
    $bp->bp_nav['groups']['position'] = 80;

    /** -=======social-article plugin======- */
    if( isset ($bp->bp_nav['articles'])){
        $bp->bp_nav['articles']['position'] = 90;
    }
    /** -==============================- */


    $bp->bp_nav['profile']['position'] = 110;
    $bp->bp_nav['settings']['position'] = 120;
}
add_action( 'bp_setup_nav', 'reset_bp_nav_order', 999 );

// re-order the rtmedia button
function new_media_tab_position(){
    global $bp;
    if( isset ($bp->bp_nav['media'])){
        $bp->bp_nav['media']['position'] = 70;
        $name = $bp->bp_nav['media']['name'];
        $bp->bp_nav['media']['name'] = str_replace(MEDIA_NAME_ZH_CH, MEDIA_NAME_REPLACEMENT_ZH_CH, $name);
    }
}
add_action('bp_init','new_media_tab_position', 12);
/** END - rearrange profile tab order */

/**
 * show extra info at user profile header
 */
function show_extra_meta_pref() {
    global $bp;

    $extraElements = array(
        getFollowStr($bp),
    );

    foreach($extraElements as $value) {
        echo $value;
    }
}
function getFollowStr($bp) {
    $counts  = bp_follow_total_follow_counts( array( 'user_id' => bp_loggedin_user_id() ) );

    $followingUrl = trailingslashit( bp_loggedin_user_domain() . $bp->follow->following->slug );
    $followerUrl = trailingslashit( bp_loggedin_user_domain() . $bp->follow->followers->slug );

    $followingStr = getFollowLink($followingUrl, $counts['following']);
    $followerStr = getFollowLink($followerUrl, $counts['followers']);

    return sprintf('<div id="my_customized_follow_counts">%s %s | %s %s</div>', FOLLOWING_ZH_CN, $followingStr, FOLLOWER_ZH_CN, $followerStr);
}
function getFollowLink($url, $count) {
    $style = 'font-weight:bold';
    $clickable = sprintf('<a href="%s"><span style="%s">%d</span></a>', $url, $style, $count);
    $unclickable = sprintf('<span>%d</span>', $count);
    return $count > 0 ? $clickable : $unclickable;
}
add_action("bp_profile_header_meta", "show_extra_meta_pref");
/** END - showing extra info */

/**
 * shorten the latest_update further down to MAXIMUM_CHARACTERS_TO_SHOW
 * @param $latest_update
 * @return string
 */
function my_latest_update_pref($latest_update) {
    return bp_create_excerpt( $latest_update, MAX_CHAR_TO_SHOW_THE_LATEST_UPDATE );
}
add_filter("bp_get_activity_latest_update_excerpt", "my_latest_update_pref");
/** END - shortening the latest update */

// get your own posts
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
//add_action( 'init', 'my_deregister_heartbeat', 1 );
//function my_deregister_heartbeat() {
//    global $pagenow;
//
//    if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow )
//        wp_deregister_script('heartbeat');
//}
