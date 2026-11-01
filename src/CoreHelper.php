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
use Dotclear\Database\Statement\JoinStatement;
use Dotclear\Database\Statement\SelectStatement;
use Dotclear\Schema\Extension\Dates;

class CoreHelper
{
    /**
     * Gets the earlier date.
     *
     * @param      array<string, string>       $params  The parameters
     */
    public static function getEarlierDate(array $params = []): MetaRecord
    {
        $dt_f = isset($params['ts_type']) && $params['ts_type'] === 'day' ? '%Y-%m-%d 00:00:00' : '%Y-%m-%d %H:%M:%S';

        $sql = new SelectStatement();
        $sql
            ->distinct()
            ->column($sql->dateFormat($sql->max('P.post_dt'), $dt_f) . ' AS dt')
            ->from($sql->as(App::db()->con()->prefix() . 'post', 'P'))
            ->join(
                (new JoinStatement())
                    ->left()
                    ->from($sql->as(App::db()->con()->prefix() . 'category', 'C'))
                    ->on('P.cat_id = C.cat_id')
                    ->statement()
            )
            ->where('P.blog_id = ' . $sql->quote(App::blog()->id()));

        if (!empty($params['cat_id'])) {
            $sql->and('P.cat_id = ' . (int) $params['cat_id']);
        }

        if (!empty($params['cat_url'])) {
            $sql->and('C.cat_url = ' . $sql->quote($params['cat_url']));
        }

        if (!App::auth()->check('contentadmin', App::blog()->id())) {
            $statusCondition = 'P.post_status = 1';
            if (App::blog()->withoutPassword()) {
                $statusCondition .= ' AND P.post_password IS NULL';
            }

            $conditions = ['(' . $statusCondition . ')'];
            if (App::auth()->userID() !== '') {
                $conditions[] = 'P.user_id = ' . $sql->quote(App::auth()->userID());
            }

            $sql->and('(' . implode(' OR ', $conditions) . ')');
        }

        if (!empty($params['post_type'])) {
            $sql->and('P.post_type = ' . $sql->quote($params['post_type']));
        }

        $metaRecord = $sql->select() ?? MetaRecord::newFromArray([]);
        $metaRecord->extend(Dates::class);

        return $metaRecord;
    }
}
