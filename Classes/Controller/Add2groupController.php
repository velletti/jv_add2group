<?php
namespace JVE\JvAdd2group\Controller;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/***
 *
 * This file is part of the "jv Add2Group" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019 Joerg Velletti <typo3@velletti.de>
 *
 ***/

/**
 * ConnectorController
 */
class Add2groupController extends ActionController
{

    public function initializeAction()
    {
        parent::initializeAction();
    }
    /**
     * action list
     *
     * @return void
     */
    public function showAction()
    {
        $user = $GLOBALS['TSFE']->fe_user->user ;
        $this->view->assign('user', $user);
        $this->view->assign('groups', $user['usergroup']);
        $this->settings['mayNotHaveGroups'] = GeneralUtility::trimExplode("," , $this->settings['mayNotHaveGroups']  , true ) ;
        $this->settings['mustHaveGroups'] = GeneralUtility::trimExplode("," , $this->settings['mustHaveGroups']  , true ) ;
        $this->view->assign('settings', $this->settings);
        $obj = $this->configurationManager->getContentObject()->data ;

        $this->view->assign('uid', $obj['uid']);
        $this->view->assign('hash', hash( "sha256" , $obj['tstamp'] . $user['tstamp'] ));

    }

    /**
     * action addAction
     *
     * @return void
     */
    public function addAction()
    {
        $obj = $this->configurationManager->getContentObject()->data ;

        $uid = false ;
        if( $this->request->hasArgument('uid')) {
            $uid = $this->request->getArgument('uid') ;
            echo "<hr>Line "  . __LINE__ . " : ". $uid ;

            if ( $uid != $obj['uid']) {
                $uid = false ;
                echo "<hr>Line "  . __LINE__ . " : ". $uid ;
            }
        }
        echo "<hr>Line "  . __LINE__ . " : ". $uid ;
        if( $uid && $this->request->hasArgument('hash')) {
            $uid = $this->request->getArgument('hash') ;
            $user = $GLOBALS['TSFE']->fe_user->user ;
            echo "<hr>Line "  . __LINE__ . " : ". $uid ;
            if ( $uid !=  hash( "sha256" , $obj['tstamp'] . $user['tstamp'] ) ) {
                echo "<hr>Line "  . __LINE__ . " : ". $uid ;
                $uid = false ;
            }
        } else {
            $uid = false ;
        }
        echo "<hr>Line "  . __LINE__ . " : ". $uid ;
        if( $uid ) {
            $feuser = $this->updateUserGroupField(trim($this->settings['willGetGroups']) ,trim($this->settings['willLooseGroups']) );

            $msg = trim( $this->settings['successMsg']) ;

            if ($feuser) {

                $GLOBALS['TSFE']->__set('loginUser', 1);
                $GLOBALS['TSFE']->fe_user->start();
                $GLOBALS["TSFE"]->fe_user->createUserSession($feuser);
                $GLOBALS["TSFE"]->fe_user->loginSessionStarted = TRUE;
                if( strlen( $msg ) > 1  ) {
                    $this->addFlashMessage($msg , null , AbstractMessage::OK ) ;
                }
            } else {
                if( strlen( $msg ) > 1  ) {
                    $this->addFlashMessage("Nothing to do" , null , AbstractMessage::ERROR) ;
                }

            }
            $this->redirect("show" ) ;
        } else {
            $this->forward("show" ) ;
        }

    }

    private function updateUserGroupField( $addGroups , $removeGroups) {
        $user = $GLOBALS['TSFE']->fe_user->user ;
        $uid = (int)$GLOBALS['TSFE']->fe_user->user['uid'] ;
        $oldGroups = $GLOBALS['TSFE']->fe_user->user['usergroup'] ;
        $oldGroups = GeneralUtility::uniqueList($oldGroups) ;
        $newGroups = GeneralUtility::uniqueList($oldGroups . "," . $addGroups) ;
        $removeGroupsArray= GeneralUtility::trimExplode("," , $removeGroups , true) ;
        if($removeGroupsArray) {
            foreach ( $removeGroupsArray as $group ) {
                $newGroups = GeneralUtility::rmFromList($group ,$newGroups) ;
            }
        }

        $user['usergroup'] = $newGroups ;
        if( $newGroups == $oldGroups) {
            return $user ;
        }

        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance( "TYPO3\\CMS\\Core\\Database\\ConnectionPool");
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $connectionPool->getQueryBuilderForTable('fe_users') ;

        /** @var Connection $connection */
        $connection = $connectionPool->getConnectionForTable('fe_users') ;

        $queryBuilder ->update('fe_users')
            ->where( $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid , Connection::PARAM_INT )) )
            ->set('usergroup', $newGroups ) ;

        $queryBuilder->execute() ;

        if ( !$connection->errorInfo() ) {
            return $user;
        } else {
            return false;
        }

    }

    /**

    /**
     * action removeAction
     *
     * @return void
     */
    public function removeAction()
    {
        $this->redirect("show");
    }

}
