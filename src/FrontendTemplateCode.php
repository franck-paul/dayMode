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

class FrontendTemplateCode
{
    /**
     * PHP code for tpl:ArchivesHeader block
     */
    public static function ArchivesHeader(
        string $_trg_HTML,
        string $_content_HTML
    ): void {
        if (App::frontend()->context()->$_trg_HTML->isStart()) : ?>
            $_content_HTML
        <?php endif;
    }

    /**
     * PHP code for tpl:ArchivesFooter block
     */
    public static function ArchivesFooter(
        string $_trg_HTML,
        string $_content_HTML
    ): void {
        if (App::frontend()->context()->$_trg_HTML->isEnd()) : ?>
            $_content_HTML
        <?php endif;
    }

    /**
     * PHP code for tpl:ArchiveDate value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function ArchiveDate(
        string $_trg_HTML,
        string $_format_,
        array $_params_,
        string $_tag_
    ): void {
        echo App::frontend()->context()::global_filters(
            \Dotclear\Helper\Date::dt2str($_format_, App::frontend()->context()->$_trg_HTML->dt),
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:ArchiveEntriesCount value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function ArchiveEntriesCount(
        string $_trg_HTML,
        array $_params_,
        string $_tag_
    ): void {
        echo App::frontend()->context()::global_filters(
            (string) App::frontend()->context()->$_trg_HTML->nb_post,
            $_params_,
            $_tag_
        );
    }

    /**
     * PHP code for tpl:ArchiveNext block
     */
    public static function ArchiveNext(
        string $_trg_HTML,
        string $_type_,
        string $_post_type_,
        string $_content_HTML
    ): void {
        App::frontend()->context()->$_trg_HTML = App::blog()->getDates([
            'type'      => $_type_,
            'post_type' => $_post_type_,
            'next'      => App::frontend()->context()->$_trg_HTML->dt,
        ]);
        while (App::frontend()->context()->$_trg_HTML->fetch()) : ?>
            $_content_HTML
        <?php endwhile;
        App::frontend()->context()->$_trg_HTML = null;
    }

    /**
     * PHP code for tpl:ArchivePrevious block
     */
    public static function ArchivePrevious(
        string $_trg_HTML,
        string $_type_,
        string $_post_type_,
        string $_content_HTML
    ): void {
        App::frontend()->context()->$_trg_HTML = App::blog()->getDates([
            'type'      => $_type_,
            'post_type' => $_post_type_,
            'previous'  => App::frontend()->context()->$_trg_HTML->dt,
        ]);
        while (App::frontend()->context()->$_trg_HTML->fetch()) : ?>
            $_content_HTML
        <?php endwhile;
        App::frontend()->context()->$_trg_HTML = null;
    }

    /**
     * PHP code for tpl:ArchiveURL value
     *
     * @param      array<int|string, mixed>     $_params_  The parameters
     */
    public static function ArchiveURL(
        array $_params_,
        string $_tag_
    ): void {
        echo App::frontend()->context()::global_filters(
            App::frontend()->context()->exists('day') ?
            App::frontend()->context()->day->url() . '/' . App::frontend()->context()->day->day() :
            App::frontend()->context()->archives->url(),
            $_params_,
            $_tag_
        );
    }
}
