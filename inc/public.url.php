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
class dayModeUrl extends dcUrlHandlers
{
    public static function archive(?string $args): void
    {
        if ($args && (preg_match('|^/([0-9]{4})/([0-9]{2})/([0-9]{2})$|', $args, $m))) {
            $params = [
                'year'      => $m[1],
                'month'     => $m[2],
                'day'       => $m[3],
                'post_type' => 'post',
            ];

            dcCore::app()->callBehavior('publicArchiveBeforeGetDates', $params, $args);
            dcCore::app()->ctx->day = dcCore::app()->blog->getDates($params);
            if (dcCore::app()->ctx->day->isEmpty()) {
                // There is no entries for the specified day.
                self::p404();
            }

            self::serveDocument('archive_day.html');
        } else {
            parent::archive($args);
        }
    }
}
