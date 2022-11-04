<?php
/**
 * Created by PhpStorm.
 * User: velletti
 * Date: 21.09.2016
 * Time: 13:39
 */

namespace JVelletti\JvAdd2group\UserFunc;


use JVelletti\JvAdd2group\Utility\TypoScriptUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class Flexforms {


	public function ShowClasses( $config ) {



        $typoscript = TypoScriptUtility::getTypoScriptArray('jv_add2group') ;
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

}