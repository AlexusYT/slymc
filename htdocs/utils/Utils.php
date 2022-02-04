<?php
if (!defined('ROOT_PATH')) define('ROOT_PATH', substr(__DIR__, 0, strpos(__DIR__, "htdocs")+6) . DIRECTORY_SEPARATOR);

use PHPMailer\PHPMailer\PHPMailer;
require_once ROOT_PATH."utils/SafeMySQL.php";

class Utils
{
	public static function closeSQLConnection()
	{
		self::getDb()->close();
	}
	public static function getDb(): SafeMySQL
	{
		if(!isset($GLOBALS["sqldb"])) {
			$GLOBALS["sqldb"] = new SafeMySQL(array('host' => 'sql209.epizy.com', 'user' => 'epiz_30774026', 'pass' => 'x6hyKkXWBV', 'db' => 'epiz_30774026_slymc', 'charset' => 'utf8mb4'));
			register_shutdown_function('Utils::closeSQLConnection');
		}
		return $GLOBALS["sqldb"];
	}

	public static function generateToken(): string
	{
		try {
			return bin2hex(random_bytes(64));
		} catch (Exception $e) {
			return hash("sha512", hash("sha256", microtime() . $secret . (time() + time())));//FIXME secret undefined
		}
	}



	public static function sendEmail(string $email, string $nick, string $body, string $title): bool
	{

		require ROOT_PATH.'PHPMailer/PHPMailer.php';
		require ROOT_PATH.'PHPMailer/SMTP.php';
		require ROOT_PATH.'PHPMailer/Exception.php';


		$body = str_replace("__hostname__", $_SERVER['HTTPS'] == "" ? "http://" : "https://" . $_SERVER['HTTP_HOST'], $body);
		$mail = new PHPMailer(true);
		try {

			$mail->IsSMTP();
			$mail->CharSet = PHPMailer::CHARSET_UTF8;
			$mail->SMTPAuth = true;
			$mail->SMTPSecure = "ssl";
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 465;
			$mail->Username = "alexusxxyt@gmail.com";
			$mail->Password = "alexus_youtube_channel";

			$mail->SetFrom('no-reply@slymc.ru', 'SlyMC');
			$mail->Subject = $title;

			$mail->MsgHTML($body);

			$mail->addAddress($email, $nick);

			return $mail->send();
		} catch (Exception $e) {
            echo $e;
			echo "Произошла ошибка при отправке сообщения на почту";
			return false;
		}
	}

	public static function getSerializedStr(object $class, array $classFields, $row): ?string
	{
		$className = get_class($class);
		$str = "";
		$keysRow = array_keys($row);
		foreach ($classFields as $fName => $fValue) {
			//$name = ucfirst($fName);
			$fieldValue = "";
			$isInRow = in_array($fName, $keysRow);
			if(!$isInRow) {
				$fieldName = chr(0) . $className . chr(0) . $fName;
				$str .= "s:" . strlen($fieldName) . ":\"$fieldName\";" . "N;";
				continue;
			}

			switch (gettype($fValue)) {
				case "boolean":
					$fieldValue = "b:" . (strlen($row[$fName]) == 0 ? "0" : $row[$fName]) . ";";
					break;
				case "integer":
					$fieldValue = "i:" . (strlen($row[$fName]) == 0 ? "0" : $row[$fName]) . ";";
					break;
				case "string":
					$fieldValue = "s:" . strlen($row[$fName]) . ":\"" . $row[$fName] . "\"" . ";";
					break;
				case "array":
					$fieldValue = serialize(explode(",", $row[$fName]));
					break;
				case "object":
					try {
						if (get_class($fValue) == "DateTime")
							$fieldValue = serialize(new DateTime($row[$fName], new DateTimeZone("UTC")));
						else
							$fieldValue = serialize($fValue);
					} catch (Exception $e) {
						$fieldValue = "N;";
					}
					break;
				case "NULL":
					$fieldValue = "N;";
					break;
			}

			$fieldName = chr(0) . $className . chr(0) . $fName;
			$str .= "s:" . strlen($fieldName) . ":\"$fieldName\";" . $fieldValue;
		}
		if(strlen($str)==0) return null;
		return "O:" . strlen($className) . ":\"$className\":" . sizeof((array)$class) . ":{".$str . "}";
	}

	/*
	 * /^(?!(?:\"?\[-~]\"?|){255,})(?!(?:\"?\[-~]\"?|){65,}@)(?:[!#-'*+-\/-9=?^-~]+|\"
	(?:[--!#-[]-]|\[-])*\")(?:\.(?:[!#-'*+-\/-9=?^-~]+|\"(?:[--!#-[]-]|\[-])*\"))
	*@(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:[a-z][a-z0-9]*|xn--[a-z0-9]+)(?:-[a-z0-9]+)
	*|\[(?:IPv6:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7}|(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::
	(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)|(?:IPv6:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:|(?!(?:.*[a-f0-9]:){5,})
	(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?))?(?:25[0-5]|2[0-4][0-9]|1[0-9]{2}
	|[1-9]?[0-9])(?:\.(?:25[0-5]|2[0-4][0-9]|1[0-9]{2}|[1-9]?[0-9])){3})])$/iD
	 */

	public static function isEmail(string $email)
	{

		return preg_match("/^[a-zA-Z0-9.!#$%&’*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $email);
	}
	/**
	 * @param $fieldValue
	 * @param string $text
	 * @return string
	 */
	public static function getText($fieldValue, string $text): string
	{
		switch (gettype($fieldValue)) {
			case "boolean":
				$text .= $fieldValue == 0 ? "false" : "true";
				break;
			case "integer":
				$text .= "%d";
				break;
			case "string":
				$text .= "%s";
				break;
			case "NULL":
				$text .= "null";
				break;
			case "object":
				if ($fieldValue instanceof DateTime) $text .= strftime("%e %b %Y %T", $fieldValue->getTimestamp());
				break;
		}
		return $text;
	}
	private static function getSavedUsers(){
		return json_decode(file_get_contents(ROOT_PATH."savedUsers.json"), true)["users"];
	}
	public static function getSavedUser($criteria){
		$arr = json_decode(file_get_contents(ROOT_PATH."savedUsers.json"), true)["users"];
		foreach ($arr as $elem){
			if($elem["playerID"]==$criteria||$elem["username"]==strtolower($criteria)||$elem["mail"]==$criteria) return $elem;
		}
		return null;
	}

	public static function pluralizeMessage(int $value, string $one, string $many, string $other): string
	{
		if ($value % 10 == 1 && $value != 11)
			return $value . " " . $one;
		else if ($value % 10 > 1 && $value % 10 < 5 && !($value >= 12 && $value <= 20))
			return $value . " " . $many;
		else return $value . " " . $other;

	}

	public static function getSha512(string $str): string
	{
		return (string) hash('sha512', $str);
	}
	public static function getSha256(string $str): string
	{
		return (string) hash('sha256', $str);
	}

	/**
	 * @throws DOMException
	 */
	public static function wrapCell(DOMDocument $document, $element): DOMElement{
		$td = $document->createElement('td');
		if(gettype($element)==="string"){
			$td->textContent = $element;
		}else
			$td->appendChild($element);
		return $td;
	}

	/**
	 * @throws DOMException
	 */
	public static function wrapRow(): DOMElement{
		$arg_list = func_get_args();
		$document = $arg_list[0];
		$tr = $document->createElement('tr');
		for ($i = 1; $i < func_num_args(); $i++) {
			if($arg_list[$i]===null) continue;
			$tr->appendChild(self::wrapCell($document, $arg_list[$i]));
		}
		return $tr;
	}
}




