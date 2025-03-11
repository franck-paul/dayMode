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

use Dotclear\App;
use Dotclear\Core\Frontend\Url;

class FrontendUrl extends Url
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

            App::behavior()->callBehavior('publicArchiveBeforeGetDates', $params, $args);
            App::frontend()->context()->day = App::blog()->getDates($params);
            if (App::frontend()->context()->day->isEmpty()) {
                // There is no entries for the specified day.
                self::p404();
            }

            self::serveDocument('archive_day.html');
        } else {
            parent::archive($args);
        }
    }
}
