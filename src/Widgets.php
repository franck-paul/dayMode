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

use Dotclear\Plugin\widgets\WidgetsStack;

class Widgets
{
    public static function initWidgets(WidgetsStack $w): string
    {
        $w
            ->create('calendar', __('DayMode: calendar'), FrontendWidgets::calendar(...), null, __('Tickets calendar'))
            ->addTitle(__('Calendar'))
            ->setting(
                'weekstart',
                __('First day:'),
                0,
                'combo',
                array_flip([
                    __('Sunday'),
                    __('Monday'),
                    __('Tuesday'),
                    __('Wednesday'),
                    __('Thursday'),
                    __('Friday'),
                    __('Saturday'),
                ])
            )
            ->addHomeOnly([__('Archives only') => 3])
            ->addContentOnly()
            ->addClass()
            ->addOffline();

        return '';
    }
}
