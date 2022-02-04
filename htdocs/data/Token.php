<?php

if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH."utils/Utils.php";

class Token
{
	private string $token;
	private array $scopes;
	private int $userID;
	private DateTime $expiresAt;

	/**
	 * @throws Exception
	 */
	protected function __construct()
	{
		$this->token = "";
		$this->scopes = array();
		$this->userID = 0;
		$this->expiresAt = new DateTime();
	}
	public static function getFromArray(?array $row){
		if(!$row) return null;
		$class = new self();
		$str = Utils::getSerializedStr($class, get_object_vars($class), $row);
		if(!$str) return null;

		try{
			return unserialize($str);
		}catch (TypeError $error){
			return null;
		}
	}

	public static function createToken(int $userid, array $scopes, int $aliveTime, ?string &$token): ?Token
	{
		try {
			$token = Utils::generateToken();
			return self::insert($userid, $scopes, $aliveTime, $token);
		}catch (Exception $e){
			echo $e;
			return null;
		}
	}
	public static function createVerifiedToken(int $userid, array $scopes, int $aliveTime, string $verificationToken): ?Token
	{
		try {
			return self::insert($userid, $scopes, $aliveTime, $verificationToken.$_COOKIE["candy"]);
		}catch (Exception $e){
			echo $e;
			return null;
		}
	}

	/**
	 * @throws Exception
	 */
	private static function insert(int $userid, array $scopes, int $aliveTime, string $token): ?Token
	{
		$newToken = new self();
		$scopesRes = array();
		foreach ($scopes as $scope) {
			$scopesRes[] = str_replace(array("\"", "'"), "", $scope);
		}
		$scopesRes = implode(",", $scopesRes);
		$currentDate = new DateTime("now", new DateTimeZone("utc"));
		$createdAt = $currentDate->format("Y-m-d H:i:s");
		$newToken->expiresAt = $currentDate;
		$newToken->expiresAt->add(new DateInterval("PT" . $aliveTime . "S"));
		$expiresAt = $newToken->expiresAt->format("Y-m-d H:i:s");
		$newToken->token = Utils::getSha512($token);

		if(!Utils::getDb()->query("INSERT INTO tokens (token, scopes, createdAt, expiresAt, userId) VALUES ('?s','?s','?s','?s','?s')", $newToken->token, $scopesRes, $createdAt, $expiresAt, $userid)){
			return null;
		}
		return $newToken;
	}

	public function revoke(): bool{
		return Utils::getDb()->query("DELETE FROM tokens WHERE token = '?s'", $this->token);
	}

	/**
	 * @return Token token info
	 */
	static function getTokenInfo(?string $token): ?Token
	{
		if($token==null) return null;
		$row = Utils::getDb()->getRow("SELECT * FROM tokens WHERE token = '?s' LIMIT 1", Utils::getSha512($token));
		return self::getFromArray($row);
	}

	public function equals($token): bool
	{
		return $this->token == Utils::getSha512($token);
	}


	/**
	 * @return array token type
	 */
	public function getScopes(): array
	{
		return $this->scopes;
	}

	public function isInScope(string $scope): bool
	{
		return in_array($scope, $this->scopes);
	}

	/**
	 * @return DateTime expiration time
	 */
	public function getExpiresAt(): DateTime
	{
		return $this->expiresAt;
	}

	/**
	 * @return bool true if token expired
	 */
	public function isExpired(): bool{
		try {
			if(!$this->expiresAt->diff(new DateTime("now", new DateTimeZone("UTC")))->invert){
				$this->revoke();
				return true;
			}
			return false;
		}catch (Exception $e){
			return true;
		}
	}



	public function isValid(string $type): bool{
		return $this->isInScope($type)&&!$this->isExpired();
	}

	/**
	 * @return int
	 */
	public function getUserId(): int
	{
		return $this->userID;
	}
}