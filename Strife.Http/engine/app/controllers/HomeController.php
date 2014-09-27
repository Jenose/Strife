<?php

class HomeController extends BaseController {

	public function getIndex()
	{
		/*$data = 'a:2:{s:9:"meta-data";a:7:{s:11:"RequestDate";s:19:"2014-09-26 18:54:25";s:10:"RequestUri";s:34:"/c/strife/ident/accountid/2787.125";s:14:"ServerHostname";s:12:"50.22.63.204";s:10:"ServerName";s:21:"prod.s2ogi.strife.com";s:8:"ClientIP";s:11:"58.8.244.72";s:11:"ClientAgent";s:35:"S2 Games/Strife/0.4.1.2/windows/x86";s:11:"RequestTime";d:0.051862955093383789;}s:4:"body";a:11:{s:8:"nickname";s:6:"Jenose";s:6:"uniqid";s:4:"7791";s:6:"status";s:7:"enabled";s:10:"playerType";s:6:"player";s:5:"level";i:1;s:10:"experience";s:1:"0";s:11:"description";s:4:"None";s:16:"tutorialProgress";s:1:"0";s:8:"canCraft";b:0;s:13:"canPlayRanked";b:0;s:8:"ident_id";s:8:"2763.126";}}';
		$print = unserialize($data);
		echo "<pre>";
		print_r($print);
		echo "</pre>";*/
		return $this->Response(400);
	}

}
