<?php
/**
 * This file is Copyright (c).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Disables BuddyPress' registration process and fallsback to WordPress' one.
 */
//function my_disable_bp_registration() {
//    remove_action( 'bp_init',    'bp_core_wpsignup_redirect' );
//    remove_action( 'bp_screens', 'bp_core_screen_signup' );
//}
//add_action( 'bp_loaded', 'my_disable_bp_registration' );
//
//function firmasite_redirect_bp_signup_page($page ){
//    return bp_get_root_domain() . '/wp-signup.php';
//}
//add_filter( 'bp_get_signup_page', "firmasite_redirect_bp_signup_page");
/** END - falls back to wordpress standard registration process **/

//function bp_xprofile_field_add_placeholder($elements) {
//    $attributes = [
//        "field_1" => ["placeholder" => "名字"],                 // qq
//        "field_69" => ["placeholder" => "QQ号"],                 // qq
//        "field_6" => ["placeholder" => "微信号"],                // webchat
//        "field_7" => ["placeholder" => "Facebook"],             // Facebook
//        "field_39" => ["placeholder" => "Twitter"],             // Twitter
//        "field_8" => ["placeholder" => "如：0123456789"],         // mobile
//        "field_64" => ["placeholder" => "如：123 Swanstone Street"], // address
//        "field_66" => ["placeholder" => "如：墨尔本"],       // city
//        "field_65" => ["placeholder" => "如：VIC"],          // state
//        "field_67" => ["placeholder" => "如：澳大利亚"]          // nation
//    ];
//
//    foreach($attributes as $key => $value) {
//        if ($elements["id"] === $key) {
//            $elements['placeholder'] = $value["placeholder"];
//        }
//    }
//
//    return $elements;
//}
//add_action('bp_xprofile_field_edit_html_elements','bp_xprofile_field_add_placeholder');

/**
 * check if the given group name already exists before it gets persisted
 * @param $name
 * @param $id
 * @return bool
 */
function check_group_name($name, $id) {
    if (bp_has_groups( ["user_id" => bp_loggedin_user_id()] )) {
        while(bp_groups()) {
            bp_the_group();

            // check if the group name already exists when creating a new group, case-sensitive
            if (empty($id) && strtoupper($name) === strtoupper(bp_get_group_name())) {
                return false;
            }
        }
    }
    return $name;
}
add_filter('groups_group_name_before_save','check_group_name', 10, 2);
    // modify the error message
    function get_duplicated_group_name_error($content, $type) {
        return "同样的组已经有了";
    }
    add_filter("bp_core_render_message_content", "get_duplicated_group_name_error", 10, 2);
/** END - checking group name **/

