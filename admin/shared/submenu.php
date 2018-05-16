<?php
JSubMenuHelper::addEntry(JText::_('Award package'),'index.php?option=com_awardpackage', $submenu == 'donation');
JSubMenuHelper::addEntry(JText::_('Award Category'), 'index.php?option=com_awardpackage&view=category&package_id='.JRequest::getVar('package_id'));                
JSubMenuHelper::addEntry(JText::_('Gift Code'),'index.php?option=com_awardpackage&package_id='.JRequest::getVar('package_id'));									
JSubMenuHelper::addEntry(JText::_('Category Settings'),'index.php?option=com_awardpackage&view=category&package_id='.JRequest::getVar('package_id'), $submenu == 'settings');					
JSubMenuHelper::addEntry(JText::_('Donation List'),'index.php?option=com_awardpackage&view=transaction&package_id='.JRequest::getVar('package_id'), $submenu == 'donation');					
JSubMenuHelper::addEntry(JText::_('Donor List'),'index.php?option=com_awardpackage&view=donorlist&package_id='.JRequest::getVar('package_id'), $submenu == 'donor');					
JSubMenuHelper::addEntry(JText::_('Donation Settings'),'index.php?option=com_awardpackage&controller=donation&package_id='.JRequest::getVar('package_id').'&task=settings', $submenu == 'settings');	
?>