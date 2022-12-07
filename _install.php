<?php
/**
 * @brief dayMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Pep and contributors
 *
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$new_version = dcCore::app()->plugins->moduleInfo('dayMode', 'version');
$old_version = dcCore::app()->getVersion('dayMode');

if (version_compare((string) $old_version, $new_version, '>=')) {
    return;
}

dcCore::app()->blog->settings->addNameSpace('daymode');
dcCore::app()->blog->settings->daymode->put('daymode_active', false, 'boolean', 'plugin activation', false, true);

return true;
