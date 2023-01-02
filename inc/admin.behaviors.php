<?php
/*
 * @brief dayMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Pep and contributors
 *
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */

class dayModeAdminBehaviors
{
    public static function adminBlogPreferencesForm($settings)
    {
        echo
        '<div class="fieldset"><h4 id="daymode">' . __('Daily Archives') . '</h4>' .
        '<p><label class="classic">' .
        form::checkbox('daymode_active', '1', $settings->daymode->daymode_active) .
        __('Enable daily archives and calendar') . '</label></p>' .
        '</div>';
    }

    public static function adminBeforeBlogSettingsUpdate($settings)
    {
        try {
            $settings->daymode->put('daymode_active', !empty($_POST['daymode_active']), 'boolean');
        } catch (Exception $e) {
            $settings->daymode->drop('daymode_active');
            $settings->daymode->put('daymode_active', !empty($_POST['daymode_active']), 'boolean');
        }
    }
}
