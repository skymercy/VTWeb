<?php

class YiiString
{
	/**
	 * Returns the number of bytes in the given string.
	 * This method ensures the string is treated as a byte array by using `mb_strlen()`.
	 * @param string $string the string being measured for length
	 * @return int the number of bytes in the given string.
	 */
	public static function byteLength($string)
	{
		return mb_strlen($string, '8bit');
	}
	
	/**
	 * Encodes string into "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
	 *
	 * > Note: Base 64 padding `=` may be at the end of the returned string.
	 * > `=` is not transparent to URL encoding.
	 *
	 * @see https://tools.ietf.org/html/rfc4648#page-7
	 * @param string $input the string to encode.
	 * @return string encoded string.
	 * @since 2.0.12
	 */
	public static function base64UrlEncode($input)
	{
		return strtr(base64_encode($input), '+/', '-_');
	}
	
	/**
	 * Decodes "Base 64 Encoding with URL and Filename Safe Alphabet" (RFC 4648).
	 *
	 * @see https://tools.ietf.org/html/rfc4648#page-7
	 * @param string $input encoded string.
	 * @return string decoded string.
	 * @since 2.0.12
	 */
	public static function base64UrlDecode($input)
	{
		return base64_decode(strtr($input, '-_', '+/'));
	}
	
	/**
	 * Safely casts a float to string independent of the current locale.
	 *
	 * The decimal separator will always be `.`.
	 * @param float|int $number a floating point number or integer.
	 * @return string the string representation of the number.
	 * @since 2.0.13
	 */
	public static function floatToString($number)
	{
		// . and , are the only decimal separators known in ICU data,
		// so its safe to call str_replace here
		return str_replace(',', '.', (string) $number);
	}
}