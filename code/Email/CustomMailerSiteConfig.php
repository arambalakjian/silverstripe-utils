<?php
 
class CustomMailerSiteConfig extends DataObjectDecorator {
	
	function extraStatics() {
		return array(
			'db' => array(
				'SendTestEmailsTo' => 'Text'
			)
		);
	}

	public function updateCMSFields(FieldSet &$fields) {
		
		if(!Director::isLive())
		{
			$fields->addFieldToTab("Root.Main", new TextField("SendTestEmailsTo", 'Send all emails to these addresses when in test mode (on the stage site)'));		
		}
   	}
}