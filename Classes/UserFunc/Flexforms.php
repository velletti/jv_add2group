<?php
/**
 * Created by PhpStorm.
 * User: velletti
 * Date: 21.09.2016
 * Time: 13:39
 */

namespace JVelletti\JvAdd2group\UserFunc;


use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class Flexforms {


	public function ShowClasses( $config ) {


        $configurationManager = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Configuration\ConfigurationManager::class);
        $typoscript =  $configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,'jv_add2group') ;
        if ( !is_array($typoscript )) {
            return [] ;
        }
        $typoscript = $this->removeDotsFromTypoScriptArray($typoscript) ;
        if( !isset($typoscript['plugin']['tx_jvadd2group']['settings']['hookClasses'] ) ) {
            return [] ;
        }
        $classes =  $typoscript['plugin']['tx_jvadd2group']['settings']['hookClasses'] ;


        $config['items'] = [] ;
        foreach ( $classes as $key => $data ) {
            if( isset( $data['fqcn'] ) && ! empty( $data['fqcn'] ) && isset( $data['function'] ) && ! empty( $data['function'] )) {
                $config['items'][] =  array ( 0 => $data['label'] ?? $key, 1 => $key ) ;
            }
        }

        return $config;
    }

        /**
         * Removes the dots from a typoscript array
         * @param $array
         * @return array
         * @author Peter Benke <pbenke@allplan.com>
         */
        private function removeDotsFromTypoScriptArray($array): array
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

                        $newVal = $this->removeDotsFromTypoScriptArray($val);
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