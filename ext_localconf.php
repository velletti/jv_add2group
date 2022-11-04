<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        if( \JVelletti\JvAdd2group\Utility\MigrationUtility::greaterVersion(10)) {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
                'JVelletti.JvAdd2group',
                'Add2group',
                [
                    \JVelletti\JvAdd2group\Controller\Add2groupController::class => 'show,add,remove'
                ],
                // non-cacheable actions
                [
                    \JVelletti\JvAdd2group\Controller\Add2groupController::class => 'show,add,remove'
                ]
            );
        } else {
            \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
                'JVelletti.JvAdd2group',
                'Add2group',
                [
                    'Add2group' => 'show,add,remove'
                ],
                // non-cacheable actions
                [
                    'Add2group' => 'show,add,remove'
                ]
            );
        }

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        connector {
                            iconIdentifier = jv_add2group-plugin-add2group
                            title = LLL:EXT:jv_add2group/Resources/Private/Language/locallang.xlf:add2group.name
                            description = LLL:EXT:jv_add2group/Resources/Private/Language/locallang.xlf:add2group.description
                            tt_content_defValues {
                                CType = list
                                list_type = jvadd2group_add2group
                            }
                        }
                    }
                    show = *
                }
           }'
        );

		
    }
);

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

$iconRegistry->registerIcon(
    'jv_add2group-plugin-add2group',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:jv_add2group/Resources/Public/Icons/user_plugin.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1667555655] = [
    'nodeName' => 'JvAdd2groupCustomLayoutElement',
    'priority' => 40,
    'class' => \JVelletti\JvAdd2group\FormEngine\Element\JvAdd2groupCustomLayoutElement::class,
];

