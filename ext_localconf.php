<?php
defined('TYPO3') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'JvAdd2group',
            'Add2group',
            [
                \JVelletti\JvAdd2group\Controller\Add2groupController::class => 'show,add,remove'
            ],
            // non-cacheable actions
            [
                \JVelletti\JvAdd2group\Controller\Add2groupController::class => 'show,add,remove'
            ],
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
        );

		
    }
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1667555655] = [
    'nodeName' => 'JvAdd2groupCustomLayoutElement',
    'priority' => 40,
    'class' => \JVelletti\JvAdd2group\FormEngine\Element\JvAdd2groupCustomLayoutElement::class,
];

