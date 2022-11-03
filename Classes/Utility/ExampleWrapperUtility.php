<?php

namespace JVelletti\JvAdd2group\Utility;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class ExampleWrapperUtility
{
    /** update a user somewhere
     * added as example and will not out of the box
     * @return false|mixed
     */
    public static function main($settings , $user)
    {
        if( !is_array($settings ) || !isset(  $settings['fqcn'] ) ||  !isset(  $settings['function'] ) ) {
            return true;
        }

        /*  Notice: this wrapper will only work in a specific TYPO3 Instance that has a specific class
         *  but is can give you an expression how to create your own wrapper class
         */
        $mapping = $settings['mapping'] ;
        $userId = $user['uid'] ;
        $doit = true ;

        // $doit = result of your Code manipulating the user with id $userId
        // in the example: always true

        return $doit;
    }

}