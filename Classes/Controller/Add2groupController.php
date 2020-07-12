<?php
namespace JVE\JvAdd2group\Add2groupController;

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
        $user = $GLOBALS['TSFE']->fe_user ;
        $this->view->assign('user', $user);
        $this->view->assign('settings', $this->settings);
    }

    /**
     * action addAction
     *
     * @return void
     */
    public function addAction()
    {
        $this->redirect("show" ) ;
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
