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

        App::behavior()->addBehaviors([
            'templateBeforeBlockV2'    => FrontendBehaviors::templateBeforeBlock(...),
            'publicBeforeDocumentV2'   => FrontendBehaviors::publicBeforeDocument(...),
            'publicHeadContent'        => FrontendBehaviors::publicHeadContent(...),
            'publicBreadcrumb'         => FrontendBehaviors::publicBreadcrumb(...),
            'publicBreadcrumbExtended' => FrontendBehaviors::publicBreadcrumbExtended(...),

            'initWidgets' => Widgets::initWidgets(...),
        ]);

        App::frontend()->template()->addValue('ArchiveURL', FrontendTemplate::ArchiveURL(...));
        App::frontend()->template()->addBlock('ArchivesHeader', FrontendTemplate::ArchivesHeader(...));
        App::frontend()->template()->addBlock('ArchivesFooter', FrontendTemplate::ArchivesFooter(...));
        App::frontend()->template()->addValue('ArchiveDate', FrontendTemplate::ArchiveDate(...));
        App::frontend()->template()->addBlock('ArchiveNext', FrontendTemplate::ArchiveNext(...));
        App::frontend()->template()->addBlock('ArchivePrevious', FrontendTemplate::ArchivePrevious(...));

        return true;
    }
}
