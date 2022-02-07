<?php

//получение инфы о серверах
$serversInformation = [];
foreach (Utils::getDb()->getAll("SELECT * FROM `servers` INNER JOIN `modpacks` ON servers.modPackID = modpacks.modPackID") as $server) {
	$serverInfo = [];
	foreach ($server as $item => $value){
		if($item==="modPackID")//моды
			$serverInfo += ['modList' => Utils::getDb()->getAll("SELECT mods.name, mods.version, mods.description FROM mods INNER JOIN modLists ON modLists.modID = mods.modID WHERE modPackID = '?s'", $server['modPackID'])];
		else if($item==="serverID")//игроки
			$serverInfo += ['playersOnline' => getPlayers($value)];
		else if($item==="statsID") $serverInfo += ['stats' => getStats($value)];//статы
		else if($item==="serverPort") $serverInfo += ['serverPort' => intval($value)];
		else $serverInfo += [$item => $value];
	}
	$serversInformation[] = $serverInfo;
}
sendSuccess(['servers' => $serversInformation]); //парсинг в json.

function getStats(int $statsId): array
{
	$stats = [];
	foreach (Utils::getDb()->getRow("SELECT * FROM serverStats WHERE serverStats.statsID = '?s'", $statsId) as $item => $value){
		if($item === "statsID") continue;
		$newValue = intval($value);
		if(strval($newValue)!==$value) $newValue = $value;
		$stats[] = ["statName" => $item, "statValue" => $newValue];
	}
	return $stats;
}

function getPlayers(int $serverId): array
{
	$playersInfo = [];
	foreach (User::getOnlineUsers($serverId) as $player){
		$playersInfo[] = ["roleID" => $player->getRole(), "displayUsername" => $player->getDisplayUsername(), "head" => $player->getHead()];
	}
	return $playersInfo;
}
