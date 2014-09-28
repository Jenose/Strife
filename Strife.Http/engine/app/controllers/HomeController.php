<?php

class HomeController extends BaseController {

	public function getIndex()
	{
		$data = 'a:2:{s:9:"meta-data";a:7:{s:11:"RequestDate";s:19:"2014-09-27 22:10:00";s:10:"RequestUri";s:54:"/c/igames/account/accountid/2879.120?identities=strife";s:14:"ServerHostname";s:12:"50.22.63.203";s:10:"ServerName";s:21:"prod.s2ogi.strife.com";s:8:"ClientIP";s:11:"58.8.209.79";s:11:"ClientAgent";s:35:"S2 Games/Strife/0.4.1.2/windows/x86";s:11:"RequestTime";d:0.013898134231567383;}s:4:"body";a:9:{s:5:"email";s:41:"cd8630f82904102.ppni@asiasoft.s2games.com";s:7:"display";s:22:"asinpp.20140928f0368dc";s:6:"status";s:7:"enabled";s:4:"type";s:6:"player";s:8:"provider";s:1:"3";s:4:"info";a:0:{}s:10:"identities";a:2:{s:4:"game";s:6:"strife";s:10:"identities";a:1:{s:15:"strife.2857.121";a:12:{s:8:"nickname";s:4:"NGTH";s:6:"uniqid";s:4:"1894";s:6:"status";s:7:"enabled";s:10:"playerType";s:6:"player";s:5:"level";i:1;s:10:"experience";s:1:"0";s:11:"description";s:4:"None";s:16:"tutorialProgress";i:1;s:8:"canCraft";b:0;s:13:"canPlayRanked";b:0;s:9:"lanBoosts";b:0;s:8:"ident_id";s:8:"2857.121";}}}s:18:"chatServerIdentity";a:1:{s:10:"serverList";a:1:{i:0;a:2:{s:4:"host";s:18:"rc.chat.strife.com";s:4:"port";i:7033;}}}s:10:"account_id";s:8:"2879.120";}}';
		$print = unserialize($data);
		echo "<pre>";
		print_r($print);
		echo "</pre>";
		//return $this->Response(400);
	}

}
