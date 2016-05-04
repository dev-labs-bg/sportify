<?php

// Store all config values here
$base_config = array(
	'css_path'         => getCssUrl(),
	'js_path'          => getJsUrl(),
	'img_path'         => getImgUrl(),
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
function getHomeUrl() {
	return 'http://' . $_SERVER['HTTP_HOST'];
}

/**
 * Get full site url
 *
 * @return string
 */
function getSiteUrl() {
    return getHomeUrl() . $_SERVER['REQUEST_URI'];
}

/**
 * Get static dir url
 *
 * @return string
 */
function getStaticUrl() {
	return getHomeUrl() . '/static/';
}

/**
 * Get css dir url
 *
 * @return string
 */
function getCssUrl() {
	return getStaticUrl() . 'css/';
}

/**
 * Get js dir url
 *
 * @return string
 */
function getJsUrl() {
	return getStaticUrl() . 'js/';
}

/**
 * Get img dir url
 *
 * @return string
 */
function getImgUrl() {
	return getStaticUrl() . 'img/';
}