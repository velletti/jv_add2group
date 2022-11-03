<?php

namespace JVelletti\JvAdd2group\Utility;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class HookUtility
{
    /** update a user in Salesforce
     * added as example and will only work in Allplan environment
     * @return false|mixed
     */
    public static function main($hookClass , $user)
    {
        if( !is_array($hookClass ) || !isset(  $hookClass['fqcn'] ) ||  !isset(  $hookClass['function'] ) ) {
            return true;
        }

        if( substr($hookClass['fqcn'], 0, 1) == "\\") {
            $hookClass['fqcn'] = substr( $hookClass['fqcn'], 1, 999 );
        }
        if( substr($hookClass['fqcn'], -7, 7) == "::class") {
            $hookClass['fqcn'] = substr( $hookClass['fqcn'], 0, -7 );
        }
        try {
            if ( class_exists($hookClass['fqcn']  ))  {
                $service = GeneralUtility::makeInstance( $hookClass['fqcn'] ) ;
                $function = trim( $hookClass['function'] ) ;
                if ( method_exists($service , $function )  ) {
                    // must return Boolean success == true or in case of error, that should  stop further steps : false
                    return  $service->$function( $hookClass , $user ) ;
                }
            }

        } catch (Exception $e ) {
            // echo $e->getMessage() ;
        }

        return false;
    }

}