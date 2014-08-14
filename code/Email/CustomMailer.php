<?php

class CustomMailer extends Mailer
{
	/**
	 * Send a plain-text email.
	 *  
	 * @param string $to
	 * @param string $from
	 * @param string §subject
	 * @param string $plainContent
	 * @param bool $attachedFiles
	 * @param array $customheaders
	 * @return bool
	 */
	function sendPlain($to, $from, $subject, $plainContent, $attachedFiles = false, $customheaders = false) {
		if(Director::isDev())
		{
			return $this->writeEmailToFile($to, $from, $subject, $plainContent, $customheaders);
		}
		elseif(Director::isTest())
		{
			$plainContent = $this->getEmailInfoString($to, $from, $subject, $plainContent, $customheaders);
			$testTo = ContentController::SiteConfig()->SendTestEmailsTo;
			return parent::sendPlain($testTo, $from, $subject, $plainContent, $attachedFiles, $customheaders);
		}
		else
		{
			return parent::sendPlain($to, $from, $subject, $plainContent, $attachedFiles, $customheaders);
		}
		
	}

	/**
	 * Send a multi-part HTML email.
	 * 
	 * @return bool
	 */
	function sendHTML($to, $from, $subject, $htmlContent, $attachedFiles = false, $customheaders = false, $plainContent = false, $inlineImages = false) {
		if(Director::isDev())
		{
			return $this->writeEmailToFile($to, $from, $subject, $htmlContent, $customheaders);
		}
		elseif(Director::isTest())
		{
			$content = $this->getEmailInfoString($to, $from, $subject, $htmlContent, $customheaders);
			$testTo = ContentController::SiteConfig()->SendTestEmailsTo;
			return parent::sendHTML($testTo, $from, $subject, nl2br($content), $attachedFiles, $customheaders, $content, $inlineImages);
		}
		else
		{
			return parent::sendHTML($to, $from, $subject, $htmlContent, $attachedFiles, $customheaders, $plainContent, $inlineImages);
		}
	}


	/**
	 * create a string of the email information to write to a text file or send in test emails
	 */

	public function getEmailInfoString($to, $from, $subject, $body, $headers)
	{
		//build a string out of the email info
		$emailString = "\n*************************************************************";
		$emailString .= "\n*   TO: " . $to;
		$emailString .= "\n*   FROM: " . $from;
		$emailString .= "\n*   SUBJECT: " . $subject;
		$emailString .= "\n*************************************************************";
		$emailString .= "\n*   TIME: " . date('dS M Y - H:i:s');
		$emailString .= "\n*************************************************************";
		$emailString .= "\n" . $this->processHeaders($headers);
		$emailString .= "\n*************************************************************";
		$emailString .= "\n" . $body;
		$emailString .= "\n*************************************************************";
		$emailString .= "\n*   END OF EMAIL ";
		$emailString .= "\n*************************************************************\n\n\n\n\n";

		return $emailString;
	}


	/**
	 * in dev mode write the content of the email to a text file to allow local checking of emails where php mail is not available
	 */
	public function writeEmailToFile($to, $from, $subject, $body, $headers)
	{
		$fileLoc = $_SERVER['DOCUMENT_ROOT'] . "/EmailOutput.txt";
		
		$emailString = $this->getEmailInfoString($to, $from, $subject, $body, $headers);

		//if the file already exists and has content then place the new email content at the start and store in $content
		if(file_exists($fileLoc) && $fileData = file_get_contents($fileLoc))
		{
			$content = $emailString . $fileData;
		}
		else $content = $emailString;

		//replace the contents of the file with new $content
		return file_put_contents($fileLoc, $content);
	}
}
