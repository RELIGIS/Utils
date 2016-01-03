<?php

namespace RELIGIS\Utils;

use Nette;

class Strings extends Nette\Utils\Strings
{

	public static function firstLower($s)
	{
		return self::lower(self::substring($s, 0, 1)).self::substring($s, 1);
	}

	public static function replaceOnce($search, $replace, $s)
	{
		$search = str_replace('/', '\/', $search);
		$s = preg_replace('/'.$search.'/', $replace, $s, 1);

		return $s;
	}

	public static function htmlTruncate($s, $maxLen, $append = "\xE2\x80\xA6")
	{
		$i = 0;
		$tags = array();

		preg_match_all('/<[^>]+>([^<]*)/', $s, $match, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		foreach($match as $object)
		{
			if($object[0][1] - $i >= $maxLen)
			{
				break;
			}

			$object[0][0] = trim($object[0][0]);
			if(self::endsWith($object[0][0], '/>') == FALSE)
			{
				$tag = self::substring(strtok($object[0][0], " \t\n\r\0\x0B>"), 1);
				if($tag[0] != '/')
				{
					$tags[] = $tag;
				}
				elseif(end($tags) == self::substring($tag, 1))
				{
					array_pop($tags);
				}
			}

			$i += $object[1][1] - $object[0][1];
		}

		$stringLenght = min(strlen($s), $maxLen + $i);
		$truncatedString = self::truncate($s, $stringLenght, $append);
		$endingTags = count($tags = array_reverse($tags)) ? '</'.implode('></', $tags).'>' : '';

		return $truncatedString.$endingTags;
	}

	public static function camelize($s, $delimiter = '_')
	{
		$pieces = explode($delimiter, $s);
		$pieces = array_map(array('self', 'firstUpper'), $pieces);

		$s = implode('', $pieces);
		$s = self::firstLower($s);

		return $s;
	}

	public static function uncamelize($s, $delimiter = '_')
	{
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $s, $matches);

		$ret = $matches[0];
		foreach($ret as &$match)
		{
			$match = $match == self::upper($match) ? self::lower($match) : lcfirst($match);
		}

		return implode($delimiter, $ret);
	}

	public static function hexToRgb($hex)
	{
		if(self::startsWith($hex, '#'))
		{
			$hex = self::substring($hex, 1);
		}

		if(self::length($hex) == 3)
		{
			$hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
		}
		elseif(self::length($hex) != 6)
		{
			return FALSE;
		}

		$rgb = array();
		$rgb[0] = hexdec(self::substring($hex, 0, 2));
		$rgb[1] = hexdec(self::substring($hex, 2, 2));
		$rgb[2] = hexdec(self::substring($hex, 4, 2));

		return $rgb;
	}

	public static function combineTitles($firstTitle, $secondTitle)
	{
		// $firstTitle = 'Bavlněné povlečení';
		// $secondTitle = 'Povlečení Smolka';

		// $firstTitle = 'Povlečení na postel';
		// $secondTitle = 'Povlečení na postel Smolka';

		$firstWords = explode(' ', $firstTitle);
		$secondWords = explode(' ', $secondTitle);

		$firstWords = array_reverse($firstWords);

		foreach($firstWords as $index => $void)
		{
			if(isset($secondWords[$index]))
			{
				$temp1 = '';
				$temp2 = '';
				for($i = $index; $i >= 0; $i--)
				{
					$temp1 = $firstWords[$i].$temp1;
					$temp2 .= $secondWords[$i];
				}

				$temp1 = self::lower($temp1);
				$temp2 = self::lower($temp2);

				if($temp1 != $temp2)
				{
					continue;
				}
				else
				{
					for($i = $index; $i >= 0; $i--)
					{
						unset($firstWords[$i]);
					}

					$firstWords = array_reverse($firstWords);

					$firstTitle = implode(' ', $firstWords);
					$secondTitle = self::firstLower($secondTitle);
					break;
				}
			}
		}

		return self::firstUpper(trim($firstTitle.' '.$secondTitle));
	}

	public static function removeAllWhitespaces($s)
	{
		return self::replace($s, '/\s/', '');
	}

	public static function substringByWords($s, $maxWords, $append = '')
	{
		$wordsInText = explode(' ', $s);

		if(count($wordsInText) > $maxWords)
		{
			$result = array_slice($wordsInText, 0, $maxWords);
			$s = implode(' ', $result).$append;
		}

		return $s;
	}

	public static function convertToWin1250($str)
	{
		$str = self::fixEncoding($str);

		return iconv('UTF-8', 'WINDOWS-1250//IGNORE', $str);
	}

	public static function convertToUtf8($str)
	{
		return iconv('WINDOWS-1250', 'UTF-8//TRANSLIT', $str);
	}

	public static function stripTags($s, $tags = array())
	{
		$tags = (array) $tags;

		$s = preg_replace('@<('.implode('|', $tags).').*>@si', '', $s);
		return trim($s);
	}
}