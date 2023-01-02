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
if (!defined('DC_RC_PATH')) {
    return;
}

require_once __DIR__ . '/_widgets.php';

if (!dcCore::app()->blog->settings->daymode->daymode_active) {
    return;
}

dcCore::app()->addBehaviors([
    'templateBeforeBlockV2'    => [dayModePublicBehaviors::class, 'block'],
    'publicBeforeDocumentV2'   => [dayModePublicBehaviors::class, 'addTplPath'],
    'publicHeadContent'        => [dayModePublicBehaviors::class, 'publicHeadContent'],
    'publicBreadcrumb'         => [dayModePublicBehaviors::class, 'publicBreadcrumb'],
    'publicBreadcrumbExtended' => [dayModePublicBehaviors::class, 'publicBreadcrumbExtended'],
]);

/*
 * Overloads some Archives* dedicated template tags
 */
dcCore::app()->tpl->addValue('ArchiveURL', [dayModeTpl::class, 'ArchiveURL']);
dcCore::app()->tpl->addBlock('ArchivesHeader', [dayModeTpl::class, 'ArchivesHeader']);
dcCore::app()->tpl->addBlock('ArchivesFooter', [dayModeTpl::class, 'ArchivesFooter']);
dcCore::app()->tpl->addValue('ArchiveDate', [dayModeTpl::class, 'ArchiveDate']);
dcCore::app()->tpl->addBlock('ArchiveNext', [dayModeTpl::class, 'ArchiveNext']);
dcCore::app()->tpl->addBlock('ArchivePrevious', [dayModeTpl::class, 'ArchivePrevious']);

/*
 * Redefines 'archive' urlHandler to plug the new day mode
 */
dcCore::app()->url->register('archive', 'archive', '^archive(/.+)?$', [dayModeUrl::class, 'archive']);
