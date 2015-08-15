<?php
/**
 * This file is Copyright (c).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

const ERROR_GROUP_ALREADY_EXIST = "您要建的群已经存在";
const MAX_LENGTH_OF_GROUP_NAME = 20;
const ERROR_GROUP_NAME_IS_TOO_LONG = "群的名字太长(最多只允许输入%d个字)";

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

/** code snippet for length of xprofile control */
//function bpfr_custom_textfield_length() {
//
//    //Check if user is logged in & if xprofile component is activated
//    if ( is_user_logged_in() && bp_is_active( 'xprofile' ) ) :
//        $my_custom_textfield = bp_get_member_profile_data( 'field=Brief Biography&user_id='.bp_get_member_user_id() );
//
//        /*
//		 * The length = number of characters, not words.
//		 * Set the number of caracters to show.
//		 * The 3 dots are the appended text ending the excerpt.
//		 * Don't remove the quotes if you change this
//		 * BuddyPress 2.1 will add new class and id for custom fields.
//		 * The span can be omited to style this part. See ticket #5741
//		 */
//        if ( strlen($my_custom_textfield) > 20) :  //adjust to your need
//            $my_custom_textfield = substr($my_custom_textfield, 20).'...'; //adjust to your need
//        endif;
//        // uncomment the following line to get a span around the displayed field content
//        // echo '<span class="short-custom">'. $my_custom_textfield; .'</span>';
//        // comment the following line if you use the span
//        echo $my_custom_textfield;
//
//
//    endif; // is_user_logged_in
//}
//add_action( 'bp_directory_members_item', 'bpfr_custom_textfield_length' );