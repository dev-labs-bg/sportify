<?php

namespace Devlabs\App;

// Store all config values here
$base_config = array(
	'css_path'         => UrlHelper::getCssUrl(),
	'js_path'          => UrlHelper::getJsUrl(),
	'img_path'         => UrlHelper::getImgUrl(),
	'points_css_class' => array(
		0 => 'danger',
		1 => 'warning',
		3 => 'success'
	)
);
