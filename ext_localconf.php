<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'JVE.JvAdd2group',
            'Add2group',
            [
                'Add2group' => 'show,add,remove'
            ],
            // non-cacheable actions
            [
                'Connector' => 'show,add,remove'
            ]
        );

        // wizards
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            'mod {
                wizards.newContentElement.wizardItems.plugins {
                    elements {
                        connector {
                            iconIdentifier = jv_add2group-plugin-add2group
                            title = LLL:EXT:jv_add2group/Resources/Private/Language/locallang.xlf:add2group.name
                            description = LLL:EXT:jv_banners/Resources/Private/Language/locallang.xlf:add2group.description
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

