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
class dayModePublicBehaviors
{
    public static function publicHeadContent()
    {
        if (!(bool) dcCore::app()->blog->settings->daymode->daymode_active) {
            return;
        }

        echo
        dcUtils::cssModuleLoad('dayMode/css/dayMode.css');
    }

    public static function publicBreadcrumbExtended($context)
    {
        return $context === 'archive';
    }

    public static function publicBreadcrumb($context, $separator)
    {
        if ($context === 'archive') {
            // Archives
            $ret = '<a id="bc-home" href="' . dcCore::app()->blog->url . '">' . __('Home') . '</a>';
            if (!dcCore::app()->ctx->exists('day')) {
                if (!dcCore::app()->ctx->archives) {
                    // Global archives
                    $ret .= $separator . __('Archives');
                } else {
                    // Month archive
                    $ret .= $separator . '<a href="' . dcCore::app()->blog->url . dcCore::app()->url->getURLFor('archive') . '">' . __('Archives') . '</a>';
                    $ret .= $separator . dt::dt2str('%B %Y', dcCore::app()->ctx->archives->dt);
                }
            } else {
                // Day archive
                $ret .= $separator . '<a href="' . dcCore::app()->blog->url . dcCore::app()->url->getURLFor('archive') . '">' . __('Archives') . '</a>';
                $ret .= $separator . '<a href="' . dcCore::app()->blog->url . dcCore::app()->url->getURLFor('archive', dt::dt2str('%Y/%m', dcCore::app()->ctx->day->dt)) . '">' . dt::dt2str('%B %Y', dcCore::app()->ctx->day->dt) . '</a>';
                $ret .= $separator . dt::dt2str('%e', dcCore::app()->ctx->day->dt);
            }

            return $ret;
        }
    }

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
                $p = '<?php if (dcCore::app()->ctx->exists("day")) { ' .
                    "\$params['post_year'] = dcCore::app()->ctx->day->year(); " .
                    "\$params['post_month'] = dcCore::app()->ctx->day->month(); " .
                    "\$params['post_day'] = dcCore::app()->ctx->day->day(); " .
                    "unset(\$params['limit']); " .
                "} ?>\n";
            }

            return $p;
        }
    }

    public static function addTplPath()
    {
        $tplset = dcCore::app()->themes->moduleInfo(dcCore::app()->blog->settings->system->theme, 'tplset');
        if (!empty($tplset) && is_dir(__DIR__ . '/../default-templates/' . $tplset)) {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), __DIR__ . '/../default-templates/' . $tplset);
        } else {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), __DIR__ . '/../default-templates/' . DC_DEFAULT_TPLSET);
        }
    }
}
