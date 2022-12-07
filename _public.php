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

#-----------------------------------------------------------
# Adds a new template behavior
#-----------------------------------------------------------
dcCore::app()->addBehavior('templateBeforeBlockV2', ['dayModeBehaviors','block']);
dcCore::app()->addBehavior('publicBeforeDocumentV2', ['dayModeBehaviors','addTplPath']);
dcCore::app()->addBehavior('publicHeadContent', ['publicdayMode','publicHeadContent']);

class publicdayMode
{
    public static function publicHeadContent()
    {
        dcCore::app()->blog->settings->addNamespace('daymode');
        if (!(bool) dcCore::app()->blog->settings->daymode->daymode_active) {
            return;
        }

        echo
        dcUtils::cssModuleLoad('dayMode/css/dayMode.css');
    }
}
#-----------------------------------------------------------
# Overloads some Archives* dedicated template tags
#-----------------------------------------------------------
dcCore::app()->tpl->addValue('ArchiveURL', ['dayModeTemplates','ArchiveURL']);
dcCore::app()->tpl->addBlock('ArchivesHeader', ['dayModeTemplates','ArchivesHeader']);
dcCore::app()->tpl->addBlock('ArchivesFooter', ['dayModeTemplates','ArchivesFooter']);
dcCore::app()->tpl->addValue('ArchiveDate', ['dayModeTemplates','ArchiveDate']);
dcCore::app()->tpl->addBlock('ArchiveNext', ['dayModeTemplates','ArchiveNext']);
dcCore::app()->tpl->addBlock('ArchivePrevious', ['dayModeTemplates','ArchivePrevious']);

class dayModeTemplates
{
    /* Archives ------------------------------------------- */
    public static function ArchivesHeader($attr, $content)
    {
        $trg = (dcCore::app()->ctx->exists('day')) ? 'day' : 'archives';

        return
        '<?php if (dcCore::app()->ctx->' . $trg . '->isStart()) : ?>' .
        $content .
        '<?php endif; ?>';
    }

    public static function ArchivesFooter($attr, $content)
    {
        $trg = (dcCore::app()->ctx->exists('day')) ? 'day' : 'archives';

        return
        '<?php if (dcCore::app()->ctx->' . $trg . '->isEnd()) : ?>' .
        $content .
        '<?php endif; ?>';
    }

    public static function ArchiveDate($attr)
    {
        if (dcCore::app()->ctx->exists('day')) {
            $trg    = 'day';
            $format = dcCore::app()->blog->settings->system->date_format;
        } else {
            $trg    = 'archives';
            $format = '%B %Y';
        }
        if (!empty($attr['format'])) {
            $format = addslashes($attr['format']);
        }

        $f = dcCore::app()->tpl->getFilters($attr);

        return '<?php echo ' . sprintf($f, "dt::dt2str('" . $format . "', dcCore::app()->ctx->" . $trg . '->dt)') . '; ?>';
    }

    public static function ArchiveEntriesCount($attr)
    {
        $f   = dcCore::app()->tpl->getFilters($attr);
        $trg = (dcCore::app()->ctx->exists('day')) ? 'day' : 'archives';

        return '<?php echo ' . sprintf($f, 'dcCore::app()->ctx->' . $trg . '->nb_post') . '; ?>';
    }

    public static function ArchiveNext($attr, $content)
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
        $res .= 'dcCore::app()->ctx->' . $trg . ' = dcCore::app()->blog->getDates($params); unset($params);' . "\n";
        $res .= "?>\n";
        $res .= '<?php while (dcCore::app()->ctx->' . $trg . '->fetch()) : ?>' . $content . '<?php endwhile; dcCore::app()->ctx->' . $trg . ' = null; ?>';

        return $res;
    }

    public static function ArchivePrevious($attr, $content)
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
        $res .= 'dcCore::app()->ctx->' . $trg . ' = dcCore::app()->blog->getDates($params); unset($params);' . "\n";
        $res .= "?>\n";
        $res .= '<?php while (dcCore::app()->ctx->' . $trg . '->fetch()) : ?>' . $content . '<?php endwhile; dcCore::app()->ctx->' . $trg . ' = null; ?>';

        return $res;
    }

    public static function ArchiveURL($attr)
    {
        $f = dcCore::app()->tpl->getFilters($attr);

        return
        '<?php if (dcCore::app()->ctx->exists("day")) { ' .
        'echo ' . sprintf($f, 'dcCore::app()->ctx->day->url(dcCore::app())') . '; echo "/".dcCore::app()->ctx->day->day(); } ' .
        'else { echo ' . sprintf($f, 'dcCore::app()->ctx->archives->url(dcCore::app())') . '; } ?>';
    }
}

#-----------------------------------------------------------
# Redefines 'archive' urlHandler to plug the new day mode
#-----------------------------------------------------------
dcCore::app()->url->register('archive', 'archive', '^archive(/.+)?$', ['dayModeUrlHandlers','archive']);

class dayModeUrlHandlers extends dcUrlHandlers
{
    public static function archive(?string $args): void
    {
        if (preg_match('|^/([0-9]{4})/([0-9]{2})/([0-9]{2})$|', $args, $m)) {
            $params = [
                'year'      => $m[1],
                'month'     => $m[2],
                'day'       => $m[3],
                'post_type' => 'post',
            ];

            dcCore::app()->ctx->day = dcCore::app()->blog->getDates($params);
            if (dcCore::app()->ctx->day->isEmpty()) {
                self::p404();
            }

            self::serveDocument('archive_day.html');
        } else {
            parent::archive($args);
        }
    }
}
