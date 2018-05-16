<style>
  #main-container  #group-button{
    padding-left: 35px;
    margin: 5px 0;
  }
  #j-sidebar-container{
    width: 285px;
  }
  #main-container .pane-slider{
    border : none !important;
  }
  #main-container .panelform{
    display : none;
  }
  #main-container .panelform a,.panelform a:visited{
      background: none;
      text-decoration: none;
      color: #444;
      display:block;
      padding: 5px;
      font-size: 90%;

  }
  #main-container .panelform a:hover{
      color: blue;
      background: none;
  }
  #main-container .panel a:hover{
      color: blue;
      text-decoration:none;
  }
  #main-container .panel h3{
    font-size : 11px !important;
    margin : 0;
    padding: 10px;
	background:#f2f2f2;
	border:1px solid #ccc;

  }
  #main-container .panel{
	background:#fcfcfc;
	border:1px solid #ccc;
  }
  #main-container .pane-slider{
    margin : 5px;
    padding : 5px;

  }
  .leftmenu{
      border-right: 1px solid #CCC;
      height: auto;
  }
  .btn:hover,.btn:focus{
      box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.05);
      text-shadow: none;
      border: #AAA 1px solid;
  }
  fieldset#main-container{
    padding-right: 20px;
  }
  fieldset#main-container ul{
      padding-left: 40px;
      padding-bottom: 0;
      margin-bottom: 0;
  }
  #main-container .panel h3.pane-toggler a {
      background-position: -972px 0px !important;
      padding-left: 40px;
  }
  #main-container  .panel h3.pane-toggler-down a {
      background-position: -972px 0px !important;
      border-bottom: none !important;
      padding-left: 40px; 
  }
  /* +++++++++++++++++++++++  SLIDER  ++++++++++++++++++++  */
.panel h3.pane-toggler a {
	background: url(../templates/beez3/images/slider_plus.png) right top no-repeat;
	color: #333
}

.panel h3.pane-toggler-down a {
	background: url(../templates/beez3/images/slider_minus.png) right top no-repeat;
	border-bottom: solid 1px #ddd;
	color: #333
}
</style>

<div id="j-sidebar-container" class="span3 leftmenu">
    <fieldset id="main-container">
        
            <div>
                <div id="group-button">
                    <button onclick="return false;" id='expand-alls' class="btn btn-small"><?php echo JText::_('Expand All');?></button>
                    <button onclick="return false;" id='collapse-alls' class="btn btn-small"><?php echo JText::_('Collapse All');?></button>
                </div>
                <legend></legend>
            </div>
        
        <div class='panel'>
            <!--AMS DIRECTORY-->
            <div class='pane-slider content pane-down' style="padding-top: 0px; padding-bottom: 0px; overflow: hidden; height: auto;">
                <h3 id="toggle-trigger" class="title pane-toggler">
                    <a href="javascript:void(0);"><span><?php echo JText::_('Presentation User groups');?></span></a>
                </h3>
                <fieldset class="panelform">
                    <ul>
                        <li><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=apresentationlist&task=apresentationlist.initiate&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Presentation user group list');?></a></li>
                    </ul>
                </fieldset>
            </div>
            <!--SYSTEM-->
            <div class='pane-slider content pane-down' style="padding-top: 0px; padding-bottom: 0px; overflow: hidden; height: auto;">
                <h3 id="toggle-trigger" class="title pane-toggler">
                    <a href="javascript:void(0);"><span><?php echo JText::_('Process presentation');?></span></a>
                </h3>
                <fieldset class="panelform">
                    <ul>
                         <li><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=processpresentationlist&task=processpresentationlist.get_processpresentationlist&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Process presentation list');?></a></li>
                    </ul>
                </fieldset>
            </div>
            <!--DOMAIN-->
            <div class='pane-slider content pane-down' style="padding-top: 0px; padding-bottom: 0px; overflow: hidden; height: auto;">
                <h3 id="toggle-trigger" class="title pane-toggler">
                    <a href="javascript:void(0);"><span><?php echo JText::_('Award funds plan');?></span></a>
                </h3>
                <fieldset class="panelform">
                    <ul>
                        <li><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=awardfundplan&task=awardfundplan.get_awardfundplan&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Award Fund Plan list');?></a></li>
                    </ul>
                </fieldset>
            </div>
            <!--SECTION-->
            <div class='pane-slider content pane-down' style="padding-top: 0px; padding-bottom: 0px; overflow: hidden; height: auto;">
                <h3 id="toggle-trigger" class="title pane-toggler">
                    <a href="javascript:void(0);"><span><?php echo JText::_('Fund prize plan');?></span></a>
                </h3>
                <fieldset class="panelform">
                    <ul>
                        <li><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=fundprizeplan&task=fundprizeplan.get_fundprizeplan&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Fund prize plan list');?></a></li>
                    </ul>
                </fieldset>
            </div>
            <!--SECTION CATEGORY-->
            <div class='pane-slider content pane-down' style="padding-top: 0px; padding-bottom: 0px; overflow: hidden; height: auto;">
                <h3 id="toggle-trigger" class="title pane-toggler">
                    <a href="javascript:void(0);"><span><?php echo JText::_('Fund receiver plan');?></span></a>
                </h3>
                <fieldset class="panelform">
                    <ul>
                        <li><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=fundreceiver&task=fundreceiver.get_fundreceiver&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Fund receiver plan list');?></a></li>
                    </ul>
                </fieldset>
            </div>
            
             <!--SECTION CATEGORY-->
            <div class='pane-slider content pane-down' style="padding-top: 0px; padding-bottom: 0px; overflow: hidden; height: auto;">
                <h3 id="toggle-trigger" class="title pane-toggler">
                    <a href="javascript:void(0);"><span><?php echo JText::_('Symbol queue groups');?></span></a>
                </h3>
                <fieldset class="panelform">
                    <ul>
                        <li><a href="<?php echo JRoute::_('index.php?option=com_awardpackage&view=symbolqueuegroup&task=symbolqueuegroup.get_symbolqueuegroup&package_id=' . JRequest::getVar('package_id')); ?>"><?php echo JText::_('Symbol queue groups list');?></a></li>
                    </ul>
                </fieldset>
            </div>
        </div> 
    </fieldset>
</div>
<div id="j-main-container" class="row span9">

