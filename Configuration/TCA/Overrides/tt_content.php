<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined ('TYPO3')) die ('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin('JvAdd2group', 'Add2group', 'LLL:EXT:jv_add2group/Resources/Private/Language/locallang.xlf:add2group.name', 'jv_add2group-plugin-add2group');
ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    'jvadd2group_add2group',
    'after:subheader'
);
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:jv_add2group/Configuration/FlexForms/flexform.xml',
    'jvadd2group_add2group'
);


