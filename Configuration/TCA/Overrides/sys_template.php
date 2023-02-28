<?php
if (!defined ('TYPO3')) die ('Access denied.');
$_EXTKEY = "jv_add2group" ;
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('jv_add2group','Configuration/TypoScript/', 'Add user To Group');