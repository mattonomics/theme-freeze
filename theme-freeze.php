<?php
/*
Plugin Name: Theme Freeze
Author: Matt Gross
Description: Would you like to test out a more complex theme than the WordPress can possibly accomodate? Simply activate Theme Freeze and it will show visitors the current active theme. Then, while logged in, switch to another theme and test it out!
Version: 1.0
*/

register_activation_hook(__FILE__, 'theme_freeze_activate');
function theme_freeze_activate() {
	$save = array(
		'template' => get_option('template'),
		'stylesheet' => get_option('stylesheet')
	);
	add_option('_theme_freeze', $save);
}

register_deactivation_hook(__FILE__, 'theme_freeze_deactivate');
function theme_freeze_deactivate() {
	delete_option('_theme_freeze');
}

add_filter('template', 'theme_freeze_template');
function theme_freeze_template() {
	$tl = get_option('_theme_freeze');
	$option = !is_user_logged_in() && !empty($tl['template']) ? $tl['template'] : get_option('template');
	return $option;
}

add_filter('stylesheet', 'theme_freeze_stylesheet');
function theme_freeze_stylesheet() {
	$tl = get_option('_theme_freeze');
	$option = !is_user_logged_in() && !empty($tl['stylesheet']) ? $tl['stylesheet'] : get_option('stylesheet');
	return $option;
}

add_filter('option_sidebars_widgets', 'theme_freeze_widgets');
function theme_freeze_widgets($value) {
	if (is_user_logged_in()) return $value;
	$option = get_option('_theme_freeze');
	$sidebars = get_option('theme_mods_' . $option['stylesheet']);
	return $sidebars['sidebars_widgets']['data'];
}

add_filter('theme_mod_nav_menu_locations', 'theme_freeze_nav');
function theme_freeze_nav($value) {
	if (is_user_logged_in()) return $value;
	$option = get_option('_theme_freeze');
	$sidebars = get_option('theme_mods_' . $option['stylesheet']);
	return $sidebars['nav_menu_locations'];
}