<?php
defined('_JEXEC') or die();
?>
<div id="cj-wrapper">
	<div class="container-fluid no-space-left no-space-right surveys-wrapper">
		<div class="row-fluid">
			<table width="100%">
				<tr>
					<td width="10%" valign="top">
						<?php include_once JPATH_COMPONENT.DS.'helpers'.DS.'main_header.php';?>
					</td>
					<td valign="top">
						<div class="span12">
							<div class="well">
										
								<nav class="navigation" role="navigation">
                                <ul class="nav menu nav-pills">
                               <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getFunds&accountId=".JRequest::getVar('accountId')."&package_id=".JRequest::getVar('package_id')."");?>">Funds</a>	</li>
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getDonation&accountId=".JRequest::getVar('accountId')."&package_id=".JRequest::getVar('package_id')."");?>">Donation</a>	</li>
								<li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getAwardSymbol&accountId=".JRequest::getVar('accountId')."&package_id=".JRequest::getVar('package_id')."");?>">Award Symbol</a>	</li>                                
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getShoppingCredit&accountId=".JRequest::getVar('accountId')."&package_id=".JRequest::getVar('package_id')."");?>">Shopping Credit</a>	</li>
                                <li><a href="<?php echo JRoute::_("index.php?option=com_awardpackage&view=uaccount&task=uaccount.getPrize&accountId=".JRequest::getVar('accountId')."&package_id=".JRequest::getVar('package_id')."");?>">Prize Claimed</a>		</li>	<br />
</ul>
</nav>                                
						</div>
						<div class="span12">
							<div class="login">
							
							</div>
						</div>	
					</td>
				</tr>
			</table>									
		</div>
	</div>
</div>

