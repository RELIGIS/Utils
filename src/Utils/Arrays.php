<?php

namespace RELIGIS\Utils;

class Arrays
{
	public static function sortArrayByArray(array $array, array $orderArray)
	{
		$orderedArray = array();
		foreach($orderArray as $key)
		{
			if(array_key_exists($key, $array))
			{
				$orderedArray[$key] = $array[$key];
				unset($array[$key]);
			}
		}

		return $orderedArray + $array;
	}

	public static function sortArrayByArray_key(array $array, array $orderArray)
	{
		$orderedArray = array();
		foreach($orderArray as $key => $value)
		{
			if(array_key_exists($key, $array))
			{
				$orderedArray[$key] = $array[$key];
				unset($array[$key]);
			}
		}

		return $orderedArray + $array;
	}
	
	public static function arrayToTableColumns(array $array, $columnsCount, $emptyValue = NULL)
	{
		$values = array();
		$rowsPerColumn = ceil(count($array) / $columnsCount);
		
		for($column = 1; $column <= $columnsCount; $column++)
		{ 
			$values['column_'.$column] = array();
		}

		$missingValuesCount = $rowsPerColumn * $columnsCount - count($array);
		for($i = 0; $i < $missingValuesCount; $i++)
		{
			$array[] = $emptyValue;
		}

		$column = 1;
		$row = 1;
		foreach($array as $value)
		{
			$values['column_'.$column][$row][] = $value;
			if($row == $rowsPerColumn)
			{ 
				$column++;
				$row = 1;
			}
			else 
			{
				$row++;
			}
		}

		$finalArrays = array();
		for($row = 1; $row <= $rowsPerColumn; $row++)
		{
			for($column = 1; $column <= $columnsCount; $column++)
			{ 								
				if(isset($values['column_'.$column][$row]))
				{
					$finalArrays[] = reset($values['column_'.$column][$row]);
				}
			}
		}

		return $finalArrays;
	}
	
	public static function arrayMapRecursive(callable $function, array &$array)
	{
		foreach ($array as $key => &$arr)
		{
			if(is_array($arr))
			{
				self::arrayMapRecursive($function, $arr);
			}
			else
			{
				call_user_func($function, $arr);
			}
		}
	}

	public static function arrayToXmlString($array)
	{
		$xmlString = '';
		foreach($array as $tagName => $value)
		{
			if(preg_match('/^(.*)__[0-9]+$/', $tagName, $regs))
			{
				$tagName = $regs[1];
			}

			$xmlString .= '<'.$tagName.'>';

			if(is_array($value))
			{
				$xmlString .= self::arrayToXmlString($value);
			}
			else
			{
				$xmlString .= '<![CDATA['.$value.']]>';
			}

			$xmlString .= '</'.$tagName.'>';
		}

		return $xmlString;
	}
}
