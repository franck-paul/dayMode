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

use dcCore;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsElement;

class FrontendWidgets
{
    public static function calendar(WidgetsElement $w): string
    {
        $settings = My::settings();
        if (!(bool) $settings->daymode_active) {
            return '';
        }

        if ($w->offline) {
            return '';
        }

        if (!$w->checkHomeOnly(dcCore::app()->url->type)) {
            return '';
        }

        if ($w->homeonly == 3 && dcCore::app()->url->type !== 'archive') {
            return '';
        }

        $calendar = new Calendar();

        $calendar->weekstart = $w->weekstart;

        $res = ($w->title ? $w->renderTitle(Html::escapeHTML($w->title)) : '') .
        $calendar->draw();

        return $w->renderDiv((bool) $w->content_only, join(' ', ['calendar', $w->class]), '', $res);
    }
}
