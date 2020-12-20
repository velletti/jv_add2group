<?php


$EM_CONF[$_EXTKEY] = [
    'title' => 'Add Usergroup to Frontend user',
    'description' => 'Show Text with Checkbox. After user Accepts, add user to FE group or remove him / her from group',
    'category' => 'plugin',
    'author' => 'Joerg Velletti',
    'author_email' => 'typo3@velletti.de',
    'state' => 'beta',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.3',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-9.5.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
