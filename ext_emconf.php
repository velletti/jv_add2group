<?php


$EM_CONF[$_EXTKEY] = [
    'title' => 'Add or Remove Usergroup to Frontend user',
    'description' => 'Show Text with Button. After user Accepts, add user to FE group or remove him / her from group',
    'category' => 'plugin',
    'author' => 'Joerg Velletti',
    'author_email' => 'typo3@velletti.de',
    'state' => 'beta',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '10.4.9',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-10.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
