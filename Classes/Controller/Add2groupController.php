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

    public function __construct(private readonly \TYPO3\CMS\Core\Database\ConnectionPool $connectionPool)
    {
    }
    public function initializeAction(): void
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
        $user = MigrationUtility::getUser( $this->request ) ;
        $this->view->assign('user', $user);
        $this->view->assign('groups', MigrationUtility::getUserGroups( $this->request ) );
        $this->settings['mayNotHaveGroups'] = GeneralUtility::trimExplode("," , $this->settings['mayNotHaveGroups']  , true ) ;
        $this->settings['mustHaveGroups'] = GeneralUtility::trimExplode("," , $this->settings['mustHaveGroups']  , true ) ;
        $this->view->assign('settings', $this->settings);
        $obj = $this->request->getAttribute('currentContentObject')->data ;

        $uid = false ;
        if( $this->request->hasArgument('uid') &&$this->request->hasArgument('done')) {
            $uid = $this->request->getArgument('uid');
            $this->view->assign('done', true );
        } else {
            $uid = $obj['uid'] ;
        }
        $this->view->assign('uid', $uid );
        $this->view->assign('server', ($_SERVER['SERVER_NAME'] ?? '' ));
        $this->view->assign('ddev', ($_ENV['DDEV_PROJECT'] ?? false )); ;
        $this->view->assign('hash', hash( "sha256" , $obj['tstamp'] . "JVE" . MigrationUtility::getUserHash($this->request) ));
        $this->view->assign('session', $obj['tstamp'] . "JVE" . MigrationUtility::getUserHash($this->request) );
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
        $obj = $this->request->getAttribute('currentContentObject')->data ;

        $user = MigrationUtility::getUser( $this->request ) ;
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
        $hash = false ;
        if( $this->request->hasArgument('hash')) {
            $hash = $this->request->getArgument('hash');
            $debug .= " |  Got Hash=" . $hash ;
        }
        if( $uid && $hash ) {
            $uid = $hash ;
            $debug .= " |  Got Hash=" . $uid ;
            if ( $uid !=  hash( "sha256" , $obj['tstamp'] . "JVE" .  MigrationUtility::getUserHash($this->request) ) ) {
                $uid = false ;
                $debug .= " |   Hash NOT VALID !! for tstamp/crdate : " . $obj['tstamp'] . " | " . MigrationUtility::getUserHash($this->request)  ;
                $this->addFlashMessage("Hash not valid." , '' , \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR) ;
            }
        } else {
            $uid = false ;
            $debug .= " |   got no Hash "   ;
            $this->addFlashMessage("Got no hash" , '' , \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR) ;
        }
        if( $uid ) {

            $debug .= " |   Add/remove Groups from user uid: "  . (int)$user['uid']   ;
            $debug .= " |   Current Groups: "  . MigrationUtility::getUserGroups( $this->request )   ;
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
            $feuser = $this->updateUserGroupField(trim($this->settings['willGetGroups']) ,trim($this->settings['willLooseGroups'] ) , $hookClassesSettings , $user , MigrationUtility::getUserGroups( $this->request ) ) ;

            $msg = trim( $this->settings['successMsg']) ;
            if ($feuser) {

                // 3) Re-create and store the user session so TYPO3 picks up the new data:
                $updatedSessionData = $this->request->getAttribute('frontend.user')->createUserSession($feuser);
                $this->request->getAttribute('frontend.user')->user = $feuser;
                $this->request->getAttribute('frontend.user')->storeSessionData();  // pushes changes into the session



                if( strlen( $msg ) > 1  ) {
                    $this->addFlashMessage($msg , '' , \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK ) ;
                }
            } else {
                $debug .= " |    user had Groups already  "    ;
                if( strlen( $msg ) > 1  && isset( $this->settings['nothingToDoMessage'] ) && !empty( $this->settings['nothingToDoMessage']) ) {
                    $this->addFlashMessage($this->settings['nothingToDoMessage'] , '' , \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::ERROR) ;
                }
                return $this->redirect("show" , null , null , array("done" => random_bytes(16) ) ) ;

            }

            if( $this->settings['debug'] == 1 || isset( $_ENV['DDEV_PROJECT']) ) {
                $this->getFlashMessageQueue()->getAllMessagesAndFlush(\TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::OK) ;

                $this->addFlashMessage( "Debug: " .   $debug  , "debug" , \TYPO3\CMS\Core\Type\ContextualFeedbackSeverity::INFO , true) ;
            }
            return( new ForwardResponse("show"))
                ->withArguments( array("done" => random_bytes(16) )
                ) ;
        } else {
            return( new ForwardResponse("show")) ;
        }

    }

    private function updateUserGroupField( $addGroups , $removeGroups , $HookClasses , $user , $oldGroups ) {
        $uid = ( $user["uid"] ? (int)$user["uid"] : 0 ) ;
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
        $connectionPool = $this->connectionPool;
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $connectionPool->getQueryBuilderForTable('fe_users') ;

        /** @var Connection $connection */
        $connection = $connectionPool->getConnectionForTable('fe_users') ;

        $queryBuilder ->update('fe_users')
            ->where( $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid , Connection::PARAM_INT )) )
            ->set('usergroup', $newGroups ) ;

        try {
            $queryBuilder->executeStatement() ;
        } catch(\Doctrine\DBAL\Exception $e ) {
            return false;
        }
        if ( method_exists( $connection , 'errorInfo' ) ) {
            if ( !$connection->errorInfo() ) {
                return $user;
            } else {
                return false;
            }
        }

    }

    /**
    
        /**
    * action removeAction
    *
    * @return \Psr\Http\Message\ResponseInterface
    */
    public function removeAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->redirect("show");
    }

}
