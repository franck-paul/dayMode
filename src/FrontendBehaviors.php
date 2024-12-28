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
use Dotclear\App;
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
            $ret = '<a id="bc-home" href="' . App::blog()->url() . '">' . __('Home') . '</a>';
            if (!App::frontend()->context()->exists('day')) {
                if (!App::frontend()->context()->archives) {
                    // Global archives
                    $ret .= $separator . __('Archives');
                } else {
                    // Month archive
                    $ret .= $separator . '<a href="' . App::blog()->url() . App::url()->getURLFor('archive') . '">' . __('Archives') . '</a>';
                    $ret .= $separator . Date::dt2str('%B %Y', App::frontend()->context()->archives->dt);
                }
            } else {
                // Day archive
                $ret .= $separator . '<a href="' . App::blog()->url() . App::url()->getURLFor('archive') . '">' . __('Archives') . '</a>';
                $ret .= $separator . '<a href="' . App::blog()->url() . App::url()->getURLFor('archive', Date::dt2str('%Y/%m', App::frontend()->context()->day->dt)) . '">' . Date::dt2str('%B %Y', App::frontend()->context()->day->dt) . '</a>';
                $ret .= $separator . Date::dt2str('%e', App::frontend()->context()->day->dt);
            }

            return $ret;
        }

        return '';
    }

    /**
     * @param      string                                               $block  The block
     * @param      array<string, string>|ArrayObject<string, string>    $attr   The attributes
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
                $p = '<?php if (App::frontend()->context()->exists("day")) { ' .
                    "\$params['post_year'] = App::frontend()->context()->day->year(); " .
                    "\$params['post_month'] = App::frontend()->context()->day->month(); " .
                    "\$params['post_day'] = App::frontend()->context()->day->day(); " .
                    "unset(\$params['limit']); " .
                "} ?>\n";
            }

            return $p;
        }

        return '';
    }

    public static function addTplPath(): string
    {
        App::frontend()->template()->appendPath(My::tplPath());

        return '';
    }
}
