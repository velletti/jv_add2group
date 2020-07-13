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
    }

    /**
     * action addAction
     *
     * @return void
     */
    public function addAction()
    {
        $feuser = $this->updateUserGroupField(trim($this->settings['willGetGroups']));
        $msg = trim( $this->settings['successMsg']) ;

        if ($feuser) {

            // Get the domain to be used for the cookie (if any):
           //  $cookieDomain = $this->getCookieDomain();
            // always use the base path:
           // $cookiePath = '/';
            $GLOBALS['TSFE']->__set('loginUser', 1);
            $GLOBALS['TSFE']->fe_user->start();
            $GLOBALS["TSFE"]->fe_user->createUserSession($feuser);
            $GLOBALS["TSFE"]->fe_user->loginSessionStarted = TRUE;
            $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
            if( $msg ) {
                $this->addFlashMessage($msg , null , \TYPO3\CMS\Core\Messaging\AbstractMessage::OK , false) ;
            }
        } else {
            $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush();
            if( $msg) {
                $this->addFlashMessage("Nothing to do" , null , \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR) ;
            }
        }
        $this->redirect("show" ) ;
    }

    private function updateUserGroupField( $group ) {
        $user = $GLOBALS['TSFE']->fe_user->user ;
        $uid = (int)$GLOBALS['TSFE']->fe_user->user['uid'] ;
        $oldGroups = $GLOBALS['TSFE']->fe_user->user['usergroup'] ;
        $oldGroups = GeneralUtility::uniqueList($oldGroups) ;
        $newGroups = GeneralUtility::uniqueList($oldGroups . "," . $group) ;
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
