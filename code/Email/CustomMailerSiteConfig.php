<?php
 
class CustomMailerSiteConfig extends DataExtension {
	
	static $db = array(
		'SendTestEmailsTo' => 'Text'
	);
	
	public function updateCMSFields(FieldList &$fields) {
		
		if(!Director::isLive())
		{
			$fields->addFieldToTab("Root.Main", new TextField("SendTestEmailsTo", 'Send all emails to these addresses when in test mode (on the stage site)'));		
		}
   	}
}