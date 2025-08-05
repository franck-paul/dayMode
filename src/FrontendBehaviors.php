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

use ArrayObject;
use Dotclear\App;
use Dotclear\Helper\Date;
use Dotclear\Helper\Html\Form\Link;
use Dotclear\Helper\Html\Form\Set;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Plugin\TemplateHelper\Code;

class FrontendBehaviors
{
    public static function publicBeforeDocument(): string
    {
        App::frontend()->template()->appendPath(My::tplPath());

        return '';
    }

    public static function publicHeadContent(): string
    {
        $settings = My::settings();
        if (!(bool) $settings->daymode_active) {
            return '';
        }

        echo
        My::cssLoad('dayMode.css');

        return '';
    }

    public static function publicBreadcrumbExtended(string $context): string
    {
        // Would like to return a boolean value but behaviors management does not allow that yet.
        return $context === 'archive' ? '1' : '';
    }

    public static function publicBreadcrumb(string $context, string $separator): string
    {
        if ($context === 'archive') {
            // Archives
            $parts = [];

            $parts[] = (new Link('bc-home'))
                ->href(App::blog()->url())
                ->text(__('Home'));

            if (!App::frontend()->context()->exists('day')) {
                if (!App::frontend()->context()->archives) {
                    // Global archives
                    $parts[] = (new Text(null, __('Archives')));
                } else {
                    // Month archive
                    $parts[] = (new Link())
                        ->href(App::blog()->url() . App::url()->getURLFor('archive'))
                        ->text(__('Archives'));
                    $parts[] = (new Text(null, Date::dt2str('%B %Y', App::frontend()->context()->archives->dt)));
                }
            } else {
                // Day archive
                $parts[] = (new Link())
                    ->href(App::blog()->url() . App::url()->getURLFor('archive'))
                    ->text(__('Archives'));
                $parts[] = (new Link())
                    ->href(App::blog()->url() . App::url()->getURLFor('archive', Date::dt2str('%Y/%m', App::frontend()->context()->day->dt)))
                    ->text(Date::dt2str('%B %Y', App::frontend()->context()->day->dt));
                $parts[] = (new Text(null, Date::dt2str('%e', App::frontend()->context()->day->dt)));
            }

            return (new Set())
                ->separator($separator)
                ->items($parts)
            ->render();
        }

        return '';
    }

    /**
     * @param      string                                               $block  The block
     * @param      array<string, string>|ArrayObject<string, string>    $attr   The attributes
     */
    public static function templateBeforeBlock(string $block, array|ArrayObject $attr): string
    {
        if ($block === 'Entries') {
            return Code::getPHPCode(
                self::templateBeforeBlockCode(...),
                [
                    isset($attr['today']) && $attr['today'] !== '',
                ]
            );
        }

        return '';
    }

    // Template code methods

    public static function templateBeforeBlockCode(
        bool $_today_
    ): void {
        if ($_today_) {
            $daymode_today        = \Dotclear\Plugin\dayMode\CoreHelper::getEarlierDate(['ts_type' => 'day']);
            $params['post_year']  = $daymode_today->year();
            $params['post_month'] = $daymode_today->month();
            $params['post_day']   = $daymode_today->day();
            $params['limit']      = null;
            unset($params['limit'], $daymode_today);
        } elseif (App::frontend()->context()->exists('day')) {
            $params['post_year']  = App::frontend()->context()->day->year();
            $params['post_month'] = App::frontend()->context()->day->month();
            $params['post_day']   = App::frontend()->context()->day->day();
            $params['limit']      = null;
            unset($params['limit']);
        }
    }
}
