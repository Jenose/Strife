<?php

class CController extends BaseController {

	var $identIncrement;
	var $shard;

	public function postIgames($action)
	{
		switch ($action) 
		{
			case 'asiasoftSession':
				file_put_contents('asiasoftSession.txt', serialize($_POST));
				return $this->asiasoftSession(
					Input::get('id'), 
					Input::get('password'), 
					Input::get('identities'),
					Input::get('akey')
				);
				break;
			
			default:
				Log::info(serialize($_POST));
				break;
		}
	}

	public function asiasoftSession($id, $password, $identities, $akey = null)
	{
		$account = DB::table('account')
					->where('email', $id)
					->first();

		if($account == null)
		{
			$this->response['body'] = null;
			$this->response['error'] = array("error_account_not_found");
			return $this->Response(401);
		}
		else if($account->password != $password)
		{
			$this->response['body'] = null;
			$this->response['error'] = array("error_password_incorrect");
			return $this->Response(401);
		}

		$account->identities = DB::table('account_identity')
					->where('account_id', $account->account_id)
					->first();

		$body_identities = array();
		if($account->identities != null)
		{
			$body_identities = array(
				'strife.'.$account->account_id => array(
					'nickname' => $account->identities->nickname,
					'uniqid' => $account->identities->uniqid,
					'status' => $account->identities->status,
					'playerType' => $account->identities->playerType,
					'level' => $account->identities->level,
					'experience' => $account->identities->experience,
					'description' => $account->identities->description,
					'tutorialProgress' => $account->identities->tutorialProgress,
					'canCraft' => $account->identities->canCraft,
					'canPlayRanked' => $account->identities->canPlayRanked,
					'ident_id' => $account->account_id,
				)
			);
		}
		

		$this->response['body'] = array(
				'email' => $account->email,
				'display' => $account->display,
				'status' => $account->status,
				'type' => $account->type,
				'provider' => $account->provider,
				'info' => array(
					// todo
					),
				'identities' => array(
					'game' => 'strife',
					'identities' => $body_identities
					),
				'chatServerIdentity' => array(
					'serverList' => array(
						array(
							'host' => 'rc.chat.strife.com',
							'port' => 7033
							)
						),
					),
				'clientSession' => array(
					'sessionKey' => sha1($account->account_id.time()),
					'type' => 'client',
					'clientData' => array(
						'region' => 'TH',
						'ip' => $_SERVER['REMOTE_ADDR'],
						'longitude' => 100.50140380859,
						'latitude' => 13.753999710083,
						'country' => 'TH'
						),
					'isAdult' => 1
					),
				'account_id' => $account->account_id,
			);
		
		unset($this->response['error']);
		return $this->Response();
	}

	public function getStrife($func, $action, $value)
	{
		switch ($func) {
			case 'ident':
				switch ($action) {
					case 'identid':
						return $this->ident_identid($value);
						break;
					case 'playerResources':
						return $this->ident_playerResources($value);
						break;
				}
				break;

			case 'profile':
				switch ($action) {
					case 'identid':
						return $this->identid($value);
						break;
				}
				break;
			
			default:
				Log::info(serialize(array('func' => $func, 'action' => $action, 'value' => $value, '_GET' => $_GET)));
				break;
		}
	}

	public function postStrife($func, $action, $value)
	{
		switch ($func) {
			case 'ident':
				switch ($action) {
					case 'accountid':
						return $this->ident_accountid($value);
						break;
					case 'identid':
						return $this->ident_identid($value);
						break;
				}
				break;
			
			default:
				Log::info(serialize(array('func' => $func, 'action' => $action, 'value' => $value, '_POST' => $_POST)));
				break;
		}
	}

	public function ident_identid($id)
	{
		$accountid = ($id * 1000);
		$account = DB::table('account')
					->where('account_id', $accountid)
					->first();

		$account->identities = DB::table('account_identity')
					->where('account_id', $account->account_id)
					->first();

		$this->response['body'] = array();
		if($account->identities != null)
		{
			$this->response['body'] = array(
			'nickname' => $account->identities->nickname,
			'uniqid' => $account->identities->uniqid,
			'status' => $account->identities->status,
			'playerType' => $account->identities->playerType,
			'level' => $account->identities->level,
			'experience' => $account->identities->experience,
			'description' => $account->identities->description,
			'tutorialProgress' => $account->identities->tutorialProgress,
			'canCraft' => $account->identities->canCraft,
			'canPlayRanked' => $account->identities->canPlayRanked,
			'identStats' => array(
				'stats' => array(
					'matchmakingIdentStats' => array(
						),
					'matchmakingHeroStats' => array(
						),
					'matchmakingFamiliarStats' => array(
						),
					'matchmakingHeroFamiliarStats' => array(
						),
					),
				),
			'pets' => array(
				'pets' => array(

					),
				),
			);
		}
		else
		{
			$this->response['body'] = array(
			'nickname' => null,
			'uniqid' => null,
			'status' => null,
			'playerType' => null,
			'level' => null,
			'experience' => null,
			'description' => null,
			'tutorialProgress' => null,
			'canCraft' => null,
			'canPlayRanked' => null,
			'identStats' => array(
				'stats' => array(
					'matchmakingIdentStats' => array(
						),
					'matchmakingHeroStats' => array(
						),
					'matchmakingFamiliarStats' => array(
						),
					'matchmakingHeroFamiliarStats' => array(
						),
					),
				),
			'pets' => array(
				'pets' => array(

					),
				),
			);
		}
		
		unset($this->response['error']);
		return $this->Response();
	}

	public function ident_playerResources($id)
	{
		//$uid = str_replace(Config::get('app.game_id').'.', '', $id);
		$account = DB::table('account')
					->where('account_idcc', $id)
					->first();

		$json = '{"analytics":[],"favouritewatchIDs":[],"friendDatabase":[],"groupExpanded":{"offline":true,"online":true,"recently":false,"search":false},"heroBuilds":[],"launcherMusicEnabled":true,"optionsKeybinds":{"gameActivateTool00false":{"action":"ActivateTool","bindTable":"game","currentBinding":"Q","impulse":"false","num":"0","param":"0"},"gameActivateTool01false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"0"},"gameActivateTool1000false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"100"},"gameActivateTool1001false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"100"},"gameActivateTool1010false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"101"},"gameActivateTool1011false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"101"},"gameActivateTool1020false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"102"},"gameActivateTool1021false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"102"},"gameActivateTool1030false":{"action":"ActivateTool","bindTable":"game","currentBinding":"X","impulse":"false","num":"0","param":"103"},"gameActivateTool1031false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"103"},"gameActivateTool1040false":{"action":"ActivateTool","bindTable":"game","currentBinding":"C","impulse":"false","num":"0","param":"104"},"gameActivateTool1041false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"104"},"gameActivateTool10false":{"action":"ActivateTool","bindTable":"game","currentBinding":"W","impulse":"false","num":"0","param":"1"},"gameActivateTool110false":{"action":"ActivateTool","bindTable":"game","currentBinding":"G","impulse":"false","num":"0","param":"11"},"gameActivateTool111false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"11"},"gameActivateTool11false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"1"},"gameActivateTool180false":{"action":"ActivateTool","bindTable":"game","currentBinding":"F","impulse":"false","num":"0","param":"18"},"gameActivateTool181false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"18"},"gameActivateTool20false":{"action":"ActivateTool","bindTable":"game","currentBinding":"E","impulse":"false","num":"0","param":"2"},"gameActivateTool21false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"2"},"gameActivateTool30false":{"action":"ActivateTool","bindTable":"game","currentBinding":"R","impulse":"false","num":"0","param":"3"},"gameActivateTool31false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"3"},"gameActivateTool80false":{"action":"ActivateTool","bindTable":"game","currentBinding":"H","impulse":"false","num":"0","param":"8"},"gameActivateTool81false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"8"},"gameActivateTool960false":{"action":"ActivateTool","bindTable":"game","currentBinding":"D","impulse":"false","num":"0","param":"96"},"gameActivateTool961false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"96"},"gameActivateTool970false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"97"},"gameActivateTool971false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"97"},"gameActivateTool980false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"98"},"gameActivateTool981false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"98"},"gameActivateTool990false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"99"},"gameActivateTool991false":{"action":"ActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"99"},"gameActivateUsableItem10false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"1","impulse":"false","num":"0","param":"1"},"gameActivateUsableItem11false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"1"},"gameActivateUsableItem20false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"2","impulse":"false","num":"0","param":"2"},"gameActivateUsableItem21false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"2"},"gameActivateUsableItem30false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"3","impulse":"false","num":"0","param":"3"},"gameActivateUsableItem31false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"3"},"gameActivateUsableItem40false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"4","impulse":"false","num":"0","param":"4"},"gameActivateUsableItem41false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"4"},"gameActivateUsableItem50false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"5","impulse":"false","num":"0","param":"5"},"gameActivateUsableItem51false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"5"},"gameActivateUsableItem60false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"6","impulse":"false","num":"0","param":"6"},"gameActivateUsableItem61false":{"action":"ActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"6"},"gameCameraDrag0false":{"action":"CameraDrag","bindTable":"game","currentBinding":"MOUSEM","impulse":"false","num":"0","param":""},"gameCameraDrag1false":{"action":"CameraDrag","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameCancel0false":{"action":"Cancel","bindTable":"game","currentBinding":"ESC","impulse":"false","num":"0","param":""},"gameCancel1false":{"action":"Cancel","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameCenter0false":{"action":"Center","bindTable":"game","currentBinding":"SPACE","impulse":"false","num":"0","param":""},"gameCenter1false":{"action":"Center","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameChatTeam0true":{"action":"ChatTeam","bindTable":"game","currentBinding":"ENTER","impulse":"true","num":"0","param":""},"gameChatTeam1true":{"action":"ChatTeam","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameCommanderFocusToggle0false":{"action":"CommanderFocusToggle","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":""},"gameCommanderFocusToggle1false":{"action":"CommanderFocusToggle","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameCommanderModifierCtrl0false":{"action":"CommanderModifierCtrl","bindTable":"game","currentBinding":"CTRL","impulse":"false","num":"0","param":""},"gameCommanderModifierCtrl1false":{"action":"CommanderModifierCtrl","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameCommanderModifierShift0false":{"action":"CommanderModifierShift","bindTable":"game","currentBinding":"SHIFT","impulse":"false","num":"0","param":""},"gameCommanderModifierShift1false":{"action":"CommanderModifierShift","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameCommanderPing0true":{"action":"CommanderPing","bindTable":"game","currentBinding":"ALT+MOUSEL","impulse":"true","num":"0","param":""},"gameCommanderPing1true":{"action":"CommanderPing","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameLevelupAbility00true":{"action":"LevelupAbility","bindTable":"game","currentBinding":"SHIFT+Q","impulse":"true","num":"0","param":"0"},"gameLevelupAbility01true":{"action":"LevelupAbility","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":"0"},"gameLevelupAbility10true":{"action":"LevelupAbility","bindTable":"game","currentBinding":"SHIFT+W","impulse":"true","num":"0","param":"1"},"gameLevelupAbility11true":{"action":"LevelupAbility","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":"1"},"gameLevelupAbility20true":{"action":"LevelupAbility","bindTable":"game","currentBinding":"SHIFT+E","impulse":"true","num":"0","param":"2"},"gameLevelupAbility21true":{"action":"LevelupAbility","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":"2"},"gameLevelupAbility30true":{"action":"LevelupAbility","bindTable":"game","currentBinding":"SHIFT+R","impulse":"true","num":"0","param":"3"},"gameLevelupAbility31true":{"action":"LevelupAbility","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":"3"},"gameMoveBack0false":{"action":"MoveBack","bindTable":"game","currentBinding":"DOWN","impulse":"false","num":"0","param":""},"gameMoveBack1false":{"action":"MoveBack","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameMoveForward0false":{"action":"MoveForward","bindTable":"game","currentBinding":"UP","impulse":"false","num":"0","param":""},"gameMoveForward1false":{"action":"MoveForward","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameMoveLeft0false":{"action":"MoveLeft","bindTable":"game","currentBinding":"LEFT","impulse":"false","num":"0","param":""},"gameMoveLeft1false":{"action":"MoveLeft","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameMoveRight0false":{"action":"MoveRight","bindTable":"game","currentBinding":"RIGHT","impulse":"false","num":"0","param":""},"gameMoveRight1false":{"action":"MoveRight","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameOrderAttack0true":{"action":"OrderAttack","bindTable":"game","currentBinding":"A","impulse":"true","num":"0","param":""},"gameOrderAttack1true":{"action":"OrderAttack","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameOrderCancelAndHold0true":{"action":"OrderCancelAndHold","bindTable":"game","currentBinding":"S","impulse":"true","num":"0","param":""},"gameOrderCancelAndHold1true":{"action":"OrderCancelAndHold","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameOrderEmote0true":{"action":"OrderEmote","bindTable":"game","currentBinding":"Y","impulse":"true","num":"0","param":""},"gameOrderEmote1true":{"action":"OrderEmote","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameOrderQuickAttack0true":{"action":"OrderQuickAttack","bindTable":"game","currentBinding":"None","impulse":"true","num":"0","param":""},"gameOrderQuickAttack1true":{"action":"OrderQuickAttack","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameQuickActivateTool00false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"0"},"gameQuickActivateTool01false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"0"},"gameQuickActivateTool10false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"1"},"gameQuickActivateTool11false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"1"},"gameQuickActivateTool180false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"18"},"gameQuickActivateTool181false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"18"},"gameQuickActivateTool20false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"2"},"gameQuickActivateTool21false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"2"},"gameQuickActivateTool30false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"3"},"gameQuickActivateTool31false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"3"},"gameQuickActivateTool960false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"96"},"gameQuickActivateTool961false":{"action":"QuickActivateTool","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"96"},"gameQuickActivateUsableItem10false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"1"},"gameQuickActivateUsableItem11false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"1"},"gameQuickActivateUsableItem20false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"2"},"gameQuickActivateUsableItem21false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"2"},"gameQuickActivateUsableItem30false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"3"},"gameQuickActivateUsableItem31false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"3"},"gameQuickActivateUsableItem40false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"4"},"gameQuickActivateUsableItem41false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"4"},"gameQuickActivateUsableItem50false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"5"},"gameQuickActivateUsableItem51false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"5"},"gameQuickActivateUsableItem60false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"6"},"gameQuickActivateUsableItem61false":{"action":"QuickActivateUsableItem","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"6"},"gameSelectHero0true":{"action":"SelectHero","bindTable":"game","currentBinding":"F1","impulse":"true","num":"0","param":""},"gameSelectHero1true":{"action":"SelectHero","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameShowChat0false":{"action":"ShowChat","bindTable":"game","currentBinding":"Z","impulse":"false","num":"0","param":""},"gameShowChat1false":{"action":"ShowChat","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"gameToggleCvarsound_muteMusic0true":{"action":"ToggleCvar","bindTable":"game","currentBinding":"None","impulse":"true","num":"0","param":"sound_muteMusic"},"gameToggleCvarsound_muteMusic1true":{"action":"ToggleCvar","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":"sound_muteMusic"},"gameToggleLockedCam0true":{"action":"ToggleLockedCam","bindTable":"game","currentBinding":"L","impulse":"true","num":"0","param":""},"gameToggleLockedCam1true":{"action":"ToggleLockedCam","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameToggleMute0true":{"action":"ToggleMute","bindTable":"game","currentBinding":"None","impulse":"true","num":"0","param":""},"gameToggleMute1true":{"action":"ToggleMute","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameToggleShop0true":{"action":"ToggleShop","bindTable":"game","currentBinding":"B","impulse":"true","num":"0","param":""},"gameToggleShop1true":{"action":"ToggleShop","bindTable":"game","currentBinding":"None","impulse":"true","num":"1","param":""},"gameTriggerTogglegamePurchaseCurrentValidBookmark0false":{"action":"TriggerToggle","bindTable":"game","currentBinding":"None","impulse":"false","num":"0","param":"gamePurchaseCurrentValidBookmark"},"gameTriggerTogglegamePurchaseCurrentValidBookmark1false":{"action":"TriggerToggle","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"gamePurchaseCurrentValidBookmark"},"gameTriggerTogglegameShowMoreInfo0false":{"action":"TriggerToggle","bindTable":"game","currentBinding":"TAB","impulse":"false","num":"0","param":"gameShowMoreInfo"},"gameTriggerTogglegameShowMoreInfo1false":{"action":"TriggerToggle","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"gameShowMoreInfo"},"gameTriggerTogglegameShowSkills0false":{"action":"TriggerToggle","bindTable":"game","currentBinding":"~","impulse":"false","num":"0","param":"gameShowSkills"},"gameTriggerTogglegameShowSkills1false":{"action":"TriggerToggle","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":"gameShowSkills"},"gameVoicePushToTalk0false":{"action":"VoicePushToTalk","bindTable":"game","currentBinding":"T","impulse":"false","num":"0","param":""},"gameVoicePushToTalk1false":{"action":"VoicePushToTalk","bindTable":"game","currentBinding":"None","impulse":"false","num":"1","param":""},"spectatorTriggerToggleSpecCinematicMode0false":{"action":"TriggerToggle","bindTable":"spectator","currentBinding":"CTRL","impulse":"false","num":"0","param":"SpecCinematicMode"},"spectatorTriggerToggleSpecCinematicMode1false":{"action":"TriggerToggle","bindTable":"spectator","currentBinding":"None","impulse":"false","num":"1","param":"SpecCinematicMode"}},"optionsMenu":{"Cam_mode":{"value":"0"},"_backpackVis":{"value":false},"_bossTimerVis":{"value":false},"_expWheelVis":{"value":false},"_game_always_show_hero_levels":{"value":false},"_game_always_show_hero_names":{"value":false},"_game_healthLerping":{"value":true},"_game_screenFeedbackVis":{"value":true},"_heroInfoVis":{"value":false},"_heroVitalsVis":{"value":false},"_pushOrbVis":{"value":false},"_ui_friendNotificationSound":{"value":true},"cam_edgeScroll":{"value":true},"cam_edgeScrollPixelLeniencyX":{"value":"0"},"cam_edgeScrollPixelLeniencyY":{"value":"0"},"cam_invertMouseDrag":{"value":false},"cam_scrollspeed":{"value":"3000.0000"},"cg_allyHealthColor":{"value":"0 1 0"},"cg_attackMovePrimary":{"value":false},"cg_cameraCenterOnRespawn":{"value":true},"cg_censorChat":{"value":true},"cg_chatTimestamps":{"value":true},"cg_cmdrDoubleActivate":{"value":true},"cg_constrainCursor":{"value":true},"cg_enableMinimapRightclick":{"value":true},"cg_enemyHealthColor":{"value":"1 0 0"},"cg_globalQuickCast":{"value":false},"cg_instantCast":{"value":false},"cg_muteAnnouncerVoice":{"value":false},"cg_neutralHealthColor":{"value":"0.823 0 1"},"cg_selfHealthColor":{"value":"1 1 0"},"cg_unitVoiceResponses":{"value":true},"cg_unitVoiceResponsesDelay":{"value":"10000"},"model_quality":{"value":"med"},"options_soundQuality":{"value":"1"},"px_enable":{"value":false},"px_enableApex":{"value":false},"sound_disable":{"value":false},"sound_interfaceVolume":{"value":"0.6000"},"sound_masterVolume":{"value":"0.6008"},"sound_musicVolume":{"value":"0.3000"},"sound_mute":{"value":false},"sound_muteMusic":{"value":false},"sound_numChannels":{"value":"128"},"sound_playbackDriver":{"value":"0"},"sound_sfxVolume":{"value":"0.7500"},"ui_swapMinimap":{"value":false},"ui_whisperRequiresFriendship":{"value":false},"voice_activationStartLevel":{"value":"0.6000"},"voice_audioDampen":{"value":"0.6000"},"voice_micGainDB":{"value":"0.0000"},"voice_recordingDriver":{"value":"8b7964d7-0f8f-40dc-a592-24a20cf39c38"},"voice_voiceActivation":{"value":false},"voice_volume":{"value":"1.0000"}},"progression":{"lastAccountLevel":1,"lastExperience":0},"recentlyPlayedWith":[],"subscribedGroups":[],"tosSigned":1,"unixTimestamp":"1411661743"}';

		$this->response['body'] = array(
			'identIncrement' => Config::get('app.game_id'),
			'shard' => sprintf('%03d', $account->uid),
			'playerResources' => array(
				'lua_db_5' => $json
			),
		);

		unset($this->response['error']);
		return $this->Response();
	}

	public function ident_accountid($id)
	{
		$accountid = ($id * 1000);
		$account = DB::table('account')
					->where('account_id', $accountid)
					->first();

		DB::table('account_identity')->insert(
		    array(
		    	'uniqid' => Input::get('uniqid'),
		    	'account_id' => $accountid,
		    	'nickname' => Input::get('nickname'),
		    	'playerType' => 'player',
		    	'description' => 'None',
		    	'ident_id' => $id,
		    )
		);

		$account->identities = DB::table('account_identity')
					->where('account_id', $accountid)
					->first();

		$this->response['body'] = array(
			'nickname' => $account->identities->nickname,
			'uniqid' => $account->identities->uniqid,
			'status' => $account->identities->status,
			'playerType' => $account->identities->playerType,
			'level' => $account->identities->level,
			'experience' => $account->identities->experience,
			'description' => $account->identities->description,
			'tutorialProgress' => $account->identities->tutorialProgress,
			'canCraft' => $account->identities->canCraft,
			'canPlayRanked' => $account->identities->canPlayRanked,
			'ident_id' => $id,
		);

		unset($this->response['error']);
		return $this->Response();
	}

	public function profile_identid($id)
	{
		$uid = str_replace(Config::get('app.game_id').'.', '', $id);
		$account = DB::table('accounts')
					->where('uid', $uid)
					->first();

		$_rankedHeroRatings = array(
			'Hero_Caprice',
			'Hero_Ace',
			'Hero_Bastion',
			'Hero_Bo',
			'Hero_Carter',
			'Hero_GunSlinger',
			'Hero_Hale',
			'Hero_Ladytinder',
			'Hero_Magebane',
			'Hero_Malady',
			'Hero_Minerva',
			'Hero_Moxie',
			'Hero_Predator',
			'Hero_Ray',
			'Hero_Rook',
			'Hero_Vermillion',
			'Hero_Vex',
			'Hero_Waterbender',
			'Hero_Tyme',
			'Hero_Versat',
			'Hero_Shank',
			'Hero_Claudessa',
			'Hero_Fetterstone',
			'Hero_Trixie',
			'Hero_Blazer',
			'Hero_Harrower',
		);

		$rankedHeroRatings_ = array();
		foreach ($_rankedHeroRatings as $value)
		{
			$rankedHeroRatings_[$value] = array(
				'division' => 'bronze',
				'rank' => 1000,
				'losses' => 0,
				'wins' => 0,
				'seasonLosses' => 0,
				'seasonWins' => 0,
				'gamesBelowBracket' => 0,
				'gamesAboveBracket' => 0,
			);
		}

		$ident_id = Config::get('app.game_id').'.'.sprintf('%03d', $account->uid);
		$this->response['body'] = array(
			'nickname' => $account->login,
			'uniqid' => $account->uid,
			'playerType' => 'player',
			'level' => 99,
			'clientAccountIcons' => array(
				'clientAccountIcons' => array(
					$ident_id.'.209' => array(
						'active' => 1,
						'productIncrement' => 209,
						'transactionIncrement' => 0,
						'stringTableName' => 'default',
						'localPath' => '/ui/shared/textures/account_icons/default.tga',
						'webPath' => '//cdn.strife.com/images/game/account_icons/default.jpg',
					), 
				),
			),
			'clientAccountTitles' => array(
				'clientAccountTitles' => array(

				),
				'ident_id' => $ident_id,
			),
			'clientAccountColors' => array(
				'clientAccountColors' => array(

				),
				'ident_id' => $ident_id,
			),
			'matchHistoryList' => array(
				'matchHistoryList' => array(

				),
			),
			'rankedHeroRatings' => $rankedHeroRatings_,
			'ident_id' => $ident_id
		);

		unset($this->response['error']);
		return $this->Response();
	}

	public function getManifest()
	{
		$serializeData = 'a:2:{s:9:"meta-data";a:7:{s:11:"RequestDate";s:19:"2014-09-26 03:14:38";s:10:"RequestUri";s:95:"/c/strife/manifest/product/strife/branch/prod/buildType/client/os/windows/arch/x86/version/live";s:14:"ServerHostname";s:12:"50.22.63.204";s:10:"ServerName";s:21:"prod.s2ogi.strife.com";s:8:"ClientIP";s:12:"61.90.69.116";s:11:"ClientAgent";s:35:"S2 Games/Strife/0.4.1.2/windows/x86";s:11:"RequestTime";d:0.026453018188476562;}s:4:"body";a:1:{s:8:"manifest";a:14:{s:11:"manifest_id";s:3:"375";s:7:"product";s:6:"strife";s:6:"branch";s:4:"prod";s:9:"buildType";s:6:"client";s:2:"os";s:7:"windows";s:4:"arch";s:3:"x86";s:5:"major";s:1:"0";s:5:"minor";s:1:"4";s:5:"micro";s:1:"1";s:6:"hotfix";s:1:"2";s:12:"manifestHash";s:40:"4b6dd3acd0adcb7e5d087a9eaac7f24625b95ce2";s:17:"containerChecksum";s:8:"093f7568";s:4:"date";s:19:"2014-09-16 19:23:03";s:13:"downloadHosts";a:3:{i:0;s:24:"http://patch.strife.com/";i:1;s:47:"https://cdnbackup.s2games.com/patch.strife.com/";i:2;s:46:"http://cdnbackup.s2games.com/patch.strife.com/";}}}}';
		$data = unserialize($serializeData);
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";*/
		echo $serializeData;
	}

}
