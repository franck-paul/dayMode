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
$this->registerModule(
    'dayMode',
    'Provides daily archives and an associated calendar widget',
    'Pep and contributors',
    '6.3',
    [
        'date'     => '2025-08-05T18:22:01+0200',
        'requires' => [
            ['core', '2.34'],
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
