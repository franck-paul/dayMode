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

use ArrayObject;
use Dotclear\App;
use Dotclear\Plugin\TemplateHelper\Code;

class FrontendTemplate
{
    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     */
    public static function ArchivesHeader(array|ArrayObject $attr, string $content): string
    {
        $attr = $attr instanceof ArrayObject ? $attr : new ArrayObject($attr);

        return Code::getPHPTemplateBlockCode(
            FrontendTemplateCode::ArchivesHeader(...),
            [
                App::frontend()->context()->exists('day') ? 'day' : 'archives',
            ],
            $content,
            $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     */
    public static function ArchivesFooter(array|ArrayObject $attr, string $content): string
    {
        $attr = $attr instanceof ArrayObject ? $attr : new ArrayObject($attr);

        return Code::getPHPTemplateBlockCode(
            FrontendTemplateCode::ArchivesFooter(...),
            [
                App::frontend()->context()->exists('day') ? 'day' : 'archives',
            ],
            $content,
            $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function ArchiveDate(array|ArrayObject $attr): string
    {
        $attr = $attr instanceof ArrayObject ? $attr : new ArrayObject($attr);

        $format = '';

        if (!empty($attr['format']) && is_string($attr['format'])) {
            // Use given format
            $format = addslashes($attr['format']);
        } elseif (App::frontend()->context()->exists('day')) {
            // Use blog settings date format
            $format = is_string($format = App::blog()->settings()->system->date_format) ? $format : '%F';
        }

        if ($format === '') {
            // Use default format depending on context
            $format = App::frontend()->context()->exists('day') ? '%Y-%m-%d' : '%B %Y';
        }

        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::ArchiveDate(...),
            [
                App::frontend()->context()->exists('day') ? 'day' : 'archives',
                $format,
            ],
            attr: $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function ArchiveEntriesCount(array|ArrayObject $attr): string
    {
        $attr = $attr instanceof ArrayObject ? $attr : new ArrayObject($attr);

        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::ArchiveEntriesCount(...),
            [
                App::frontend()->context()->exists('day') ? 'day' : 'archives',
            ],
            attr: $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     */
    public static function ArchiveNext(array|ArrayObject $attr, string $content): string
    {
        $attr = $attr instanceof ArrayObject ? $attr : new ArrayObject($attr);

        return Code::getPHPTemplateBlockCode(
            FrontendTemplateCode::ArchiveNext(...),
            [
                App::frontend()->context()->exists('day') ? 'day' : 'archives',
                isset($attr['type'])      && is_string($attr['type']) ? addslashes($attr['type']) : (App::frontend()->context()->exists('day') ? 'day' : 'month'),
                isset($attr['post_type']) && is_string($attr['post_type']) ? addslashes($attr['post_type']) : 'post',
            ],
            $content,
            $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     */
    public static function ArchivePrevious(array|ArrayObject $attr, string $content): string
    {
        $attr = $attr instanceof ArrayObject ? $attr : new ArrayObject($attr);

        return Code::getPHPTemplateBlockCode(
            FrontendTemplateCode::ArchivePrevious(...),
            [
                App::frontend()->context()->exists('day') ? 'day' : 'archives',
                isset($attr['type'])      && is_string($attr['type']) ? addslashes($attr['type']) : (App::frontend()->context()->exists('day') ? 'day' : 'month'),
                isset($attr['post_type']) && is_string($attr['post_type']) ? addslashes($attr['post_type']) : 'post',
            ],
            $content,
            $attr,
        );
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     */
    public static function ArchiveURL(array|ArrayObject $attr): string
    {
        $attr = $attr instanceof ArrayObject ? $attr : new ArrayObject($attr);

        return Code::getPHPTemplateValueCode(
            FrontendTemplateCode::ArchiveURL(...),
            attr: $attr,
        );
    }
}
