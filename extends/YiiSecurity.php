<?php

use biny\lib\TXException;

class YiiSecurity
{
	/**
	 * @var int Default cost used for password hashing.
	 * Allowed value is between 4 and 31.
	 * @see generatePasswordHash()
	 * @since 2.0.6
	 */
	public static $PasswordHashCost = 13;
	
	private static $_UseLibreSSL = null;
	private static $_RandomFile = null;
	
	/**
	 * Generates specified number of random bytes.
	 * Note that output may not be ASCII.
	 * @see generateRandomString() if you need a string.
	 *
	 * @param int $length the number of bytes to generate
	 * @return string the generated random bytes
	 * @throws InvalidArgumentException if wrong length is specified
	 * @throws Exception on failure.
	 */
	public static function generateRandomKey($length = 32)
	{
		if (!is_int($length)) {
			throw new TXException(Yii::YiiErrorCode, 'First parameter ($length) must be an integer');
		}
		
		if ($length < 1) {
			throw new TXException(Yii::YiiErrorCode, 'First parameter ($length) must be greater than 0');
		}
		
		// always use random_bytes() if it is available
		if (function_exists('random_bytes')) {
			return random_bytes($length);
		}
		
		// The recent LibreSSL RNGs are faster and likely better than /dev/urandom.
		// Parse OPENSSL_VERSION_TEXT because OPENSSL_VERSION_NUMBER is no use for LibreSSL.
		// https://bugs.php.net/bug.php?id=71143
		if (self::$_UseLibreSSL === null) {
			self::$_UseLibreSSL = defined('OPENSSL_VERSION_TEXT')
				&& preg_match('{^LibreSSL (\d\d?)\.(\d\d?)\.(\d\d?)$}', OPENSSL_VERSION_TEXT, $matches)
				&& (10000 * $matches[1]) + (100 * $matches[2]) + $matches[3] >= 20105;
		}
		
		// Since 5.4.0, openssl_random_pseudo_bytes() reads from CryptGenRandom on Windows instead
		// of using OpenSSL library. LibreSSL is OK everywhere but don't use OpenSSL on non-Windows.
		if (function_exists('openssl_random_pseudo_bytes')
			&& (self::$_UseLibreSSL
				|| (
					DIRECTORY_SEPARATOR !== '/'
					&& substr_compare(PHP_OS, 'win', 0, 3, true) === 0
				))
		) {
			$key = openssl_random_pseudo_bytes($length, $cryptoStrong);
			if ($cryptoStrong === false) {
				throw new Exception(
					'openssl_random_pseudo_bytes() set $crypto_strong false. Your PHP setup is insecure.'
				);
			}
			if ($key !== false && YiiString::byteLength($key) === $length) {
				return $key;
			}
		}
		
		// mcrypt_create_iv() does not use libmcrypt. Since PHP 5.3.7 it directly reads
		// CryptGenRandom on Windows. Elsewhere it directly reads /dev/urandom.
		if (function_exists('mcrypt_create_iv')) {
			$key = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
			if (YiiString::byteLength($key) === $length) {
				return $key;
			}
		}
		
		// If not on Windows, try to open a random device.
		if (self::$_RandomFile === null && DIRECTORY_SEPARATOR === '/') {
			// urandom is a symlink to random on FreeBSD.
			$device = PHP_OS === 'FreeBSD' ? '/dev/random' : '/dev/urandom';
			// Check random device for special character device protection mode. Use lstat()
			// instead of stat() in case an attacker arranges a symlink to a fake device.
			$lstat = @lstat($device);
			if ($lstat !== false && ($lstat['mode'] & 0170000) === 020000) {
				self::$_RandomFile = fopen($device, 'rb') ?: null;
				
				if (is_resource(self::$_RandomFile)) {
					// Reduce PHP stream buffer from default 8192 bytes to optimize data
					// transfer from the random device for smaller values of $length.
					// This also helps to keep future randoms out of user memory space.
					$bufferSize = 8;
					
					if (function_exists('stream_set_read_buffer')) {
						stream_set_read_buffer(self::$_RandomFile, $bufferSize);
					}
					// stream_set_read_buffer() isn't implemented on HHVM
					if (function_exists('stream_set_chunk_size')) {
						stream_set_chunk_size(self::$_RandomFile, $bufferSize);
					}
				}
			}
		}
		
		if (is_resource(self::$_RandomFile)) {
			$buffer = '';
			$stillNeed = $length;
			while ($stillNeed > 0) {
				$someBytes = fread(self::$_RandomFile, $stillNeed);
				if ($someBytes === false) {
					break;
				}
				$buffer .= $someBytes;
				$stillNeed -= YiiString::byteLength($someBytes);
				if ($stillNeed === 0) {
					// Leaving file pointer open in order to make next generation faster by reusing it.
					return $buffer;
				}
			}
			fclose(self::$_RandomFile);
			self::$_RandomFile = null;
		}
		
		throw new TXException(Yii::YiiErrorCode,'Unable to generate a random key');
	}
	
	/**
	 * Generates a random string of specified length.
	 * The string generated matches [A-Za-z0-9_-]+ and is transparent to URL-encoding.
	 *
	 * @param int $length the length of the key in characters
	 * @return string the generated random key
	 * @throws Exception on failure.
	 */
	public static function generateRandomString($length = 32)
	{
		if (!is_int($length)) {
			throw new TXException(Yii::YiiErrorCode, 'First parameter ($length) must be an integer');
		}
		
		if ($length < 1) {
			throw new TXException(Yii::YiiErrorCode, 'First parameter ($length) must be greater than 0');
		}
		
		$bytes = self::generateRandomKey($length);
		return substr(YiiString::base64UrlEncode($bytes), 0, $length);
	}
	
	/**
	 * Generates a secure hash from a password and a random salt.
	 *
	 * The generated hash can be stored in database.
	 * Later when a password needs to be validated, the hash can be fetched and passed
	 * to [[validatePassword()]]. For example,
	 *
	 * ```php
	 * // generates the hash (usually done during user registration or when the password is changed)
	 * $hash = Yii::$app->getSecurity()->generatePasswordHash($password);
	 * // ...save $hash in database...
	 *
	 * // during login, validate if the password entered is correct using $hash fetched from database
	 * if (Yii::$app->getSecurity()->validatePassword($password, $hash)) {
	 *     // password is good
	 * } else {
	 *     // password is bad
	 * }
	 * ```
	 *
	 * @param string $password The password to be hashed.
	 * @param int $cost Cost parameter used by the Blowfish hash algorithm.
	 * The higher the value of cost,
	 * the longer it takes to generate the hash and to verify a password against it. Higher cost
	 * therefore slows down a brute-force attack. For best protection against brute-force attacks,
	 * set it to the highest value that is tolerable on production servers. The time taken to
	 * compute the hash doubles for every increment by one of $cost.
	 * @return string The password hash string. When [[passwordHashStrategy]] is set to 'crypt',
	 * the output is always 60 ASCII characters, when set to 'password_hash' the output length
	 * might increase in future versions of PHP (http://php.net/manual/en/function.password-hash.php)
	 * @throws TXException on bad password parameter or cost parameter.
	 * @see validatePassword()
	 */
	public static function generatePasswordHash($password, $cost = null)
	{
		if ($cost === null) {
			$cost = self::$PasswordHashCost;
		}
		
		if (function_exists('password_hash')) {
			/* @noinspection PhpUndefinedConstantInspection */
			return password_hash($password, PASSWORD_DEFAULT, ['cost' => $cost]);
		}
		
		$salt = self::generateSalt($cost);
		$hash = crypt($password, $salt);
		// strlen() is safe since crypt() returns only ascii
		if (!is_string($hash) || strlen($hash) !== 60) {
			throw new TXException(Yii::YiiErrorCode, 'Unknown error occurred while generating hash.');
		}
		
		return $hash;
	}
	
	/**
	 * Verifies a password against a hash.
	 * @param $password
	 * @param $hash
	 * @return bool
	 * @throws TXException
	 * @see generatePasswordHash()
	 */
	public static function validatePassword($password, $hash)
	{
		if (!is_string($password) || $password === '') {
			throw new TXException(Yii::YiiErrorCode, 'Password must be a string and cannot be empty.');
		}
		
		if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches)
			|| $matches[1] < 4
			|| $matches[1] > 30
		) {
			throw new TXException(Yii::YiiErrorCode, 'Hash is invalid.');
		}
		
		if (function_exists('password_verify')) {
			return password_verify($password, $hash);
		}
		
		$test = crypt($password, $hash);
		$n = strlen($test);
		if ($n !== 60) {
			return false;
		}
		
		return self::compareString($test, $hash);
	}
	
	/**
	 * Generates a salt that can be used to generate a password hash.
	 *
	 * The PHP [crypt()](http://php.net/manual/en/function.crypt.php) built-in function
	 * requires, for the Blowfish hash algorithm, a salt string in a specific format:
	 * "$2a$", "$2x$" or "$2y$", a two digit cost parameter, "$", and 22 characters
	 * from the alphabet "./0-9A-Za-z".
	 *
	 * @param int $cost
	 * @return string
	 * @throws TXException
	 */
	protected static function generateSalt($cost = 13)
	{
		$cost = (int) $cost;
		if ($cost < 4 || $cost > 31) {
			throw new TXException(Yii::YiiErrorCode, 'Cost must be between 4 and 31.');
		}
		
		// Get a 20-byte random string
		$rand = self::generateRandomKey(20);
		// Form the prefix that specifies Blowfish (bcrypt) algorithm and cost parameter.
		$salt = sprintf('$2y$%02d$', $cost);
		// Append the random salt data in the required base64 format.
		$salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));
		
		return $salt;
	}
	
	/**
	 * Performs string comparison using timing attack resistant approach.
	 * @see http://codereview.stackexchange.com/questions/13512
	 * @param $expected
	 * @param $actual
	 * @return bool
	 * @throws TXException
	 */
	public static function compareString($expected, $actual)
	{
		if (!is_string($expected)) {
			throw new TXException(Yii::YiiErrorCode, 'Expected expected value to be a string, ' . gettype($expected) . ' given.');
		}
		
		if (!is_string($actual)) {
			throw new TXException(Yii::YiiErrorCode, 'Expected actual value to be a string, ' . gettype($actual) . ' given.');
		}
		
		if (function_exists('hash_equals')) {
			return hash_equals($expected, $actual);
		}
		
		$expected .= "\0";
		$actual .= "\0";
		$expectedLength = YiiString::byteLength($expected);
		$actualLength = YiiString::byteLength($actual);
		$diff = $expectedLength - $actualLength;
		for ($i = 0; $i < $actualLength; $i++) {
			$diff |= (ord($actual[$i]) ^ ord($expected[$i % $expectedLength]));
		}
		
		return $diff === 0;
	}
}