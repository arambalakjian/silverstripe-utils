<?php

Object::add_extension('SiteConfig', 'CustomMailerSiteConfig');
Email::set_mailer(new CustomMailer()); 
