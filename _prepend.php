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

use Dotclear\Helper\Clearbricks;

Clearbricks::lib()->autoload([
    'dcCalendar' => __DIR__ . '/inc/class.dc.calendar.php',
    'dcDayTools' => __DIR__ . '/inc/class.dc.calendar.php',

    'dayModeAdminBehaviors'  => __DIR__ . '/inc/admin.behaviors.php',
    'dayModePublicBehaviors' => __DIR__ . '/inc/public.behaviors.php',
    'dayModeTpl'             => __DIR__ . '/inc/public.tpl.php',
    'dayModeUrl'             => __DIR__ . '/inc/public.url.php',
    'dayModeWidgets'         => __DIR__ . '/inc/widgets.php',
]);

/*
 * Redefines 'archive' urlHandler to plug the new day mode
 */
dcCore::app()->url->register('archive', 'archive', '^archive(/.+)?$', [dayModeUrl::class, 'archive']);
