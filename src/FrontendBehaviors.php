<?php
/**
 * @brief dayMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\dayMode;

use ArrayObject;
use dcCore;
use Dotclear\Core\Frontend\Utility;
use Dotclear\Helper\Date;

class FrontendBehaviors
{
    public static function publicHeadContent(): string
    {
        $settings = My::settings();
        if (!(bool) $settings->daymode_active) {
            return '';
        }

        echo
        My::cssLoad('dayMode.css');

        return '';
    }

    public static function publicBreadcrumbExtended(string $context): string
    {
        // Would like to return a boolean value but behaviors management does not allow that yet.
        return $context === 'archive' ? '1' : '';
    }

    public static function publicBreadcrumb(string $context, string $separator): string
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
                    $ret .= $separator . Date::dt2str('%B %Y', dcCore::app()->ctx->archives->dt);
                }
            } else {
                // Day archive
                $ret .= $separator . '<a href="' . dcCore::app()->blog->url . dcCore::app()->url->getURLFor('archive') . '">' . __('Archives') . '</a>';
                $ret .= $separator . '<a href="' . dcCore::app()->blog->url . dcCore::app()->url->getURLFor('archive', Date::dt2str('%Y/%m', dcCore::app()->ctx->day->dt)) . '">' . Date::dt2str('%B %Y', dcCore::app()->ctx->day->dt) . '</a>';
                $ret .= $separator . Date::dt2str('%e', dcCore::app()->ctx->day->dt);
            }

            return $ret;
        }

        return '';
    }

    /**
     * @param      string                                               $block  The block
     * @param      array<string, string>|ArrayObject<string, string>    $attr   The attributes
     *
     * @return     string
     */
    public static function block(string $block, array|ArrayObject $attr): string
    {
        if ($block === 'Entries') {
            if (!empty($attr['today'])) {
                $p = '<?php $today = ' . CoreHelper::class . '::getEarlierDate(array("ts_type" => "day")); ' .
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

        return '';
    }

    public static function addTplPath(): string
    {
        $tplset = dcCore::app()->themes->moduleInfo(dcCore::app()->blog->settings->system->theme, 'tplset');
        if (!empty($tplset) && is_dir(My::path() . '/' . Utility::TPL_ROOT . '/' . $tplset)) {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), My::path() . '/' . Utility::TPL_ROOT . '/' . $tplset);
        } else {
            dcCore::app()->tpl->setPath(dcCore::app()->tpl->getPath(), My::path() . '/' . Utility::TPL_ROOT . '/' . DC_DEFAULT_TPLSET);
        }

        return '';
    }
}
