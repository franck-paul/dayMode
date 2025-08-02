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
use Dotclear\Helper\Date;

class Calendar
{
    public const SUNDAY_TS = 1_042_329_600;

    /**
     * @var array<string, mixed>
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
        if (App::frontend()->context()->exists('day')) {
            $month      = App::frontend()->context()->day->month();
            $year       = App::frontend()->context()->day->year();
            $this->cday = (int) App::frontend()->context()->day->day();
        } elseif (App::frontend()->context()->exists('archives')) {
            $month = App::frontend()->context()->archives->month();
            $year  = App::frontend()->context()->archives->year();
        } else {
            $recent = CoreHelper::getEarlierDate(['post_type' => $this->post_type]);
            if ($recent->count() > 0) {
                $month = $recent->month();
                $year  = $recent->year();
            }
        }

        $month_dates = App::blog()->getDates([
            'month'     => $month,
            'year'      => $year,
            'post_type' => $this->post_type,
        ]);

        $this->dts = [];
        while ($month_dates->fetch()) {
            $this->dts[] = $month_dates->dt;
        }

        $this->base = [
            'dt'    => date('Y-m-01 00:00:00', (int) strtotime((string) $month_dates->dt)),
            'url'   => $month_dates->url(),
            'month' => $month,
            'year'  => $year,
        ];

        $this->base['ts'] = strtotime($this->base['dt']);
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
            $link_next = ' <a href="' . $l_next->url() . '" title="' .
            Date::str('%B %Y', $l_next->ts()) . '">&nbsp;&#187;&nbsp;</a>';
        }

        $l_prev = App::blog()->getDates([
            'previous'  => $this->base['dt'],
            'type'      => 'month',
            'post_type' => $this->post_type,
        ]);
        if (!$l_prev->isEmpty()) {
            $link_prev = '<a href="' . $l_prev->url() . '" title="' .
            Date::str('%B %Y', $l_prev->ts()) . '">&nbsp;&#171;&nbsp;</a> ';
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
            if ($i % 7 == 0) {
                $res .= '<tr>';
            }

            if ($i == $first) {
                $dstart = true;
            }

            if ($dstart && !checkdate((int) $m, $d, (int) $y)) {
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

            ++$i;
            if ($dstart) {
                ++$d;
            }
        }

        return $res . '</tbody></table>';
    }
}
