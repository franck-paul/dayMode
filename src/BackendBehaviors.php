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
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Fieldset;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Legend;
use Dotclear\Helper\Html\Form\Para;
use Exception;

class BackendBehaviors
{
    public static function adminBlogPreferencesForm()
    {
        $settings = dcCore::app()->blog->settings->get(My::id());

        echo (new Fieldset('daymode'))
            ->legend(new Legend(__('Daily Archives')))
            ->fields([
                (new Para())->items([
                    (new Checkbox('daymode_active', $settings->daymode_active))
                        ->value(1)
                        ->label((new Label(__('Enable daily archives and calendar'), Label::INSIDE_TEXT_AFTER))),
                ]),
            ])
        ->render();
    }

    public static function adminBeforeBlogSettingsUpdate()
    {
        $settings = dcCore::app()->blog->settings->get(My::id());

        try {
            $settings->put('daymode_active', !empty($_POST['daymode_active']), 'boolean');
        } catch (Exception $e) {
            $settings->drop('daymode_active');
            $settings->put('daymode_active', !empty($_POST['daymode_active']), 'boolean');
        }
    }
}
