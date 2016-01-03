<?php

namespace RELIGIS\Utils;

class ArrayCombination
{
	private $codes;
	private $pos;
	private $result;

	public function __construct()
	{
		$this->codes = array();
		$this->pos = 0;
		$this->result = array();
	}


	public static function getArrayCombination(array $arr)
	{
		$class = __CLASS__;

		/** @var self $object */
		$object = new $class();
		$object->generateCodes($arr);

		return $object->result;
	}


	private function generateCodes(array $arr)
	{
		if(count($arr))
		{
			for($i = 0; $i < count($arr[0]); $i++)
			{
				$tmp = $arr;
				$this->codes[$this->pos] = $arr[0][$i];
				array_shift($tmp);
				$this->pos++;

				$this->generateCodes($tmp);
			}
		}
		else
		{
			$this->result[] = $this->codes;
		}

		$this->pos--;
	}

}

?>