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

// dead but useful code, in order to have translations
__('dayMode') . __('Provides daily archives and an associated calendar widget');

require_once dirname(__FILE__) . '/_widgets.php';

dcCore::app()->addBehavior('adminBlogPreferencesFormV2', ['dayModeBehaviors','adminBlogPreferencesForm']);
dcCore::app()->addBehavior('adminBeforeBlogSettingsUpdate', ['dayModeBehaviors','adminBeforeBlogSettingsUpdate']);
