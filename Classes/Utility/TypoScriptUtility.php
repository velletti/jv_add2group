<?php

namespace JVelletti\JvAdd2group\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class TypoScriptUtility
{
    /**
     * Removes the dots from a typoscript array
     * @return array
     * @author Peter Benke <pbenke@allplan.com>
     */
    public static function getTypoScriptArray($extension='jv_add2group'): array
    {
        $configurationManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
        $typoscript =  $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT, $extension ) ;

        return self::removeDotsFromTypoScriptArray($typoscript) ;
    }

    public static function removeDotsFromTypoScriptArray($array): array
    {
        $newArray = [];
        if(is_array($array)){
            foreach ($array as $key => $val){

                if (is_array($val)) {
                    // Remove last character (dot)
                    if( substr($key, -1, 1) == ".") {
                        $newKey = substr($key, 0, -1);
                    } else {
                        $newKey = $key ;
                    }

                    $newVal = self::removeDotsFromTypoScriptArray($val);
                } else {
                    $newKey = $key;
                    $newVal = $val;
                }
                $newArray[$newKey] = $newVal;
            }
        }
        return $newArray;

    }
}