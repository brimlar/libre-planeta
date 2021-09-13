<?php

class Messages
{
	const Message1 = "<h3>Your listing has been submitted</h3>\nAn e-mail has been sent to the e-mail address you specified, which includes a link that can be used to finalize and publish your listing. <br /><br />Thank you for using Homeplaneta.";
	
	const Message2 = "<h3>Your listing has been deleted</h3>\nYour listing has been deleted and the associated pictures destroyed.<br /><br />Thank you for using Homeplaneta.";

	const Message3 = "<h3>You are attempting to access a non-public listing</h3>\nThis listing hasn't been made public by its owner yet.<br /><br />If you are the owner, and wish for this listing to be made public, please access the e-mail account you specified when you completed the listing form.  In that e-mail account there will be an e-mail (sent by Homeplaneta) which includes a link you can use to publish your listing.";

	const Message4 = "<h3>Thank you for your purchase</h3>\nThank you for your PayPal purchase.  It may take a few minutes for your listing to reflect your transaction.  An e-mail will be sent to you	to acknowledge the transaction, and after you receive that e-mail you can view your Management Page and notice its updated status.<br /><br />If you had already had a paid listing before your purchase, you should notice that your expiration date has been further extended.  If you didn't	previously have a paid listing, you will notice that 1) the advertisements will be gone, 2) you will be able to post up to 18 pictures, and 3) the expiration date has been extended.<br /><br />If you experience any problems during your transaction, feel free to contact us at our customer service e-mail, <a href='mailto:customerservice@homeplaneta.com' style='color: #d7d5bf;'>customerservice@homeplaneta.com</a>, or let us know via our PayPal shop page.";

	const Message5 = "<h3>Action failed</h3>\nThere was a problem processing your transaction.<br /><br />If you feel the error was an issue with Homeplaneta, feel free to report the issue by e-mailing us at customerservice@homeplaneta.com.";

	const DefaultMessage = "<h3>There is nothing here</h3>\nI don't know why you're here -- this is a blank page.  It may <i>appear</i> that this page exists, but it is simply	your imagination working overtime -- again.  Perhaps now would be a good time to grab a tasty beverage and rest the eyes for a few minutes?<br /><br />Feel free to submit a listing by following the link below, though.  Thanks again.";

	public static function retrieve($number)
	{
		if (!isset(self::Message.$number))
		{
			echo self::DefaultMessage;
		}
		else
		{
			echo self::Message.$number;
		}
	}
}
