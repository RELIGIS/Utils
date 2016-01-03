<?php

namespace RELIGIS\Utils;

class AsynchronousCaller
{
	public static function call($url, $parameters = array(), $type = 'GET')
	{
		if($type !== 'GET' && $type !== 'POST')
		{
			throw new \UnexpectedValueException('Type must be GET or POST. '.$type.' given.');
		}

		$requestParameters = array();

		foreach($parameters as $index => &$value)
		{
			if(is_array($value))
			{
				$value = implode(',', $value);
			}

			$requestParameters[] = $index.'='.urlencode($value);
		}

		$parametersString = implode('&', $requestParameters);

		$urlParts = parse_url($url);

		$out = "$type ".$urlParts['path']." HTTP/1.1\r\n";
		$out .= "Host: ".$urlParts['host']."\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "Content-Length: ".strlen($parametersString)."\r\n";
		$out .= "Connection: Close\r\n\r\n";

		if($type === 'POST')
		{
			$out .= $parametersString;
		}

		if($type === 'GET')
		{
			$urlParts['path'] .= '?'.$parametersString;
		}

		$socket = fsockopen($urlParts['host'], isset($urlParts['port']) ? $urlParts['port'] : 80, $errno, $errstr, 30);
		fwrite($socket, $out);
		fclose($socket);
	}
}