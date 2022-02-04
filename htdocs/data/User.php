<?php

if (!defined('ROOT_PATH'))  define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH . "data/Token.php";
require_once ROOT_PATH . "utils/SafeMySQL.php";
class User
{
	private int $userID = 0;
	private int $roleID = 0;
	private string $username = "";
	private string $displayUsername = "";
	private string $head = "";
	private string $mail = "";
	private bool $mailVerified = false;
	private string $password = "";
	private ?DateTime $passChangedAt;
	private bool $twoFA = false;
	private ?DateTime $registeredAt;
	private ?DateTime $lastOnlineAt;
	private int $donateMoney = 0;
	private int $money = 0;
	private string $skin = "";
	private bool $license = false;
	private string $cloak = "";
	private string $HWID = "";
	private string $forumStatus = "";
	private string $sign = "";
	private ?Token $token = null;

	public static function getByToken(?string $token): ?User
	{
		if(!$token) return null;
		$sql = "SELECT * FROM tokens 
INNER JOIN users ON users.userID = tokens.userID
WHERE token = '?s' LIMIT 1";

		$user = self::getFromArray(Utils::getDb()->getRow($sql, Utils::getSha512($token)));
		if($user==null) return null;
		if(($arr = Utils::getSavedUser($user->getUserID()))!=null){
			$user->setFromArray($arr);
		}
		return $user;
	}

	public static function getByName(?string $nick): ?User
	{
		if(($user = self::getSaved($nick))!=null) {
			return $user;
		}
		return self::getFromArray(Utils::getDb()->getRow("SELECT * FROM `users` WHERE username = '?s'", strtolower($nick)));
	}

	public static function getByEmail(?string $email): ?User
	{
		if(($user = self::getSaved($email))!=null) {
			return $user;
		}
		return self::getFromArray(Utils::getDb()->getRow("SELECT * FROM `users` WHERE mail = '?s'", $email));
	}

	public static function getById(?int $id, bool $skipCheck=false): ?User
	{
		if(!$skipCheck&&($user = self::getSaved($id))!=null) {
			return $user;
		}
		return self::getFromArray(Utils::getDb()->getRow("SELECT * FROM `users` WHERE userID = '?s'", $id));
	}

	public static function getOnlineUsers(int $serverId): array
	{
		$users = [];
		foreach (Utils::getDb()->getAll("SELECT * FROM playersOnServers INNER JOIN users ON playersOnServers.userID = users.userID WHERE serverID = '?i' AND onServer = 1 ORDER BY users.roleID", $serverId) as $row){
			$users[] = self::getFromArray($row);
		}
		return $users;
	}

	private static function getSaved($criteria): ?User
	{
		if(($arr = Utils::getSavedUser($criteria))!=null) {
			$user = self::getById($arr["playerID"], true);
			$user->setFromArray($arr);
			return $user;
		}
		return null;
	}

	private function setFromArray(array $arr){
		$this->roleID = $arr["roleID"];
		$this->username = $arr["username"];
		$this->displayUsername = $arr["displayUsername"];
		$this->mail = $arr["mail"];
		$this->mailVerified = $arr["mailVerified"];
		$this->password = hash("sha512", $arr["password"]);
		$this->twoFA = $arr["twoFA"];
		$this->donateMoney = $arr["donateMoney"];
		$this->money = $arr["money"];
		$this->HWID = $arr["HWID"];
		$this->forumStatus = $arr["forumStatus"];
		$this->sign = $this->getNewSign();
	}

	public static function getFromArray(?array $row):?User{
		if(!$row) return null;
		$class = new self();
		$result = unserialize(Utils::getSerializedStr($class, get_object_vars($class), $row));
		$result->token = Token::getFromArray($row);
		if($result->head === "-"){
			$result->head = Utils::getDefaultHead();
		}

		if(($arr = Utils::getSavedUser($result->userID))!=null) {
			$result->setFromArray($arr);
		}

		return $result;
	}

	public function resign(): bool{
		$this->sign = $this->getNewSign();
		return Utils::getDb()->query("UPDATE `users` SET sign = '?s' WHERE username = '?s'", $this->sign, $this->username);
	}

	private function __construct()
	{
		$this->registeredAt = new DateTime();
		$this->lastOnlineAt = new DateTime();
		$this->passChangedAt = new DateTime();
	}

	/**
	 * @return string
	 */
	public function getUsername(): string
	{
		return $this->username;
	}

	/**
	 * @return int
	 */
	public function getUserID(): int
	{
		return $this->userID;
	}

	/**
	 * @return Token|null
	 */
	public function getToken(): ?Token
	{
		return $this->token;
	}


	/**
	 * @return int
	 */
	public function getRole(): int
	{
		return $this->roleID;
	}

	/**
	 * @return string
	 */
	public function getHead(): string
	{
		return $this->head;
	}

	/**
	 * @return string
	 */
	public function getMail(): string
	{
		return $this->mail;
	}

	/**
	 * @return bool
	 */
	public function isMailVerified(): bool
	{
		return $this->mailVerified;
	}

	/**
	 * @return string
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	/**
	 * @return bool
	 */
	public function isTwoFA(): bool
	{
		return $this->twoFA;
	}

	/**
	 * @return DateTime|null
	 */
	public function getRegister(): ?DateTime
	{
		return $this->registeredAt;
	}

	/**
	 * @return DateTime|null
	 */
	public function getLastOnline(): ?DateTime
	{
		return $this->lastOnlineAt;
	}

	/**
	 * @return int
	 */
	public function getDonateMoney(): int
	{
		return $this->donateMoney;
	}

	/**
	 * @return int
	 */
	public function getGameMoney(): int
	{
		return $this->money;
	}

	/**
	 * @return string
	 */
	public function getSkin(): string
	{
		return $this->skin;
	}

	/**
	 * @return bool
	 */
	public function isLicense(): bool
	{
		return $this->license;
	}

	/**
	 * @return string
	 */
	public function getCloak(): string
	{
		return $this->cloak;
	}

	/**
	 * @return string
	 */
	public function getHwid(): string
	{
		return $this->HWID;
	}

	/**
	 * @return string
	 */
	public function getForumStatus(): string
	{
		return $this->forumStatus;
	}

	/**
	 * @return string
	 */
	public function getSign(): string
	{
		return $this->sign;
	}

	public function checkSign(): bool
	{
		return $this->sign == $this->getNewSign();
	}

	/**
	 * generates new sign
	 * @return string new sign
	 */
	public function getNewSign(): string
	{
		$secret = "|s|gj8:265JG9>;CK7Pgi0iv_F.,C>aj";
		$str = $this->roleID.$this->username.$this->displayUsername.$this->mail.($this->mailVerified?"1":"0").$this->password.($this->twoFA?"1":"0").$this->donateMoney.$this->money.$this->HWID.$this->forumStatus.$secret;
		return Utils::getSha512($str);
	}

	/**
	 * @return string
	 */
	public function getDisplayUsername(): string
	{
		return $this->displayUsername;
	}

	/**
	 * @return DateTime|null
	 */
	public function getPassChangedAt(): ?DateTime
	{
		return $this->passChangedAt;
	}
}