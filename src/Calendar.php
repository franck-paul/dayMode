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
use Dotclear\Database\MetaRecord;
use Dotclear\Helper\Date;

class Calendar
{
    public const SUNDAY_TS = 1_042_329_600;

    /**
     * @var array{dt: string, url: string, month: string, year: string, ts: int}
     */
    protected array $base;

    /**
     * @var array<string>
     */
    protected array $dts;

    protected int $cday = 0;

    public int $weekstart = 0;

    public function __construct(
        protected string $post_type = 'post'
    ) {
        $year  = '';
        $month = '';
        if (App::frontend()->context()->exists('day') && App::frontend()->context()->day instanceof MetaRecord) {
            $month      = is_string($month = App::frontend()->context()->day->month()) ? $month : '';
            $year       = is_string($year = App::frontend()->context()->day->year()) ? $year : '';
            $day        = is_numeric($day = App::frontend()->context()->day->day()) ? (int) $day : 0;
            $this->cday = $day;
        } elseif (App::frontend()->context()->exists('archives') && App::frontend()->context()->archives instanceof MetaRecord) {
            $month = is_string($month = App::frontend()->context()->archives->month()) ? $month : '';
            $year  = is_string($year = App::frontend()->context()->archives->year()) ? $year : '';
        } else {
            $recent = CoreHelper::getEarlierDate(['post_type' => $this->post_type]);
            if ($recent->count() > 0) {
                $month = is_string($month = $recent->month()) ? $month : '';
                $year  = is_string($year = $recent->year()) ? $year : '';
            }
        }

        $month_dates = App::blog()->getDates([
            'month'     => $month,
            'year'      => $year,
            'post_type' => $this->post_type,
        ]);

        $this->dts = [];
        $dt        = '';
        while ($month_dates->fetch()) {
            $dt = is_string($dt = $month_dates->dt) ? $dt : '';
            if ($dt !== '') {
                $this->dts[] = $dt;
            }
        }

        $url        = is_string($url = $month_dates->url()) ? $url : '';
        $time       = (int) strtotime($dt);
        $this->base = [
            'dt'    => date('Y-m-01 00:00:00', $time),
            'url'   => $url,
            'month' => $month,
            'year'  => $year,
            'ts'    => $time,
        ];
    }

    public function draw(): string
    {
        $link_next = '';
        $link_prev = '';
        $l_next    = App::blog()->getDates([
            'next'      => $this->base['dt'],
            'type'      => 'month',
            'post_type' => $this->post_type,
        ]);
        if (!$l_next->isEmpty()) {
            $url = is_string($url = $l_next->url()) ? $url : '';
            $ts  = is_numeric($ts = $l_next->ts()) ? (int) $ts : null;
            if ($url !== '' && $ts !== null) {
                $link_next = ' <a href="' . $url . '" title="' . Date::str('%B %Y', $ts) . '">&nbsp;&#187;&nbsp;</a>';
            }
        }

        $l_prev = App::blog()->getDates([
            'previous'  => $this->base['dt'],
            'type'      => 'month',
            'post_type' => $this->post_type,
        ]);
        if (!$l_prev->isEmpty()) {
            $url = is_string($url = $l_prev->url()) ? $url : '';
            $ts  = is_numeric($ts = $l_prev->ts()) ? (int) $ts : null;
            if ($url !== '' && $ts !== null) {
                $link_prev = '<a href="' . $url . '" title="' . Date::str('%B %Y', $ts) . '">&nbsp;&#171;&nbsp;</a> ';
            }
        }

        $res = '<table><caption>' .
        $link_prev .
        Date::str('%B %Y', $this->base['ts']) .
        $link_next .
        '</caption>';

        $first_ts = self::SUNDAY_TS + ($this->weekstart * 86400);
        $last_ts  = $first_ts       + (6 * 86400);
        $first    = date('w', $this->base['ts']);
        $first    = ($first == 0) ? 7 : $first;
        $first -= $this->weekstart;
        $limit = date('t', $this->base['ts']);

        $res .= '<thead><tr>';
        for ($j = $first_ts; $j <= $last_ts; $j += 86400) {
            $res .= '<th scope="col"><abbr title="' . Date::str('%A', $j) . '">' .
                Date::str('%a', $j) . '</abbr></th>';
        }

        $res .= '</tr></thead><tbody>';
        $d      = 1;
        $i      = 0;
        $dstart = false;
        $y      = $this->base['year'];
        $m      = $this->base['month'];

        while ($i < 42) {
            if ($i % 7 === 0) {
                $res .= '<tr>';
            }

            if ($i == $first) {
                $dstart = true;
            }

            if ($dstart && !checkdate((int) $m, $d, (int) $y)) {
                $dstart = false;
            }

            if (in_array(sprintf('%4d-%02d-%02d 00:00:00', (int) $y, (int) $m, $d), $this->dts)) {
                $url  = $this->base['url'] . '/' . sprintf('%02d', $d);
                $link = '<a href="' . $url . '">%s</a>';
            } else {
                $link = '%s';
            }

            $class = ($this->cday === $d && $dstart) ? ' class="active"' : '';

            $res .= '<td' . $class . '>';
            $res .= ($dstart) ? sprintf($link, $d) : ' ';
            $res .= '</td>';

            if (($i + 1) % 7 === 0) {
                $res .= '</tr>';
                if ($d >= $limit) {
                    $i = 42;
                }
            }

            ++$i;
            if ($dstart) {
                ++$d;
            }
        }

        return $res . '</tbody></table>';
    }
}
