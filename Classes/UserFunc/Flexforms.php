<?php
/**
 * Created by PhpStorm.
 * User: velletti
 * Date: 21.09.2016
 * Time: 13:39
 */

namespace JVelletti\JvAdd2group\UserFunc;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class Flexforms {


	public function ShowClasses($config) {


        $config['items']['-'] = array("0" => "none") ;

        return $config ;
    }

}