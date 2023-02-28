<?php
namespace JVelletti\JvAdd2group\Controller;

use TYPO3\CMS\Extbase\Http\ForwardResponse;
use Exception;
use JVelletti\JvAdd2group\Utility\HookUtility;
use JVelletti\JvAdd2group\Utility\MigrationUtility;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

use Psr\Http\Message\ResponseInterface;


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
    public function showAction(): ResponseInterface
    {
        $user = $GLOBALS['TSFE']->fe_user->user ;
        $this->view->assign('user', $user);
        $this->view->assign('groups', $user['usergroup']);
        $this->settings['mayNotHaveGroups'] = GeneralUtility::trimExplode("," , $this->settings['mayNotHaveGroups']  , true ) ;
        $this->settings['mustHaveGroups'] = GeneralUtility::trimExplode("," , $this->settings['mustHaveGroups']  , true ) ;
        $this->view->assign('settings', $this->settings);
        $obj = $this->configurationManager->getContentObject()->data ;

        $this->view->assign('uid', $obj['uid']);
        $this->view->assign('hash', hash( "sha256" , $obj['tstamp'] . "JVE" . MigrationUtility::getUserSessionId() ));
        return $this->htmlResponse();

    }

    /**
     * action addAction
     *
     *
     */
    public function addAction()
    {
        $debug= "" ;
        $obj = $this->configurationManager->getContentObject()->data ;

        $user = $GLOBALS['TSFE']->fe_user->user ;
        if( !is_array($user)) {
            return( new ForwardResponse("show")) ;
        }
        $uid = false ;
        if( $this->request->hasArgument('uid')) {
            $uid = $this->request->getArgument('uid') ;
            $debug .= " |  Got Content Object Uid=" . $uid ;
            if ( $uid != $obj['uid']) {
                $uid = false ;
            }
        }
        if( $uid && $this->request->hasArgument('hash')) {
            $uid = $this->request->getArgument('hash') ;
            $debug .= " |  Got Hash=" . $uid ;
            if ( $uid !=  hash( "sha256" , $obj['tstamp'] . "JVE" .  MigrationUtility::getUserSessionId() ) ) {
                $uid = false ;
                $debug .= " |   Hash NOT VALID !! for tstamp/crdate : " . $obj['tstamp'] . " | " . $user['crdate']  ;
                $this->addFlashMessage("Hash not valid." , null , AbstractMessage::ERROR) ;
            }
        } else {
            $uid = false ;
            $debug .= " |   got no Hash "   ;
            $this->addFlashMessage("Got no hash" , null , AbstractMessage::ERROR) ;
        }
        if( $uid ) {

            $debug .= " |   Add/remove Grups from user uid: "  . (int)$GLOBALS['TSFE']->fe_user->user['uid']   ;
            $debug .= " |   Current Groups: "  . $GLOBALS['TSFE']->fe_user->user['usergroup']  ;
            $debug .= " |   Add Group(s): " . $this->settings['willGetGroups'] ;
            $debug .= " |   Remove Group(s): " . $this->settings['willLooseGroups'] ;
            $debug .= " |   additonal Classes (s): " . $this->settings['classname'] ;
            $hookClassesSettings = [] ;
            $hookClasses = GeneralUtility::trimExplode("," , $this->settings['classnames'] ) ;

            if (is_array( $this->settings['hookClasses'] ) && !empty($hookClasses )) {
                foreach ( $hookClasses as $key ) {
                    if ( isset( $this->settings['hookClasses'][$key])) {
                        $hookClassesSettings[] = $this->settings['hookClasses'][$key]  ;
                    }
                }
            }
            $feuser = $this->updateUserGroupField(trim($this->settings['willGetGroups']) ,trim($this->settings['willLooseGroups'] ) , $hookClassesSettings );

            $msg = trim( $this->settings['successMsg']) ;
            if ($feuser) {

                if(MigrationUtility::greaterVersion(10)) {
                    // toDo restart the session in New Style

                } else {
                    $GLOBALS['TSFE']->__set('loginUser', 1);
                    $GLOBALS['TSFE']->fe_user->start();
                    $GLOBALS["TSFE"]->fe_user->createUserSession($feuser);
                    $GLOBALS["TSFE"]->fe_user->loginSessionStarted = TRUE;
                }


                if( strlen( $msg ) > 1  ) {
                    $this->addFlashMessage($msg , null , AbstractMessage::OK ) ;
                }
            } else {
                $debug .= " |    user had Groups already  "    ;
                if( strlen( $msg ) > 1  && isset( $this->settings['nothingToDoMessage'] ) && !empty( $this->settings['nothingToDoMessage']) ) {
                    $this->addFlashMessage($this->settings['nothingToDoMessage'] , null , AbstractMessage::ERROR) ;
                }

            }

            if( $this->settings['debug'] == 1 ) {
                if(MigrationUtility::greaterVersion(10)) {
                    $this->getFlashMessageQueue()->getAllMessagesAndFlush(AbstractMessage::OK) ;
                } else {
                    // @extensionScannerIgnoreLine
                    $this->controllerContext->getFlashMessageQueue()->getAllMessagesAndFlush(AbstractMessage::OK) ;
                }

                $this->addFlashMessage( "Debug: " .   $debug  , "debug" , AbstractMessage::INFO , true) ;
            }
            $this->redirect("show" , null, null , array("hash" => $user['tstamp'] ) ) ;
            return( new ForwardResponse("show"))
                ->withArguments( array("hash" => $user['tstamp'] )
                ) ;
        } else {
            return( new ForwardResponse("show")) ;
        }

    }

    private function updateUserGroupField( $addGroups , $removeGroups , $HookClasses ) {
        $user = $GLOBALS['TSFE']->fe_user->user ;
        $uid = (int)$GLOBALS['TSFE']->fe_user->user['uid'] ;
        $oldGroups = $GLOBALS['TSFE']->fe_user->user['usergroup'] ;
        $oldGroups = MigrationUtility::uniqueList($oldGroups) ;
        $newGroups = MigrationUtility::uniqueList($oldGroups . "," . $addGroups) ;
        $removeGroupsArray= GeneralUtility::trimExplode("," , $removeGroups , true) ;
        if($removeGroupsArray) {
            foreach ( $removeGroupsArray as $group ) {
                $newGroups = MigrationUtility::rmFromList($group ,$newGroups) ;
            }
        }

        $user['usergroup'] = $newGroups ;
        if( !empty( $HookClasses)) {
            foreach ( $HookClasses as $hookClass ) {
                // in case of errors, do not continue
                if( HookUtility::main($hookClass , $user ) ) {
                   return false ;
                }
            }
        }
        if( $newGroups == $oldGroups ) {
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

        $queryBuilder->executeStatement() ;


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
