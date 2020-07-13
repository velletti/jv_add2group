<?php
/**
 * Created by PhpStorm.
 * User: velletti
 * Date: 21.09.2016
 * Time: 13:39
 */

namespace JVE\JvAdd2group\UserFunc;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class Flexforms {


	/** used in event Model TCA to show the Translations of Tags and Categories, but still work with the uid of the default record
     * does not work in 9.5.15 - 20.4.2020 but there is a closed issue / patch
     * https://forge.typo3.org/issues/85142
     * */
	public function ShowClasses($config) {


        $config['items']['-'] = array("0" => "none") ;

        return $config ;
    }

}