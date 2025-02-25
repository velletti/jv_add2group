<?php

namespace JVelletti\JvAdd2group\Utility;

use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

class MigrationUtility
{
    /** get string with user Session ID fro or False
     * @return false|mixed
     */
    public static function getUserSessionId()
    {
        if( self::greaterVersion(9)  ) {
            $cookieName = $GLOBALS['TYPO3_CONF_VARS']['FE']['cookieName'] ;
            if( array_key_exists($cookieName , $_COOKIE)) {
                return $_COOKIE[ $cookieName  ];
            }
            return false ;
        } else {
            // @extensionScannerIgnoreLine
           return $GLOBALS['TSFE']->fe_user->getSessionId() ;
        }
    }
    public static function getUserHash(?RequestInterface $request)
    {
        $feuser = self::getUser($request);
        if ( $feuser) {
            return (string)$feuser['uid'] . (string)$feuser['password'] ;
        }
        return false ;
    }
    public static function getUser(?RequestInterface $request)
    {
        if( self::greaterVersion(10)  ) {
            /** @var \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication $feuser */
            $feuser = $request->getAttribute('frontend.user');
            return ($feuser ? $feuser->user : null) ;
        } else {
            // @extensionScannerIgnoreLine
            return $GLOBALS['TSFE']->fe_user->user ;
        }
    }
    public static function getUserGroups($request)
    {
        if( self::greaterVersion(10)  ) {
            $feuser = $request->getAttribute('frontend.user');
            return ($feuser->user ? $feuser->user['usergroup'] : '' );
        } else {
            // @extensionScannerIgnoreLine
            return $GLOBALS['TSFE']->fe_user->user['usergroup'] ;
        }
    }


    /**
     * Takes comma-separated lists and removes all duplicates
     * If a value in the list is trim(empty), the value is ignored.
     *
     * @param string $in_list Accepts comma-separated lists of values
     * @return string Returns the list without any duplicates of values, space around values are trimmed
     */
    public static function uniqueList( string $in_list) :string
    {
        if( self::greaterVersion(10)  ) {
           return  StringUtility::uniqueList($in_list);
        } else {
            // @extensionScannerIgnoreLine
           return  StringUtility::uniqueList($in_list);
        }
    }


    /**
     * Removes an item from a comma-separated list of items.
     *
     * If $element contains a comma, the behaviour of this method is undefined.
     * Empty elements in the list are preserved.
     *
     * @param string $element Element to remove
     * @param string $list Comma-separated list of items (string)
     * @return string New comma-separated list of items
     */

    public static function rmFromList( string $element , string $list) :string
    {
        $items = explode(',', $list);
        foreach ($items as $k => $v) {
            if ($v == $element) {
                unset($items[$k]);
            }
        }
        return implode(',', $items);
    }


    /** get condition if installed TYPO version is Greater than given argument. usefull for outdated API calls
     * @param int $version
     * @return bool
     */

    public static function greaterVersion(int $version): bool
    {
        /** @var Typo3Version $t3vc */
        $t3vc = GeneralUtility::makeInstance( Typo3Version::class ) ;
        if( $t3vc->getMajorVersion() > $version ) {
            return true ;
        }
        return false;
    }
}