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
use Dotclear\App;
use Dotclear\Database\MetaRecord;

class CoreHelper
{
    /**
     * Gets the earlier date.
     *
     * @param      array<string, string>       $params  The parameters
     *
     * @return     MetaRecord
     */
    public static function getEarlierDate(array $params = []): MetaRecord
    {
        $catReq = '';
        if (isset($params['ts_type']) && $params['ts_type'] == 'day') {
            $dt_f = '%Y-%m-%d 00:00:00';
        } else {
            $dt_f = '%Y-%m-%d %H:%M:%S';
        }

        if (!empty($params['cat_id'])) {
            $catReq = 'AND P.cat_id = ' . (int) $params['cat_id'] . ' ';
        } elseif (!empty($params['cat_url'])) {
            $catReq = "AND C.cat_url = '" . App::con()->escapeStr($params['cat_url']) . "' ";
        }

        $strReq = 'SELECT DISTINCT(' . App::con()->dateFormat('MAX(post_dt)', $dt_f) . ') AS dt ' .
                'FROM ' . App::con()->prefix() . 'post P LEFT JOIN ' .
                App::con()->prefix() . 'category C ' .
                'ON P.cat_id = C.cat_id ' .
                "WHERE P.blog_id = '" . App::con()->escapeStr(App::blog()->id()) . "' " .
                $catReq;

        if (!dcCore::app()->auth->check('contentadmin', App::blog()->id())) {
            $strReq .= 'AND ((post_status = 1 ';

            if (App::blog()->withoutPassword()) {
                $strReq .= 'AND post_password IS NULL ';
            }
            $strReq .= ') ';

            if (dcCore::app()->auth->userID()) {
                $strReq .= "OR P.user_id = '" . App::con()->escapeStr(dcCore::app()->auth->userID()) . "')";
            } else {
                $strReq .= ') ';
            }
        }

        if (!empty($params['post_type'])) {
            $strReq .= "AND post_type = '" . App::con()->escapeStr($params['post_type']) . "' ";
        }

        if (!empty($params['cat_id'])) {
            $strReq .= 'AND P.cat_id = ' . (int) $params['cat_id'] . ' ';
        }

        if (!empty($params['cat_url'])) {
            $strReq .= "AND C.cat_url = '" . App::con()->escapeStr($params['cat_url']) . "' ";
        }

        $rs = new MetaRecord(App::con()->select($strReq));
        $rs->extend('rsExtDates');

        return $rs;
    }
}
