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
use dcNsProcess;

class Frontend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = My::checkContext(My::FRONTEND);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        $settings = dcCore::app()->blog->settings->get(My::id());
        if (!(bool) $settings->daymode_active) {
            return false;
        }

        dcCore::app()->addBehaviors([
            'templateBeforeBlockV2'    => [FrontendBehaviors::class, 'block'],
            'publicBeforeDocumentV2'   => [FrontendBehaviors::class, 'addTplPath'],
            'publicHeadContent'        => [FrontendBehaviors::class, 'publicHeadContent'],
            'publicBreadcrumb'         => [FrontendBehaviors::class, 'publicBreadcrumb'],
            'publicBreadcrumbExtended' => [FrontendBehaviors::class, 'publicBreadcrumbExtended'],

            'initWidgets' => [Widgets::class, 'initWidgets'],
        ]);

        dcCore::app()->tpl->addValue('ArchiveURL', [FrontendTemplate::class, 'ArchiveURL']);
        dcCore::app()->tpl->addBlock('ArchivesHeader', [FrontendTemplate::class, 'ArchivesHeader']);
        dcCore::app()->tpl->addBlock('ArchivesFooter', [FrontendTemplate::class, 'ArchivesFooter']);
        dcCore::app()->tpl->addValue('ArchiveDate', [FrontendTemplate::class, 'ArchiveDate']);
        dcCore::app()->tpl->addBlock('ArchiveNext', [FrontendTemplate::class, 'ArchiveNext']);
        dcCore::app()->tpl->addBlock('ArchivePrevious', [FrontendTemplate::class, 'ArchivePrevious']);

        return true;
    }
}
