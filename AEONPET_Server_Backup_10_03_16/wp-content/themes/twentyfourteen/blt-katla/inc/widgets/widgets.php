<?php

	# Include widgets
	foreach (blt_theme_config('widgets') as $key => $value) {
		include_once $value.'.php';
	}