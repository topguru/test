<?php
/**
 *  PayPal IPN Listener
 *
 *  A class to listen for and handle Instant Payment Notifications (IPN) from 
 *  the PayPal server.
 *
 *  https://github.com/Quixotix/PHP-PayPal-IPN
 *
 *  @package    PHP-PayPal-IPN
 *  @author     Micah Carrick
 *  @copyright  (c) 2011 - Micah Carrick
 *  @version    2.0.5
 *  @license    http://opensource.org/licenses/gpl-3.0.html
 */
 
defined( '_JEXEC' ) or die( 'Restricted access' );
 
jimport( 'joomla.application.component.model' );
 

class AwardpackageModelIpnListener extends JModelLegacy {

		function fsockPost($url,$data) {

			//Parse url
			$web=parse_url($url);

			//build post string
			foreach($data as $i=>$v) {
				$postdata.= $i . "=" . urlencode($v) . "&";
			}

			$postdata.="cmd=_notify-validate";

			//Set the port number
			if($web[scheme] == "https") { $web[port]="443";  $ssl="ssl://"; } else { $web[port]="80"; }

			//Create paypal connection
			$fp=@fsockopen($ssl . $web[host],$web[port],$errnum,$errstr,30);

			//Error checking
			if(!$fp) { echo "$errnum: $errstr"; }

			//Post Data
			else {

			  fputs($fp, "POST $web[path] HTTP/1.1\r\n");
			  fputs($fp, "Host: $web[host]\r\n");
			  fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			  fputs($fp, "Content-length: ".strlen($postdata)."\r\n");
			  fputs($fp, "Connection: close\r\n\r\n");
			  fputs($fp, $postdata . "\r\n\r\n");

			//loop through the response from the server
			while(!feof($fp)) { $info[]=@fgets($fp, 1024); }

					//close fp - we are done with it
					fclose($fp);

					//break up results into a string
					$info=implode(",",$info);

			}

			return $info;

		   }

		function paypal(){

			$paypal[business]="seller_1315922650_biz@gmail.com";
			$paypal[site_url]="http://www.primadita.net/test/";
			$paypal[image_url]="";
			$paypal[success_url]="php_paypal/success.php";
			//$paypal[success_url]="php_paypal/ipn/ipn.php";
			$paypal[cancel_url]="php_paypal/error.php";
			$paypal[notify_url]="php_paypal/ipn/ipn.php";
			$paypal[return_method]="1"; //1=GET 2=POST
			$paypal[currency_code]="USD"; //[USD,GBP,JPY,CAD,EUR]
			$paypal[lc]="US";

			//$paypal[url]="http://www.paypal.com/cgi-bin/webscr";
			//$paypal[url]="https://www.paypal.com/cgi-bin/webscr";
			$paypal[url]="https://www.sandbox.paypal.com/cgi-bin/webscr";
			$paypal[post_method]="fso"; //fso=fsockopen(); curl=curl command line libCurl=php compiled with libCurl support
			$paypal[curl_location]="/usr/local/bin/curl";

			$paypal[bn]="toolkit-php";
			$paypal[cmd]="_xclick";

			//Payment Page Settings
			$paypal[display_comment]="0"; //0=yes 1=no
			$paypal[comment_header]="Comments";
			$paypal[continue_button_text]="Continue >>";
			$paypal[background_color]=""; //""=white 1=black
			$paypal[display_shipping_address]=""; //""=yes 1=no
			$paypal[display_comment]="1"; //""=yes 1=no


			//Product Settings
			$paypal[item_name]="$_POST[item_name]";
			$paypal[item_number]="$_POST[item_number]";
			$paypal[amount]="$_POST[amount]";
			$paypal[on0]="$_POST[on0]";
			$paypal[os0]="$_POST[os0]";
			$paypal[on1]="$_POST[on1]";
			$paypal[os1]="$_POST[os1]";
			$paypal[quantity]="$_POST[quantity]";
			$paypal[edit_quantity]=""; //1=yes ""=no
			$paypal[invoice]="$_POST[invoice]";
			$paypal[tax]="$_POST[tax]";

			//Shipping and Taxes
			$paypal[shipping_amount]="$_POST[shipping_amount]";
			$paypal[shipping_amount_per_item]="";
			$paypal[handling_amount]="";
			$paypal[custom_field]="";

			//Customer Settings
			$paypal[firstname]="$_POST[firstname]";
			$paypal[lastname]="$_POST[lastname]";
			$paypal[address1]="$_POST[address1]";
			$paypal[address2]="$_POST[address2]";
			$paypal[city]="$_POST[city]";
			$paypal[state]="$_POST[state]";
			$paypal[zip]="$_POST[zip]";
			$paypal[email]="$_POST[email]";
			$paypal[phone_1]="$_POST[phone1]";
			$paypal[phone_2]="$_POST[phone2]";
			$paypal[phone_3]="$_POST[phone3]";
			return $paypal;
		}		
}
?>
