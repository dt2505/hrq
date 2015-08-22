<?php
/**
 * This file is Copyright (c).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

const ERROR_GROUP_ALREADY_EXIST = "您要建的群已经存在";
const MAX_LENGTH_OF_GROUP_NAME = 20;
const ERROR_GROUP_NAME_IS_TOO_LONG = "群的名字太长 (最多只允许输入%d个字)";
const MAX_LENGTH_OF_GROUP_DESC = 150;
const ERROR_GROUP_DESC_IS_TOO_LONG = "群简介太长 (最多只允许输入%d个字)";

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
//    return bp_get_root_domain() . '/wp_signup.php';
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
function check_duplicated_group_name($name, $id) {
    if (bp_has_groups( ["user_id" => bp_loggedin_user_id()] )) {

        // 1. validate the length of group name
        if (mb_strlen($name) > MAX_LENGTH_OF_GROUP_NAME) {
            bp_core_add_message( __( sprintf(ERROR_GROUP_NAME_IS_TOO_LONG, MAX_LENGTH_OF_GROUP_NAME), 'buddypress' ), 'error' );
            bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/' . bp_get_groups_current_create_step() ) );
        }

        // 2. check if the group name already exists when creating a new group, case-sensitive
        $existed = false;
        while(bp_groups()) {
            bp_the_group();

            if (empty($id) && strtoupper($name) === strtoupper(bp_get_group_name())) {
                $existed = true;
                break;
            }
        }
        if ($existed) {
            bp_core_add_message( __( ERROR_GROUP_ALREADY_EXIST, 'buddypress' ), 'error' );
            bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/' . bp_get_groups_current_create_step() ) );
        }
    }
    return $name;
}
add_filter('groups_group_name_before_save','check_duplicated_group_name', 10, 2);
/** END - checking group name **/

/**
 * check the number of characters in group description
 * @param $description
 * @param $id
 */
function check_characters_of_group_description($description, $id) {
    // check only when it is new
    if (empty($id)) {
        if (mb_strlen($description) > MAX_LENGTH_OF_GROUP_DESC) {
            bp_core_add_message( __( sprintf(ERROR_GROUP_DESC_IS_TOO_LONG, MAX_LENGTH_OF_GROUP_DESC), 'buddypress' ), 'error' );
            bp_core_redirect( trailingslashit( bp_get_groups_directory_permalink() . 'create/step/' . bp_get_groups_current_create_step() ) );
        }
    } else {
        return $description;
    }
}
add_filter("groups_group_description_before_save", "check_characters_of_group_description", 10, 2);
/** END - checking group description characters **/
