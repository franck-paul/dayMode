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
use dcCore;
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
        $trg = (dcCore::app()->ctx->exists('day')) ? 'day' : 'archives';

        return
        '<?php if (dcCore::app()->ctx->' . $trg . '->isStart()) : ?>' .
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
        $trg = (dcCore::app()->ctx->exists('day')) ? 'day' : 'archives';

        return
        '<?php if (dcCore::app()->ctx->' . $trg . '->isEnd()) : ?>' .
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
        if (dcCore::app()->ctx->exists('day')) {
            $trg    = 'day';
            $format = App::blog()->settings()->system->date_format;
        } else {
            $trg    = 'archives';
            $format = '%B %Y';
        }
        if (!empty($attr['format'])) {
            $format = addslashes($attr['format']);
        }

        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, Date::class . "::dt2str('" . $format . "', dcCore::app()->ctx->" . $trg . '->dt)') . '; ?>';
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function ArchiveEntriesCount(array|ArrayObject $attr): string
    {
        $f   = dcCore::app()->tpl->getFilters($attr);
        $trg = (dcCore::app()->ctx->exists('day')) ? 'day' : 'archives';

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->' . $trg . '->nb_post') . '; ?>';
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
        $trg = (dcCore::app()->ctx->exists('day')) ? 'day' : 'archives';
        if ($trg == 'day') {
            $p .= '$params[\'type\'] = \'day\';' . "\n";
        } else {
            $p .= '$params[\'type\'] = \'month\';' . "\n";
        }
        if (isset($attr['type'])) {
            $p .= "\$params['type'] = '" . addslashes($attr['type']) . "';\n";
        }

        $p .= "\$params['post_type'] = 'post';\n";
        if (isset($attr['post_type'])) {
            $p .= "\$params['post_type'] = '" . addslashes($attr['post_type']) . "';\n";
        }

        $p .= "\$params['next'] = dcCore::app()->ctx->" . $trg . '->dt;';

        $res = "<?php\n";
        $res .= $p;
        $res .= 'dcCore::app()->ctx->' . $trg . ' = App::blog()->getDates($params); unset($params);' . "\n";
        $res .= "?>\n";
        $res .= '<?php while (dcCore::app()->ctx->' . $trg . '->fetch()) : ?>' . $content . '<?php endwhile; dcCore::app()->ctx->' . $trg . ' = null; ?>';

        return $res;
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
        $trg = (dcCore::app()->ctx->exists('day')) ? 'day' : 'archives';
        if ($trg == 'day') {
            $p .= '$params[\'type\'] = \'day\';' . "\n";
        } else {
            $p .= '$params[\'type\'] = \'month\';' . "\n";
        }
        if (isset($attr['type'])) {
            $p .= "\$params['type'] = '" . addslashes($attr['type']) . "';\n";
        }

        $p .= "\$params['post_type'] = 'post';\n";
        if (isset($attr['post_type'])) {
            $p .= "\$params['post_type'] = '" . addslashes($attr['post_type']) . "';\n";
        }

        $p .= "\$params['previous'] = dcCore::app()->ctx->" . $trg . '->dt;';

        $res = "<?php\n";
        $res .= $p;
        $res .= 'dcCore::app()->ctx->' . $trg . ' = App::blog()->getDates($params); unset($params);' . "\n";
        $res .= "?>\n";
        $res .= '<?php while (dcCore::app()->ctx->' . $trg . '->fetch()) : ?>' . $content . '<?php endwhile; dcCore::app()->ctx->' . $trg . ' = null; ?>';

        return $res;
    }

    /**
     * @param      array<string, mixed>|\ArrayObject<string, mixed>  $attr      The attribute
     *
     * @return     string
     */
    public static function ArchiveURL(array|ArrayObject $attr): string
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return
        '<?php if (dcCore::app()->ctx->exists("day")) { ' .
        'echo ' . sprintf($f, 'dcCore::app()->ctx->day->url(dcCore::app())') . '; echo "/".dcCore::app()->ctx->day->day(); } ' .
        'else { echo ' . sprintf($f, 'dcCore::app()->ctx->archives->url(dcCore::app())') . '; } ?>';
    }
}
