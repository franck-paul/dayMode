<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of dayMode, a plugin for Dotclear 2.
#
# Copyright (c) 2006-2015 Pep and contributors
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------
if (!defined('DC_RC_PATH')) return;

$this->registerModule(
	/* Name */		"dayMode",
	/* Description*/	"Provides daily archives and an associated calendar widget",
	/* Author */		"Pep and contributors",
	/* Version */		'1.1-RC6',
	/* Properties */
	array(
		'permissions' => 'admin',
		'type' => 'plugin',
		'dc_min' => '2.8',
		'support' => 'http://forum.dotclear.org/viewtopic.php?id=48285',
		'details' => 'http://plugins.dotaddict.org/dc2/details/dayMode'
	)
);