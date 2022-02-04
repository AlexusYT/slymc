<?php

function send(array $result){
	die(json_encode($result));
}
function sendSuccess(array $fields=[]){
	send(['status' => 'ok']+$fields);
}

function sendError(string $error, array $fields=[]){
	send(['status' => 'error', 'error' => $error]+$fields);
}

function sendBanned($reasonRu, $reasonEn){
	sendError("banned", ['reason' => ["ru" => $reasonRu, "en" => $reasonEn]]);
}

