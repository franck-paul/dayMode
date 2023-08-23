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
use Dotclear\Core\Process;

class Frontend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::FRONTEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        $settings = My::settings();
        if (!(bool) $settings->daymode_active) {
            return false;
        }

        dcCore::app()->addBehaviors([
            'templateBeforeBlockV2'    => FrontendBehaviors::block(...),
            'publicBeforeDocumentV2'   => FrontendBehaviors::addTplPath(...),
            'publicHeadContent'        => FrontendBehaviors::publicHeadContent(...),
            'publicBreadcrumb'         => FrontendBehaviors::publicBreadcrumb(...),
            'publicBreadcrumbExtended' => FrontendBehaviors::publicBreadcrumbExtended(...),

            'initWidgets' => Widgets::initWidgets(...),
        ]);

        dcCore::app()->tpl->addValue('ArchiveURL', FrontendTemplate::ArchiveURL(...));
        dcCore::app()->tpl->addBlock('ArchivesHeader', FrontendTemplate::ArchivesHeader(...));
        dcCore::app()->tpl->addBlock('ArchivesFooter', FrontendTemplate::ArchivesFooter(...));
        dcCore::app()->tpl->addValue('ArchiveDate', FrontendTemplate::ArchiveDate(...));
        dcCore::app()->tpl->addBlock('ArchiveNext', FrontendTemplate::ArchiveNext(...));
        dcCore::app()->tpl->addBlock('ArchivePrevious', FrontendTemplate::ArchivePrevious(...));

        return true;
    }
}
