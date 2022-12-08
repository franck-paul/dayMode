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
class dcCalendar
{
    public const SUNDAY_TS = 1042329600;

    protected $base      = null;
    protected $dts       = null;
    protected $post_type = 'post';
    protected $cday      = 0;

    public $weekstart = 0;

    public function __construct($post_type = 'post')
    {
        $this->post_type = $post_type;

        $year = $month = '';
        if (dcCore::app()->ctx->exists('day')) {
            $month      = dcCore::app()->ctx->day->month();
            $year       = dcCore::app()->ctx->day->year();
            $this->cday = (int) dcCore::app()->ctx->day->day();
        } elseif (dcCore::app()->ctx->exists('archives')) {
            $month = dcCore::app()->ctx->archives->month();
            $year  = dcCore::app()->ctx->archives->year();
        } else {
            $recent = dcDayTools::getEarlierDate(['post_type' => $this->post_type]);
            $month  = $recent->month();
            $year   = $recent->year();
        }

        $month_dates = dcCore::app()->blog->getDates([
            'month'     => $month,
            'year'      => $year,
            'post_type' => $this->post_type,
        ]);

        $this->dts = [];
        while ($month_dates->fetch()) {
            $this->dts[] = $month_dates->dt;
        }

        $this->base = [
            'dt'    => date('Y-m-01 00:00:00', strtotime($month_dates->dt)),
            'url'   => $month_dates->url(dcCore::app()),
            'month' => $month,
            'year'  => $year,
        ];

        $this->base['ts'] = strtotime($this->base['dt']);
    }

    public function draw()
    {
        $link_next = $link_prev = '';

        $l_next = dcCore::app()->blog->getDates([
            'next'      => $this->base['dt'],
            'type'      => 'month',
            'post_type' => $this->post_type,
        ]);
        if (!$l_next->isEmpty()) {
            $link_next = ' <a href="' . $l_next->url() . '" title="' .
            dt::str('%B %Y', $l_next->ts()) . '">&nbsp;&#187;&nbsp;</a>';
        }

        $l_prev = dcCore::app()->blog->getDates([
            'previous'  => $this->base['dt'],
            'type'      => 'month',
            'post_type' => $this->post_type,
        ]);
        if (!$l_prev->isEmpty()) {
            $link_prev = '<a href="' . $l_prev->url() . '" title="' .
            dt::str('%B %Y', $l_prev->ts()) . '">&nbsp;&#171;&nbsp;</a> ';
        }

        $res = '<table>' .
        '<caption>' .
        $link_prev .
        dt::str('%B %Y', $this->base['ts']) .
        $link_next .
        '</caption>';

        $first_ts = self::SUNDAY_TS + ((int) $this->weekstart * 86400);
        $last_ts  = $first_ts       + (6 * 86400);
        $first    = date('w', $this->base['ts']);
        $first    = ($first == 0) ? 7 : $first;
        $first    = $first - $this->weekstart;
        $limit    = date('t', $this->base['ts']);

        $res .= '<thead><tr>';
        for ($j = $first_ts; $j <= $last_ts; $j = $j + 86400) {
            $res .= '<th scope="col"><abbr title="' . dt::str('%A', $j) . '">' .
                dt::str('%a', $j) . '</abbr></th>';
        }

        $res .= '</tr></thead><tbody>';
        $d      = 1;
        $i      = 0;
        $dstart = false;
        $y      = $this->base['year'];
        $m      = $this->base['month'];

        while ($i < 42) {
            if ($i % 7 == 0) {
                $res .= '<tr>';
            }
            if ($i == $first) {
                $dstart = true;
            }
            if ($dstart && !checkdate($m, $d, $y)) {
                $dstart = false;
            }
            if (in_array(sprintf('%4d-%02d-%02d 00:00:00', $y, $m, $d), $this->dts)) {
                $url  = $this->base['url'] . '/' . sprintf('%02d', $d);
                $link = '<a href="' . $url . '">%s</a>';
            } else {
                $link = '%s';
            }

            $class = ($this->cday == $d && $dstart) ? ' class="active"' : '';

            $res .= '<td' . $class . '>';
            $res .= ($dstart) ? sprintf($link, $d) : ' ';
            $res .= '</td>';

            if (($i + 1) % 7 == 0) {
                $res .= '</tr>';
                if ($d >= $limit) {
                    $i = 42;
                }
            }
            $i++;
            if ($dstart) {
                $d++;
            }
        }

        $res .= '</tbody></table>';

        return $res;
    }
}

class dcDayTools
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

        $rs = new dcRecord(dcCore::app()->blog->con->select($strReq));
        $rs->extend('rsExtDates');

        return $rs;
    }
}
