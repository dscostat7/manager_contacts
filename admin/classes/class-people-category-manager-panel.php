<?php
/* "Copyright 2012 A3 Revolution Web Design" This software is distributed under the terms of GNU GENERAL PUBLIC LICENSE Version 3, 29 June 2007 */
/**
 * People Contact Manager Panel
 *
 * Table Of Contents
 *
 * admin_screen()
 */

namespace A3Rev\ContactPeople\Admin;

use A3Rev\ContactPeople\Data as Data;

class Category_Manager
{

	public static function custom_upgrade_top_message( $message, $setting_id ) {
		if ( $setting_id == 'a3_people_list_group_box' ) {
			$message = '<div class="pro_feature_top_message">'
				. sprintf( __( '<strong><a href="%s" target="_blank">Ultimate Version Feature</a></strong> The Groups feature enables admins to create Groups of profiles and insert them by shortcode or Gutenberg Contact Groups Block completely independent of the Manager Contacts Page display. Simply Create Groups (like Categories) and assign Profiles to any number of groups. Then the Group (with a Group location map if required) can be inserted by shortcode or Block in any page or post.', 'manager-contacts-page-contact-people' ), $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->pro_plugin_page_url )
				. '</div>';
		}

		return $message;
	}

	public static function admin_screen () {
		$message = '';

		add_filter( $GLOBALS[PEOPLE_CONTACT_PREFIX.'admin_init']->plugin_name . '_upgrade_top_message', array( __CLASS__, 'custom_upgrade_top_message' ), 10, 2 );

		?>
        <div id="htmlForm">
        <div style="clear:both"></div>
		<div class="wrap a3rev_manager_panel_container">
        
        <?php echo $message; ?>
		<?php
		if ( isset($_GET['action']) && $_GET['action'] == 'add_new' ) {
			self::admin_category_update();
		} elseif ( isset($_GET['action']) && $_GET['action'] == 'edit' ) {
			self::admin_category_update( absint( $_GET['id'] ) );
		} elseif ( isset($_GET['action']) && $_GET['action'] == 'view-profile' ) {
			self::admin_category_profiles( absint( $_GET['id'] ) );
		} else {
			self::admin_categories();
		}
		?>
        </div>
        </div>
		<?php
	}
	
	public static function admin_categories () {
		$all_categories = array ( array('id' => 1, 'category_name' => __('Profile Group', 'manager-contacts-page-contact-people' ) ) );
	?>
        <div class="icon32 icon32-a3rev-ui-settings icon32-a3revpeople-contact-settings" id="icon32-a3revpeople-category-manager"><br></div><h1><?php _e('Groups', 'manager-contacts-page-contact-people' ); ?> <a class="add-new-h2" href="<?php echo admin_url('admin.php?page=people-category-manager&action=add_new', 'relative');?>"><?php _e('Add New', 'manager-contacts-page-contact-people' ); ?></a></h1>
		<div style="clear:both;height:5px;"></div>

		<?php
	}
	
	public static function admin_category_update( $category_id = 0) {
		global $people_contact_location_map_settings;
		$category_name      = '';
		$publish            = 1;
		$g_zoom             = $people_contact_location_map_settings['zoom_level'];
		$g_map_type         = $people_contact_location_map_settings['map_type'];
		$g_width_type       = $people_contact_location_map_settings['map_width_type'];
		$g_width_responsive = $people_contact_location_map_settings['map_width_responsive'];
		$g_width_fixed      = $people_contact_location_map_settings['map_width_fixed'];
		$g_height           = $people_contact_location_map_settings['map_height'];
		$bt_type            = 'add_new_category';
		$bt_value           = __('Create', 'manager-contacts-page-contact-people' );
		$title              = __('Add New Group', 'manager-contacts-page-contact-people' );
		if ( $category_id > 0 ) {
			$data = array('id' => 1, 'category_name' => __('Profile Group', 'manager-contacts-page-contact-people' ) );
			$category_name = $data['category_name'];
			$publish = 1;
			$bt_type = 'update_category';
			$title = __('Edit Group', 'manager-contacts-page-contact-people' );
			$bt_value = __('Update', 'manager-contacts-page-contact-people' );
		}
	?>
		<div class="icon32 icon32-posts-post" id="icon-edit"><br></div><h1><?php echo $title;?></h1>
		<div style="clear:both;"></div>

       	<script type="text/javascript">
		(function($) {
		$(document).ready(function() {
			if ( $("input.group_activate_shortcode:checked").val() != 1) {
				$(".a3_people_group_g_map_container").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
			}
			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.group_activate_shortcode', function( event, value, status ) {
				$(".a3_people_group_g_map_container").attr('style','display:none;');
				if ( status == 'true' ) {
					$(".a3_people_group_g_map_container").slideDown();
				} else {
					$(".a3_people_group_g_map_container").slideUp();
				}
			});

			if ( $("input.map_width_type:checked").val() == 'percent') {
				$(".map_width_type_fixed").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
			} else {
				$(".map_width_type_percent").css( {'visibility': 'hidden', 'height' : '0px', 'overflow' : 'hidden', 'margin-bottom' : '0px'} );
			}
			$(document).on( "a3rev-ui-onoff_checkbox-switch", '.map_width_type', function( event, value, status ) {
				$(".map_width_type_fixed").attr('style','display:none;');
				$(".map_width_type_percent").attr('style','display:none;');
				if ( status == 'true' ) {
					$(".map_width_type_fixed").slideUp();
					$(".map_width_type_percent").slideDown();
				} else {
					$(".map_width_type_fixed").slideDown();
					$(".map_width_type_percent").slideUp();
				}
			});
		});
		})(jQuery);
		</script>
    <?php
	}
	
	public static function admin_category_profiles ( $category_id ) {
		if ( $category_id < 1 ) return '';
		
		global $people_contact_grid_view_icon;
		
		$current_category = array('id' => 1, 'category_name' => __('Profile Group', 'manager-contacts-page-contact-people' ) );
		
		?>
        
        <div class="icon32 icon32-a3rev-ui-settings icon32-a3revpeople-contact-settings" id="icon32-a3revpeople-category-profiles-manager"><br></div><h1>"<?php echo esc_attr( stripslashes( $current_category['category_name'] ) ) ; ?>" <?php _e('Profiles', 'manager-contacts-page-contact-people' ); ?></h1>
		<div style="clear:both;height:5px;"></div>

		<?php	
	}
}
