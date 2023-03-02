<?php
declare(strict_types = 1);
namespace JVelletti\JvAdd2group\FormEngine\Element;

use JVelletti\JvAdd2group\Utility\TypoScriptUtility;
use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class JvAdd2groupCustomLayoutElement extends AbstractFormElement
{

    /**
     * Main data array to work on, given from parent to child elements
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Container objects give $nodeFactory down to other containers.
     *
     * @param NodeFactory $nodeFactory
     * @param array $data
     */
    public function __construct(NodeFactory $nodeFactory, array $data  = [])
    {
        parent::__construct($nodeFactory, $data);
    }

    public function render(): array
    {
        $result = $this->initializeResultArray();
        $typoscript = TypoScriptUtility::getTypoScriptArray('jv_add2group') ;
        if ( !array_key_exists( "plugin" , $typoscript) ) {
            $result['html'] = '<div class="alert alert-error">Typo Script:  did not find extension jv_add2group plugin ! </div>' ;
            return $result;
        }
        if ( !array_key_exists( "tx_jvadd2group" , $typoscript['plugin']) ) {
            $result['html'] = '<div class="alert alert-error">Typo Script:  did not find plugin :  tx_jvadd2group  ! </div>' ;
            return $result;
        }
        if ( !array_key_exists( "settings" , $typoscript['plugin']['tx_jvadd2group']) ) {
            $result['html'] = '<div class="alert alert-error">Typo Script:  did not find settings in plugin :  jv_add2group  ! </div>' ;
            return $result;
        }
        if ( !array_key_exists( "cssClasses" , $typoscript['plugin']['tx_jvadd2group']['settings']) ) {
            $result['html'] = '<div class="alert alert-error">Typo Script:  did not find cssClasses  in settings of plugin jv_add2group  ! </div>' ;
            return $result;
        }
        $cssClasses = $typoscript['plugin']['tx_jvadd2group']['settings']['cssClasses'] ;
        if ( !is_array($cssClasses)) {
            $result['html'] = '<div class="alert alert-error">Typo Script of this Extension : settings.cssClasses .. not an Array ! </div>' ;
            return $result;
        }

        $PA = $this->data['parameterArray']  ;
        $result['html'] =  '<select name="' . $PA['itemFormElName'] . '"';
   //     $result['html'] .= ' onchange="' . htmlspecialchars(implode('', $PA['fieldChangeFunc'])) . '"';
        if ( array_key_exists( 'onFocus' , $PA)) {
            $result['html'] .= $PA['onFocus'];
        }
        $result['html']  .= ' >';
        foreach ( $cssClasses as $key => $cssClass) {
            $selected = '' ;
            if ( $cssClass['value']  == htmlspecialchars($PA['itemFormElValue']) ) {
                $selected = ' selected="selected"' ;
            }
            $result['html'] .= '<option ' . $selected . ' value="' . $cssClass['value'] .  '"> ' .  $cssClass['label'] . '</option>';
        }
        $result['html']  .= '</select>';

        return $result;
    }

}
