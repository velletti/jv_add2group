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

        $cssClasses =  $typoscript['plugin']['tx_jvadd2group']['settings']['cssClasses'] ;
        if (!isset( $typoscript['plugin']['tx_jvadd2group']['settings']['cssClasses']) || $cssClasses == '' ) {
            $result['html'] = '<div class="alert alert-error">Typo Script of this Extension : settings.cssClasses .. not set ! </div>' ;
            return $result;
        }
        if ( !is_array($cssClasses)) {
            $result['html'] = '<div class="alert alert-error">Typo Script of this Extension : settings.cssClasses .. not an Array ! </div>' ;
            return $result;
        }

        $PA = $this->data['parameterArray']  ;
        $result['html'] =  '<select name="' . $PA['itemFormElName'] . '"';
        $result['html'] .= ' onchange="' . htmlspecialchars(implode('', $PA['fieldChangeFunc'])) . '"';
        $result['html'] .= $PA['onFocus'];
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
