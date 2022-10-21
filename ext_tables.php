<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {
        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'JVelletti.JvAdd2group',
            'Add2group',
            'LLL:EXT:jv_add2group/Resources/Private/Language/locallang.xlf:add2group.name',
            'jv_add2group-plugin-add2group',
        );

  //      $pluginSignature = str_replace('_', '', 'jv_add2group') . '_add2group';
  //      $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
  //      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:jv_add2group/Configuration/FlexForms/flexform.xml');
  //      \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('jv_add2group', 'Configuration/TypoScript', 'Add Usergroup to Frontenduser');

    }
);
