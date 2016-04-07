<?php

// Store all config values here
$base_config = array(
	'css_path'  => get_css_url(),
	'js_path'   => get_js_url(),
);

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