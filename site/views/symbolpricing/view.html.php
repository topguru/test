<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class AwardPackageViewSymbolPricing extends JViewLegacy {

    public function display($tpl = null) {
        $this->model = $this->getModel('symbolpricing');
        $this->items = $this->model->getSymbol();
        $document = & JFactory::getDocument();
        $document->addStyleSheet('administrator/templates/system/css/system.css');
        $document->addCustomTag(
                '<link href="administrator/templates/bluestork/css/template.css" rel="stylesheet" type="text/css" />' . "\n\n" .
                '<!--[if IE 7]>' . "\n" .
                '<link href="administrator/templates/bluestork/css/ie7.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n" .
                '<!--[if gte IE 8]>' . "\n\n" .
                '<link href="administrator/templates/bluestork/css/ie8.css" rel="stylesheet" type="text/css" />' . "\n" .
                '<![endif]-->' . "\n" .
                '<link rel="stylesheet" href="administrator/templates/bluestork/css/rounded.css" type="text/css" />' . "\n"
        );

         
        parent::display($tpl);
    }

}

?>