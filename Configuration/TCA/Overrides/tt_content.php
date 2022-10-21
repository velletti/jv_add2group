<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

if (!defined ('TYPO3_MODE')) die ('Access denied.');
$_EXTKEY = "jv_add2group" ;

/*
ExtensionManagementUtility::addPlugin(
    Array('LLL:EXT:jv_add2group/Resources/Private/Language/locallang.xlf:add2group.name',
    'jv_add2group') ,
    'list_type' ,
    'jv_add2group'

);
*/

// BOTH Lines are needed to see the Flexform in Backend !!1
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['jvadd2group_add2group'] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue('jvadd2group_add2group', 'FILE:EXT:jv_add2group/Configuration/FlexForms/flexform.xml');
