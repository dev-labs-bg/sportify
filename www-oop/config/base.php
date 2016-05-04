<?php

// Store all config values here
$base_config = array(
	'css_path'         => get_css_url(),
	'js_path'          => get_js_url(),
	'img_path'         => get_img_url(),
	'points_css_class' => array(
		0 => 'danger',
		1 => 'warning',
		3 => 'success'
	)
);

/**
 * Get base host url
 *
 * @return string
 */
function get_home_url() {
	return 'http://' . $_SERVER['HTTP_HOST'];
}

/**
 * Get static dir url
 *
 * @return string
 */
function get_static_url() {
	return get_home_url() . '/static/';
}

/**
 * Get css dir url
 *
 * @return string
 */
function get_css_url() {
	return get_static_url() . 'css/';
}

/**
 * Get js dir url
 *
 * @return string
 */
function get_js_url() {
	return get_static_url() . 'js/';
}

/**
 * Get img dir url
 *
 * @return string
 */
function get_img_url() {
	return get_static_url() . 'img/';
}