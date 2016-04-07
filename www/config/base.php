<?php

// Store all config values here
$config = array(
	'root_path' => get_root_dir(),
	'css_path'  => get_css_dir(),
	'js_path'   => get_js_dir(),
);

/**
 * Get absolute root dir path of the project
 *
 * @return string
 */
function get_root_dir() {
	return dirname( __FILE__ );
}

/**
 * Get absolute css dir path
 *
 * @return string
 */
function get_css_dir() {
	return get_root_dir() . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'css';
}

/**
 * Get absolute js dir path
 *
 * @return string
 */
function get_js_dir() {
	return get_root_dir() . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . 'js';
}