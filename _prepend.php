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
if (!defined('DC_RC_PATH')) {
    return;
}

Clearbricks::lib()->autoload([
    'dcCalendar' => __DIR__ . '/inc/class.dc.calendar.php',
    'dcDayTools' => __DIR__ . '/inc/class.dc.calendar.php',
]);

class dayModeBehaviors
{
    // Public behaviors
    public static function block()
    {
        $args = func_get_args();
        array_shift($args);

        if ($args[0] === 'Entries') {
            $attrs = $args[1];

            if (!empty($attrs['today'])) {
                $p = '<?php $today = dcDayTools::getEarlierDate(array("ts_type" => "day")); ' .
                    "\$params['post_year'] = \$today->year(); " .
                    "\$params['post_month'] = \$today->month(); " .
                    "\$params['post_day'] = \$today->day(); " .
                    "unset(\$params['limit']); " .
                    'unset($today); ' .
                " ?>\n";
            } else {
                $p = '<?php if ($_ctx->exists("day")) { ' .
                    "\$params['post_year'] = \$_ctx->day->year(); " .
                    "\$params['post_month'] = \$_ctx->day->month(); " .
                    "\$params['post_day'] = \$_ctx->day->day(); " .
                    "unset(\$params['limit']); " .
                "} ?>\n";
            }

            return $p;
        }
    }

    public static function addTplPath()
    {
        $tplset = dcCore::app()->themes->moduleInfo(dcCore::app()->blog->settings->system->theme, 'tplset');
        if (!empty($tplset) && is_dir(__DIR__ . '/default-templates/' . $tplset)) {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), __DIR__ . '/default-templates/' . $tplset);
        } else {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), __DIR__ . '/default-templates/' . DC_DEFAULT_TPLSET);
        }
    }

    // Admin behaviors
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
        $settings->addNameSpace('daymode');

        try {
            $settings->daymode->put('daymode_active', !empty($_POST['daymode_active']), 'boolean');
        } catch (Exception $e) {
            $settings->daymode->drop('daymode_active');
            $settings->daymode->put('daymode_active', !empty($_POST['daymode_active']), 'boolean');
        }
    }
}
