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
