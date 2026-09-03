<?php

/**
 * @brief dayMode, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\dayMode;

use Dotclear\App;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsElement;

class FrontendWidgets
{
    public static function calendar(WidgetsElement $widgetsElement): string
    {
        $settings = My::settings();
        if (!$settings->getBool('daymode_active')) {
            return '';
        }

        if ($widgetsElement->offline) {
            return '';
        }

        if (!$widgetsElement->checkHomeOnly(App::url()->getType())) {
            return '';
        }

        if ($widgetsElement->homeonly == 3 && !App::url()->isType('archive')) {
            return '';
        }

        $calendar = new Calendar();

        $calendar->weekstart = is_numeric($widgetsElement->get('weekstart')) ? (int) $widgetsElement->get('weekstart') : 0;

        $res = ($widgetsElement->title ? $widgetsElement->renderTitle(Html::escapeHTML($widgetsElement->title)) : '') .
        $calendar->draw();

        return $widgetsElement->renderDiv((bool) $widgetsElement->content_only, implode(' ', ['calendar', $widgetsElement->class]), '', $res);
    }
}
