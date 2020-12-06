<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');
$_EXTKEY = "jv_add2group" ;
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY,'Configuration/TypoScript/', 'Add user To Group');