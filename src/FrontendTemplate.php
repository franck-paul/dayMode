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

class FrontendTemplate
{
    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     *
     * @return     string
     */
    public static function ArchivesHeader(array|ArrayObject $attr, string $content): string
    {
        $trg = (App::frontend()->context()->exists('day')) ? 'day' : 'archives';

        return
        '<?php if (App::frontend()->context()->' . $trg . '->isStart()) : ?>' .
        $content .
        '<?php endif; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     *
     * @return     string
     */
    public static function ArchivesFooter(array|ArrayObject $attr, string $content): string
    {
        $trg = (App::frontend()->context()->exists('day')) ? 'day' : 'archives';

        return
        '<?php if (App::frontend()->context()->' . $trg . '->isEnd()) : ?>' .
        $content .
        '<?php endif; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function ArchiveDate(array|ArrayObject $attr): string
    {
        if (App::frontend()->context()->exists('day')) {
            $trg    = 'day';
            $format = App::blog()->settings()->system->date_format;
        } else {
            $trg    = 'archives';
            $format = '%B %Y';
        }

        if (!empty($attr['format'])) {
            $format = addslashes((string) $attr['format']);
        }

        $f = App::frontend()->template()->getFilters($attr);

        return '<?= ' . sprintf($f, Date::class . "::dt2str('" . $format . "', App::frontend()->context()->" . $trg . '->dt)') . ' ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function ArchiveEntriesCount(array|ArrayObject $attr): string
    {
        $f   = App::frontend()->template()->getFilters($attr);
        $trg = (App::frontend()->context()->exists('day')) ? 'day' : 'archives';

        return '<?= ' . sprintf($f, 'App::frontend()->context()->' . $trg . '->nb_post') . ' ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     *
     * @return     string
     */
    public static function ArchiveNext(array|ArrayObject $attr, string $content): string
    {
        $p   = '$params = array();';
        $trg = (App::frontend()->context()->exists('day')) ? 'day' : 'archives';
        if ($trg == 'day') {
            $p .= '$params[\'type\'] = \'day\';' . "\n";
        } else {
            $p .= '$params[\'type\'] = \'month\';' . "\n";
        }

        if (isset($attr['type'])) {
            $p .= "\$params['type'] = '" . addslashes((string) $attr['type']) . "';\n";
        }

        $p .= "\$params['post_type'] = 'post';\n";
        if (isset($attr['post_type'])) {
            $p .= "\$params['post_type'] = '" . addslashes((string) $attr['post_type']) . "';\n";
        }

        $p .= "\$params['next'] = App::frontend()->context()->" . $trg . '->dt;';

        $res = "<?php\n";
        $res .= $p;
        $res .= 'App::frontend()->context()->' . $trg . ' = App::blog()->getDates($params); unset($params);' . "\n";
        $res .= "?>\n";

        return $res . ('<?php while (App::frontend()->context()->' . $trg . '->fetch()) : ?>' . $content . '<?php endwhile; App::frontend()->context()->' . $trg . ' = null; ?>');
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     * @param      string                                            $content   The content
     *
     * @return     string
     */
    public static function ArchivePrevious(array|ArrayObject $attr, string $content): string
    {
        $p   = '$params = array();';
        $trg = (App::frontend()->context()->exists('day')) ? 'day' : 'archives';
        if ($trg == 'day') {
            $p .= '$params[\'type\'] = \'day\';' . "\n";
        } else {
            $p .= '$params[\'type\'] = \'month\';' . "\n";
        }

        if (isset($attr['type'])) {
            $p .= "\$params['type'] = '" . addslashes((string) $attr['type']) . "';\n";
        }

        $p .= "\$params['post_type'] = 'post';\n";
        if (isset($attr['post_type'])) {
            $p .= "\$params['post_type'] = '" . addslashes((string) $attr['post_type']) . "';\n";
        }

        $p .= "\$params['previous'] = App::frontend()->context()->" . $trg . '->dt;';

        $res = "<?php\n";
        $res .= $p;
        $res .= 'App::frontend()->context()->' . $trg . ' = App::blog()->getDates($params); unset($params);' . "\n";
        $res .= "?>\n";

        return $res . ('<?php while (App::frontend()->context()->' . $trg . '->fetch()) : ?>' . $content . '<?php endwhile; App::frontend()->context()->' . $trg . ' = null; ?>');
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function ArchiveURL(array|ArrayObject $attr): string
    {
        $f = App::frontend()->template()->getFilters($attr);

        return
        '<?php if (App::frontend()->context()->exists("day")) { echo ' . sprintf($f, 'App::frontend()->context()->day->url()') . '; echo "/".App::frontend()->context()->day->day(); } ' .
        'else { echo ' . sprintf($f, 'App::frontend()->context()->archives->url()') . '; } ?>';
    }
}
