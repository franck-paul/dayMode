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
class dayModeWidgets
{
    public static function calendar($w)
    {
        dcCore::app()->blog->settings->addNamespace('daymode');
        if (!(bool) dcCore::app()->blog->settings->daymode->daymode_active) {
            return;
        }

        if ($w->offline) {
            return;
        }

        if (!$w->checkHomeOnly(dcCore::app()->url->type)) {
            return;
        }

        if ($w->homeonly == 3 && dcCore::app()->url->type !== 'archive') {
            return;
        }

        $calendar = new dcCalendar();

        $calendar->weekstart = $w->weekstart;

        $res = ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '') .
        $calendar->draw();

        return $w->renderDiv($w->content_only, join(' ', ['calendar', $w->class]), '', $res);
    }

    public static function init($w)
    {
        $w
            ->create('calendar', __('DayMode: calendar'), ['dayModeWidgets', 'calendar'], null, __('Tickets calendar'))
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
    }
}
