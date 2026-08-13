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
declare(strict_types=1);

if (isset($this) && is_object($this) && method_exists($this, 'registerModule') && isset($this->id) && is_string($this->id)) {
    $this->registerModule(
        'dayMode',
        'Provides daily archives and an associated calendar widget',
        'Pep and contributors',
        '8.0',
        [
            'date'     => '2026-08-03T09:50:24+0200',
            'requires' => [
                ['core', '2.39'],
                ['TemplateHelper'],
            ],
            'permissions' => 'My',
            'type'        => 'plugin',
            'settings'    => [
                'blog' => '#params.daymode',
            ],

            'details'    => 'http://plugins.dotaddict.org/dc2/details/dayMode',
            'support'    => 'https://github.com/franck-paul/dayMode',
            'repository' => 'https://raw.githubusercontent.com/franck-paul/dayMode/main/dcstore.xml',
            'license'    => 'gpl2',
        ]
    );
}
