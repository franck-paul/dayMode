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
use Dotclear\Database\MetaRecord;

class CoreHelper
{
    public static function getEarlierDate($params = [])
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
            $catReq = "AND C.cat_url = '" . dcCore::app()->blog->con->escape($params['cat_url']) . "' ";
        }

        $strReq = 'SELECT DISTINCT(' . dcCore::app()->blog->con->dateFormat('MAX(post_dt)', $dt_f) . ') AS dt ' .
                'FROM ' . dcCore::app()->blog->prefix . 'post P LEFT JOIN ' .
                dcCore::app()->blog->prefix . 'category C ' .
                'ON P.cat_id = C.cat_id ' .
                "WHERE P.blog_id = '" . dcCore::app()->blog->con->escape(dcCore::app()->blog->id) . "' " .
                $catReq;

        if (!dcCore::app()->auth->check('contentadmin', dcCore::app()->blog->id)) {
            $strReq .= 'AND ((post_status = 1 ';

            if (dcCore::app()->blog->without_password) {
                $strReq .= 'AND post_password IS NULL ';
            }
            $strReq .= ') ';

            if (dcCore::app()->auth->userID()) {
                $strReq .= "OR P.user_id = '" . dcCore::app()->blog->con->escape(dcCore::app()->auth->userID()) . "')";
            } else {
                $strReq .= ') ';
            }
        }

        if (!empty($params['post_type'])) {
            $strReq .= "AND post_type = '" . dcCore::app()->blog->con->escape($params['post_type']) . "' ";
        }

        if (!empty($params['cat_id'])) {
            $strReq .= 'AND P.cat_id = ' . (int) $params['cat_id'] . ' ';
        }

        if (!empty($params['cat_url'])) {
            $strReq .= "AND C.cat_url = '" . dcCore::app()->blog->con->escape($params['cat_url']) . "' ";
        }

        $rs = new MetaRecord(dcCore::app()->blog->con->select($strReq));
        $rs->extend('rsExtDates');

        return $rs;
    }
}
