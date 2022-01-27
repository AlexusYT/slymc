<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli( 'sql209.epizy.com', 'epiz_30774026', 'x6hyKkXWBV', 'epiz_30774026_slymc');

$conn->set_charset("utf8mb4");

//получение инфы о серверах
$serversInformation = array();
foreach ($conn->query("SELECT * FROM `servers` INNER JOIN `modpacks` ON servers.modPackID = modpacks.modPackID INNER JOIN `serverStats` ON servers.statsID = serverStats.statID")->fetch_all(MYSQLI_ASSOC) as $server) {
    $serverInfo = ['serverName' => $server['serverName']];
    $serverInfo += ['serverIP' => $server['serverIP']];
    $serverInfo += ['serverPort' => intval($server['serverPort'])];
    $serverInfo += ['modPackName' => $server['modPackName']];
    $serverInfo += ['serverDescription' => $server['serverDescription']];
    $serverInfo += ['serverThumbnail' => $server['serverThumbnail']];
    //Моды:
    $serverInfo += ['modList' => $modsInfo = getMods($conn, $server['modPackID'])];
    //Игроки:
    $serverInfo += ['playersOnline' => getPlayers($conn, $server['serverID'])];
    //Статистика:
    $stats = array();
    $stats[] = ['statName' => 'status', 'statValue' => $server['status']];
    $stats[] = ['statName' => 'tps', 'statValue' => intval($server['tps'])];
    $stats[] = ['statName' => 'uptime', 'statValue' => intval($server['uptime'])];
    $stats[] = ['statName' => 'reboot', 'statValue' => intval($server['reboot'])];
    $stats[] = ['statName' => 'lastWipe', 'statValue' => intval($server['lastWipe'])];
    $stats[] = ['statName' => 'nextWipe', 'statValue' => intval($server['nextWipe'])];
    $stats[] = ['statName' => 'maxOnline', 'statValue' => intval($server['maxOnline'])];
    $serverInfo += ['stats' => $stats];
    $serversInformation[] = $serverInfo;
}

echo json_encode(array('servers' => $serversInformation)); //парсинг в json.
$conn->close();


function getPlayers(mysqli $conn, int $serverId): array
{
	$defaultHead = base64_encode(file_get_contents("../assets/steveHead.png"));

    $playersInfo = array();
    foreach ($conn->query("SELECT users.roleID, users.displayUsername, users.head FROM playersOnServers INNER JOIN users ON playersOnServers.userID = users.userID WHERE serverID = $serverId AND onServer = 1 ORDER BY users.roleID ASC")->fetch_all(MYSQLI_ASSOC) as $players){
		if($players['head'] === "-") $players['head'] = $defaultHead;
        $playersInfo[] = $players;
    }
    return $playersInfo;
}

function getMods(mysqli $conn, int $modpackId): array
{
	return $conn->query("SELECT mods.name, mods.version, mods.description FROM mods INNER JOIN modLists ON modLists.modID = mods.modID WHERE modPackID = $modpackId")->fetch_all(MYSQLI_ASSOC);
}
