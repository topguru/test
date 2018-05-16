<?php
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHTML::_('behavior.modal');
?>
<script type="text/javascript">
	(function($) {
	   $.fn.collapsible = function (cmd, arg) {
	   	//firewalling
	       if (!this || this.length < 1) {
	           return this;
	       }
	
	       //address command requests
	       if (typeof cmd == 'string') {
	           return $.fn.collapsible.dispatcher[cmd](this, arg);
	       }
	       
	       //return the command dispatcher
	       return $.fn.collapsible.dispatcher['_create'](this, cmd);
	   };
	
	   //create the command dispatcher
	   $.fn.collapsible.dispatcher = {
	
	       //initialized with options
	       _create : function(obj, arg) {
	           createCollapsible(obj, arg);
	       },
	
	       //toggle the element's display
	       toggle: function(obj) {
	           toggle(obj, loadOpts(obj));
	           return obj;
	       },
	
	       //show the element
	       open: function(obj) {
	           open(obj, loadOpts(obj));
	           return obj;
	       },
	
	       //hide the element
	       close: function(obj) {
	           close(obj, loadOpts(obj));
	           return obj;
	       },
	
	       //check if the element is closed
	       collapsed: function(obj) {
	           return collapsed(obj, loadOpts(obj));
	       },
	
	       //open all closed containers
	       openAll: function(obj) {
	           return openAll(obj, loadOpts(obj));
	       },
	
	       //close all opened containers
	       closeAll: function(obj) {
	           return closeAll(obj, loadOpts(obj));
	       }
	   };
	
	   //create the initial collapsible
	   function createCollapsible(obj, options)
	   {
	
	       //build main options before element iteration
	       var opts = $.extend({}, $.fn.collapsible.defaults, options);
	       
	       //store any opened default values to set cookie later
	       var opened = [];
	       
	       //iterate each matched object, bind, and open/close
	       obj.each(function() {
	
	           var $this = $(this);
	           saveOpts($this, opts);
	           
	           //bind it to the event
	           if (opts.bind == 'mouseenter') {
	
	               $this.bind('mouseenter', function(e) {
	                   e.preventDefault(); 
	                   toggle($this, opts);
	               });
	           }
	           
	           //bind it to the event
	           if (opts.bind == 'mouseover') {
	
	               $this.bind('mouseover',function(e) {
	                   e.preventDefault(); 
	                   toggle($this, opts); 
	               });
	           }
	           
	           //bind it to the event
	           if (opts.bind == 'click') {
	
	               $this.bind('click', function(e) {
	                   e.preventDefault();
	                   toggle($this, opts);
	               });
	
	           }
	           
	           //bind it to the event
	           if (opts.bind == 'dblclick') {
	
	               $this.bind('dblclick', function(e) {
	
	                   e.preventDefault();
	                   toggle($this, opts);
	               });
	
	           }
	           
	           //initialize the collapsibles
	           //get the id for this element
	           var id = $this.attr('id');
	           
	           //if not using cookies, open defaults
	           if (!useCookies(opts)) {
	
	               //is this collapsible in the default open array?
	               var dOpenIndex = inDefaultOpen(id, opts);
	               
	               //close it if not defaulted to open
	               if (dOpenIndex === false) {
	
	                   $this.addClass(opts.cssClose);
	                   opts.loadClose($this, opts);
	
	               } else { //its a default open, open it
	
	                   $this.addClass(opts.cssOpen);
	                   opts.loadOpen($this, opts);
	                   opened.push(id);
	               }
	
	           } else { //can use cookies, use them now
	
	               //has a cookie been set, this overrides default open
	               if (issetCookie(opts)) {
	
	                   var cookieIndex = inCookie(id, opts);
	
	                   if (cookieIndex === false) {
	
	                       $this.addClass(opts.cssClose);
	                       opts.loadClose($this, opts);
	
	                   } else {
	
	                       $this.addClass(opts.cssOpen);
	                       opts.loadOpen($this, opts);
	                       opened.push(id);
	                   }
	
	               } else { //a cookie hasn't been set open defaults, add them to opened array
	
	                   dOpenIndex = inDefaultOpen(id, opts);
	
	                   if (dOpenIndex === false) {
	
	                       $this.addClass(opts.cssClose);
	                       opts.loadClose($this, opts);
	
	                   } else {
	
	                       $this.addClass(opts.cssOpen);
	                       opts.loadOpen($this, opts);
	                       opened.push(id);
	                   }
	               }
	           }
	       });
	       
	       //now that the loop is done, set the cookie
	       if (opened.length > 0 && useCookies(opts)) {
	
	           setCookie(opened.toString(), opts);
	
	       } else { //there are none open, set cookie
	
	           setCookie('', opts);
	       }
	       
	       return obj;
	   }
	   
	   //load opts from object
	   function loadOpts($this) {
	       return $this.data('collapsible-opts');
	   }
	   
	   //save opts into object
	   function saveOpts($this, opts) {
	       return $this.data('collapsible-opts', opts);
	   }
	   
	   //returns true if object is opened
	   function collapsed($this, opts) {
	       return $this.hasClass(opts.cssClose);
	   }
	   
	   //hides a collapsible
	   function close($this, opts) {
	
	       //give the proper class to the linked element
	       $this.addClass(opts.cssClose).removeClass(opts.cssOpen);
	       
	       //close the element
	       opts.animateClose($this, opts);
	       
	       //do cookies if plugin available
	       if (useCookies(opts)) {
	           // split the cookieOpen string by ","
	           var id = $this.attr('id');
	           unsetCookieId(id, opts);
	       }
	   }
	   
	   //opens a collapsible
	   function open($this, opts) {
	
	       //give the proper class to the linked element
	       $this.removeClass(opts.cssClose).addClass(opts.cssOpen);
	       
	       //open the element
	       opts.animateOpen($this, opts);
	       
	       //do cookies if plugin available
	       if (useCookies(opts)) {
	
	           // split the cookieOpen string by ","
	           var id = $this.attr('id');
	           appendCookie(id, opts);
	       }
	   }
	   
	   //toggle a collapsible on an event
	   function toggle($this, opts) {
	
	       if (collapsed($this, opts)) {
	
	           //open a closed element
	           open($this, opts);
	
	       } else {
	
	           //close an open element
	           close($this, opts);
	       }
	       
	       return false;
	   }
	
	   //open all closed containers
	   function openAll($this, opts) {
	
	       // loop through all container elements
	       $.each($this, function(elem, value) {
	
	           if (collapsed($(value), opts)) {
	
	               //open a closed element
	               open($(value), opts);
	           }
	       });
	   }
	
	   //close all open containers
	   function closeAll($this, opts) {
	
	       $.each($this, function(elem, value) {
	
	           if (!collapsed($(value), opts)) {
	
	               //close an opened element
	               close($(value), opts);
	           }
	       });
	   }
	   
	   //use cookies?
	   function useCookies(opts) {
	
	       //return false if cookie plugin not present or if a cookie name is not provided
	       if (!$.cookie || opts.cookieName == '') {
	           return false;
	       }
	       
	       //we can use cookies

	       return true;
	   }
	   
	   //append a collapsible to the cookie
	   function appendCookie(value, opts) {
	
	       //check if cookie plugin available and cookiename is set
	       if (!useCookies(opts)) {
	           return false;
	       }
	       
	       //does a cookie already exist
	       if (!issetCookie(opts)) {
	
	           //no lets set one
	           setCookie(value, opts);
	           return true;
	       }
	       
	       //cookie already exists, is this collapsible already set?
	       if (inCookie(value, opts)) { //yes, quit here
	           return true;
	       }
	       
	       //get the cookie
	       var cookie = decodeURIComponent($.cookie(opts.cookieName));
	
	       //turn it into an array
	       var cookieArray = cookie.split(',');
	       
	       //add it to list
	       cookieArray.push(value);
	       
	       //save it
	       setCookie(cookieArray.toString(), opts);
	       
	       return true;    
	   }
	   
	   //unset a collapsible from the cookie
	   function unsetCookieId(value, opts)
	   {
	       //check if cookie plugin available and cookiename is set
	       if (!useCookies(opts)) {
	           return false;
	       }
	       
	       //if its not there we don't need to remove from it
	       if (!issetCookie(opts)) { //quit here, don't have a cookie 
	           return true;
	       }
	       
	       //we have a cookie, is this collapsible in it
	       var cookieIndex = inCookie(value, opts);
	       if (cookieIndex === false) { //not in the cookie quit here
	           return true;
	       }
	       
	       //still here get the cookie
	       var cookie = decodeURIComponent($.cookie(opts.cookieName));
	       
	       //turn it into an array
	       var cookieArray = cookie.split(',');
	       
	       //lets pop it out of the array
	       cookieArray.splice(cookieIndex, 1);
	
	       //overwrite
	       setCookie(cookieArray.toString(), opts);
	
	       return true
	   }
	   
	   //set a cookie
	   function setCookie(value, opts)
	   {
	       //can use the cookie plugin
	       if (!useCookies(opts)) { //no, quit here
	           return false;
	       }
	       
	       //cookie plugin is available, lets set the cookie
	       $.cookie(opts.cookieName, value, opts.cookieOptions);
	
	       return true;
	   }
	   
	   //check if a collapsible is in the cookie
	   function inCookie(value, opts)
	   {
	       //can use the cookie plugin
	       if (!useCookies(opts)) {
	           return false;
	       }
	       
	       //if its not there we don't need to remove from it
	       if (!issetCookie(opts)) { //quit here, don't have a cookie 
	           return false;
	       }
	
	       //get the cookie value
	       var cookie = decodeURIComponent($.cookie(opts.cookieName));
	       
	       //turn it into an array
	       var cookieArray = cookie.split(',');
	       
	       //get the index of the collapsible if in the cookie array
	       var cookieIndex = $.inArray(value, cookieArray);
	       
	       //is this value in the cookie array
	       if (cookieIndex == -1) { //no, quit here
	           return false;
	       }
	       
	       return cookieIndex;
	   }
	   
	   //check if a cookie is set
	   function issetCookie(opts)
	   {
	       //can we use the cookie plugin
	       if (!useCookies(opts)) { //no, quit here
	           return false;
	       }
	       
	       //is the cookie set
	       if ($.cookie(opts.cookieName) === null) { //no, quit here
	           return false;
	       }
	       
	       return true;
	   }
	   
	   //check if a collapsible is in the list of collapsibles to be opened by default
	   function inDefaultOpen(id, opts)
	   {
	       //get the array of open collapsibles
	       var defaultOpen = getDefaultOpen(opts);
	       
	       //is it in the default open array
	       var index = $.inArray(id, defaultOpen);
	       if (index == -1) { //nope, quit here
	           return false;
	       }
	       
	       return index;
	   }
	   
	   //get the default open collapsibles and return array
	   function getDefaultOpen(opts)
	   {
	       //initialize an empty array
	       var defaultOpen = [];
	       
	       //if there is a list, lets split it into an array
	       if (opts.defaultOpen != '') {
	           defaultOpen = opts.defaultOpen.split(',');
	       }
	       
	       return defaultOpen;
	   }
	   
	   // settings
	   $.fn.collapsible.defaults = {
	       cssClose: 'collapse-close', //class you want to assign to a closed collapsible header
	       cssOpen: 'collapse-open2', //class you want to assign an opened collapsible header
	       cookieName: 'collapsible', //name of the cookie you want to set for this collapsible
	       cookieOptions: { //cookie options, see cookie plugin for details
	           path: '/',
	           expires: 7,
	           domain: '',
	           secure: ''
	       },
	       defaultOpen: '', //comma separated list of header ids that you want opened by default
	       speed: 'slow', //speed of the slide effect
	       bind: 'click', //event to bind to, supports click, dblclick, mouseover and mouseenter
	       animateOpen: function (elem, opts) { //replace the standard slideUp with custom function
	           elem.next().stop(true, true).slideDown(opts.speed);
	       },
	       animateClose: function (elem, opts) { //replace the standard slideDown with custom function
	           elem.next().stop(true, true).slideUp(opts.speed);
	       },
	       loadOpen: function (elem, opts) { //replace the default open state with custom function
	           elem.next().show();
	       },
	       loadClose: function (elem, opts) { //replace the default close state with custom function
	           elem.next().hide();
	       }
	   };
	
	})(jQuery);

	jQuery.noConflict();
	jQuery(document).ready(function(jQuery)
	    {
			//$j('.collapse').collapsiblePanel();
			//syntax highlighter
			hljs.tabReplace = '    ';
	        hljs.initHighlightingOnLoad();	        
	        jQuery.fn.slideFadeToggle = function(offset, speed, easing, callback) {
	            return this.animate({
	                opacity: 'toggle',
	                width: 'toggle',
	                left: offset
	            }, speed, easing, callback);
	        };
	        jQuery('.collapsible').collapsible({
	            defaultOpen: 'section1',
	            cookieName: 'nav',
	            speed: 'slow',
	            animateOpen: function (elem, opts) { //replace the standard slideUp with custom function
	                elem.next().slideFadeToggle(opts.speed);
	            },
	            animateClose: function (elem, opts) { //replace the standard slideDown with custom function
	                elem.next().slideFadeToggle(opts.speed);
	            },
	            loadOpen: function (elem) { //replace the standard open state with custom function
	                elem.next().show();
	            },
	            loadClose: function (elem, opts) { //replace the close state with custom function
	                elem.next().hide();
	            }
	        }); 
	        jQuery('.page_collapsible2').collapsible({
	            defaultOpen: 'body_section1',
	            cookieName: 'body2',
	            speed: 'slow',
	            animateOpen: function (elem, opts) { //replace the standard slideUp with custom function
	                elem.next().slideFadeToggle(opts.speed);
	            },
	            animateClose: function (elem, opts) { //replace the standard slideDown with custom function
	                elem.next().slideFadeToggle(opts.speed);
	            },
	            loadOpen: function (elem) { //replace the standard open state with custom function
	                elem.next().show();
	            },
	            loadClose: function (elem, opts) { //replace the close state with custom function
	                elem.next().hide();
	            }

	        });

		    //assign open/close all to functions
	        function openAll() {
		        console.log(jQuery('.page_collapsible2'));
	            jQuery('.page_collapsible2').collapsible('openAll');
	        }
	        function closeAll() {
	        	jQuery('.page_collapsible2').collapsible('closeAll');
	        }

	      	//listen for close/open all
	        jQuery('#closeAll').click(function(event) {
		        event.preventDefault();
	            closeAll();

	        });
	        jQuery('#openAll').click(function(event) {
	            event.preventDefault();
	            openAll();
	        });	        	
		});
</script>
<form id="adminForm" action="<?php echo JRoute::_('index.php?option=com_awardpackage&view=userlist&task=userlist.user_list');?>" method="post" name="adminForm">
<div id="cj-wrapper">
	<div class="container-fluid quiz-wrapper nospace-left no-space-left no-space-right">
		<div class="row-fluid">					
			<input type="hidden" name="package_id" value="<?php echo  JRequest::getVar("package_id"); ?>"/>
			<input type="button" id="openAll" value="Open All"/>
			&nbsp;
			<input type="button" id="closeAll" value="Close All"/>
			&nbsp;
			<input type="submit" value="Search"/>
			<br/>
			<br/>		
			<table>
				<tr>
					<td width="30%" valign="top" style="border-width: 1px; border-style: solid; border-color: transparent #ccc transparent transparent;">							
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('User');?></div>
						<div class="content">
							<table width="100%">
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Name');?>:										
											</label>
											<div class="controls">
												<input name="user_name" style="width:100px" type="text" value="">
											</div>
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Dob');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'user_dob', 'user_dob', '%Y-%m-%d', array('placeholder' => 'YYYY-MM-DD')); ?>
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Age From');?>:										
											</label>
											<div class="controls">
												<input name="user_age_from" style="width:100px" type="text" value="">
											</div>
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Age To');?>:										
											</label>
											<div class="controls">
												<input name="user_age_to" style="width:100px" type="text" value="">
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Gender');?>:										
											</label>
											<div class="controls">
												<select name="user_gender" style="width:150px">
													<option value="-1">All</option>
													<option value="m">M</option>
													<option value="f">F</option>
												</select>
											</div>									
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('City');?>:										
											</label>
											<div class="controls">
												<input name="user_city" type="text" style="width:100px;" value="">
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Country');?>:										
											</label>
											<div class="controls">
												<select name="user_country">
												<option value="-1">All</option>
												<?php foreach ($this->countries as $country) { ?>
												<option value="<?php echo $country; ?>"><?php echo $country; ?></option>
												<?php } ?>
												</select>
											</div>									
										</div>
									</td>
								</tr>								
							</table>
						</div>
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('Funds');?></div>
						<div class="content">
							<table>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date from');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'funds_from', 'funds_from', '%Y-%m-%d', array('placeholder' => 'YYYY-MM-DD')); ?>
											</div>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'funds_to', 'funds_to', '%Y-%m-%d', array('placeholder' => 'YYYY-MM-DD')); ?>
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Added amount from');?>:										
											</label>
											<input type="text" style="width:100px" name="funds_amount_from"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Added amount to');?>:										
											</label>
											<input type="text" style="width:100px" name="funds_amount_to"/>									
										</div>
									</td>
								</tr>									
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Withdraw from');?>:										
											</label>
											<input type="text" style="width:100px" name="funds_withdraw_from"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Withdraw to');?>:										
											</label>
											<input type="text" style="width:100px" name="funds_withdraw_to"/>									
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Current total from');?>:										
											</label>
											<input type="text" style="width:100px" name="funds_current_from"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Current total to');?>:										
											</label>
											<input type="text" style="width:100px" name="funds_current_to"/>									
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('Donation');?></div>
						<div class="content">
							<table>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date from');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'donation_from', 'fund_from', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date to');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'donation_to', 'fund_to', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Donation from amount');?>:										
											</label>
											<input type="text" style="width:100px" name="donation_amount_from"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input type="text" style="width:100px" name="donation_amount_to"/>									
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Donation category');?>:										
											</label>
											<select name="donation_category">
												<option value="-1">All</option>
												<?php foreach ($this->categories as $category){ ?>
												<option value="<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
												<?php } ?>
											</select>
										</div>
									</td>
								</tr>								
							</table>
						</div>
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('Quiz');?></div>
						<div class="content">
							<table>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date from');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'quiz_from', 'quiz_from', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'quiz_to', 'quiz_to', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Quiz completed from');?>:										
											</label>
											<input name="quiz_completed_from" style="width:100px" />																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input name="quiz_completed_to" style="width:100px" />																		
										</div>
									</td>
								</tr>																
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Quiz created from');?>:										
											</label>
											<input name="quiz_created_from" style="width:100px" />																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input name="quiz_created_to" style="width:100px" />																		
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('Survey');?></div>
						<div class="content">
							<table>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date from');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'survey_from', 'survey_from', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'survey_to', 'survey_to', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Survey completed from');?>:										
											</label>
											<input name="survey_completed_from" style="width:100px" />																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input name="survey_completed_to" style="width:100px" />																		
										</div>
									</td>
								</tr>																
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Survey created from');?>:										
											</label>
											<input name="survey_created_from" style="width:100px" />																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input name="survey_created_to" style="width:100px" />																		
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('Giftcode');?></div>
						<div class="content">
							<table>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date from');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'giftcode_from', 'giftcode_from', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'giftcode_to', 'giftcode_to', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>									
										</div>
									</td>
								</tr>								
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Claimed giftcodes from');?>:										
											</label>
											<input type="text" name="giftcode_claimed_from" style="width:100px;" />																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input type="text" name="giftcode_claimed_to" style="width:100px;" />									
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Giftcode category');?>:										
											</label>
											<select name="giftcode_category">
												<option value="-1">All</option>
												<?php foreach ($this->categories as $category){ ?>
												<option value="<?php echo $category->category_id; ?>"><?php echo $category->category_name; ?></option>
												<?php } ?>
											</select>																		
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('Award Symbols');?></div>
						<div class="content">
							<table width="100%">
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date from');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'award_from', 'award_from', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'award_to', 'award_to', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Symbol pieces purchased value from');?>:										
											</label>
											<input type="text" name="award_purchased_from" style="width:100px;"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input type="text" name="award_purchased_to" style="width:100px;"/>																		
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Number of symbol pieces purchased from');?>:										
											</label>
											<input type="text" name="award_number_symbol_purchased_from" style="width:100px;"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input type="text" name="award_number_symbol_purchased_to" style="width:100px;"/>																		
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Users with prizes that have');?>:										
											</label>
											<input type="text" name="award_total_from" style="width:100px;"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to total symbol pieces to collect');?>:										
											</label>
											<input type="text" name="award_total_to" style="width:100px;"/>																		
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Users with prizes that have');?>:										
											</label>
											<input type="text" name="award_not_collected_from" style="width:100px;"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to symbol pieces not collected');?>:										
											</label>
											<input type="text" name="award_not_collected_to" style="width:100px;"/>																		
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Users with prizes that have');?>:										
											</label>
											<input type="text" name="award_pieces_collected_from" style="width:100px;"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to symbol pieces collected');?>:										
											</label>
											<input type="text" name="award_pieces_collected_to" style="width:100px;"/>																		
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('Prizes');?></div>
						<div class="content">
							<table width="100%">
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date from');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'prizes_from', 'prizes_from', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'prizes_to', 'prizes_to', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Prize value from');?>:										
											</label>
											<input type="text" value="prizes_value_from" style="width:100px;"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input type="text" value="prizes_value_to" style="width:100px;"/>																		
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Prizes status');?>:										
											</label>
											<select name="prizes_status">
												<option value="-1">All</option>
												<option value="locked">Locked (not won)</option>
												<option value="won">Won</option>
												<option value="claimed">Claimed</option>
											</select>																		
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="page_collapsible2 collapse-close" id="body-section1"><?php echo JText::_('Shopping credits');?></div>
						<div class="content">
							<table width="100%">
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Date from');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'sc_from', 'sc_from', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<div class="controls"><?php echo JHtml::_('calendar', null, 'sc_to', 'sc_to', '%Y-%m-%d %H:%M:%S', array('placeholder' => 'YYYY-MM-DD HH:mm:ss')); ?>
											</div>									
										</div>
									</td>
								</tr>
								<tr>
									<td width="50%" valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Shopping credits value from');?>:										
											</label>
											<input type="text" value="sc_value_from" style="width:100px;"/>																		
										</div>
									</td>
									<td width="2%">&nbsp;</td>
									<td valign="top">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('to');?>:										
											</label>
											<input type="text" value="sc_value_to" style="width:100px;"/>																		
										</div>
									</td>
								</tr>
								<tr>
									<td colspan="3">
										<div class="control-group">
											<label class="control-label">
												<?php echo JText::_('Shopping credits status');?>:										
											</label>
											<select name="sc_status">
												<option value="-1">All</option>
												<option value="awarded">Awarded</option>
												<option value="locked">Locked</option>
												<option value="unlocked">Unlocked</option>
												<option value="expired">Expired</option>
											</select>																		
										</div>
									</td>
								</tr>
							</table>
						</div>
					</td>
					<td width="2%" style="">&nbsp;</td>
					<td valign="top">
						<div class="span12">
							<table class="table table-hover table-striped table-bordered">
								<thead>
									<tr>
										<th><?php echo JHTML::_( 'grid.sort', JText::_( 'User' ), 'name', $this->lists['order_dir'], $this->lists['order']); ?></th>								
										<th><?php echo JText::_('Account'); ?></th>																	
										<th><?php echo JText::_('Funds'); ?></th>
										<th><?php echo JText::_('Donation'); ?></th>
										<th><?php echo JText::_('Quiz'); ?></th>
										<th><?php echo JText::_('Survey'); ?></th>
										<th><?php echo JText::_('Giftcode'); ?></th>
										<th><?php echo JText::_('Symbol Queue'); ?></th>
										<th><?php echo JText::_('Presentation'); ?></th>
										<th><?php echo JText::_('Shopping Credits'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php 
									if (!empty($_POST)) {
									//var_dump($this->users);
									foreach ($this->users as $row):?>
									<tr>
										<td class="hidden-phone"><?php echo JText::_($row->NAME); ?></td>
										<td class="hidden-phone"><?php echo JText::_(empty($row->all_transactions) ? 'no-data' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_all_transactions&accountId='.$row->id.'&package_id='.$row->package_id .'" target="_blank">View</a>'); ?></td>							
										<td class="hidden-phone"><?php echo JText::_(empty($row->funds) ? 'no-data' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_all_funds&accountId='.$row->id.'&package_id='.$row->package_id.'" target="_blank">View</a>'); ?></td>
										<td class="hidden-phone"><?php echo JText::_(empty($row->donation) ? 'no-data' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_all_donation&accountId='.$row->id.'&package_id='.$row->package_id.'" target="_blank">View</a>'); ?></td>
										<td class="hidden-phone"><?php echo JText::_(empty($row->quiz) ? 'no-data' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_quizzes&accountId='.$row->id.'&package_id='.$row->package_id.'" target="_blank">View</a>'); ?></td>
										<td class="hidden-phone"><?php echo JText::_(empty($row->survey) ? 'no-data' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_surveys&accountId='.$row->id.'&package_id='.$row->package_id.'" target="_blank">View</a>'); ?></td>
										<td class="hidden-phone"><?php echo JText::_(empty($row->giftcode) ? 'no-data' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_giftcode&accountId='.$row->id.'&package_id='.$row->package_id.'" target="_blank">View</a>');?></td>
										<td class="hidden-phone"><?php echo JText::_(empty($row->symbol_queue) ? 'No' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_symbol_queue_detail&accountId='.$row->id.'&package_id='.$row->package_id.'" target="_blank">Yes</a>'); ?></td>
										<td class="hidden-phone"><?php echo JText::_(empty($row->presentation) ? 'no-data' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_presentation&accountId='.$row->id.'&package_id='.$row->package_id.'" target="_blank">View</a>'); ?></td>
										<td class="hidden-phone"><?php echo JText::_(empty($row->shopping_credits) ? 'no-data' : '<a href="index.php?option=com_awardpackage&view=userlist&task=userlist.get_shopping_credit&accountId='.$row->id.'&package_id='.$row->package_id.'" target="_blank">View</a>'); ?></td>
									</tr>
									<?php endforeach;
									}
									?>
								</tbody>									
							</table>
						</div>
					</td>
				</tr>
			</table>				
			
			<input type="hidden" name="task" value="userlist.user_list" />
			<input type="hidden" name="filter_order" value="<?php if($this->lists['order']) echo $this->lists['order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php if($this->lists['order_dir']) echo $this->lists['order_dir']; ?>" />		
		</div>
	</div>
</div>
</form>	