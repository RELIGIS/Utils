<?php

/**
 * Test: RELIGIS\Arrays
 */

use Tester\Assert;

require __DIR__ . '/bootstrap.php';

test(function () {
	$array = [
		'Apple',
		'Orange',
		'Strawberry',
	];

	$newArray = RELIGIS\Arrays::arrayToTableColumns($array, 2);
	Assert::same([
		'Apple',
		'Strawberry',
		'Orange',
		NULL
	], $newArray);

	$newArray = RELIGIS\Arrays::arrayToTableColumns($array, 3);
	Assert::same([
		'Apple',
		'Orange',
		'Strawberry',
	], $newArray);

	$newArray = RELIGIS\Arrays::arrayToTableColumns($array, 4);
	Assert::same([
		'Apple',
		'Orange',
		'Strawberry',
		NULL,
	], $newArray);

	$array = [
		'Apple',
		'Orange',
		'Strawberry',
		'Banana',
	];

	$newArray = RELIGIS\Arrays::arrayToTableColumns($array, 2);
	Assert::same([
		'Apple',
		'Strawberry',
		'Orange',
		'Banana',
	], $newArray);

	$newArray = RELIGIS\Arrays::arrayToTableColumns($array, 3);
	Assert::same([
		'Apple',
		'Strawberry',
		NULL,
		'Orange',
		'Banana',
		NULL,
	], $newArray);

	$array = [
		'Apple',
		'Orange',
		'Strawberry',
		'Banana',
		'Kiwi',
		'Blueberry',
		'Raspberry',
	];

	$newArray = RELIGIS\Arrays::arrayToTableColumns($array, 4);
	Assert::same([
		'Apple',
		'Strawberry',
		'Kiwi',
		'Raspberry',
		'Orange',
		'Banana',
		'Blueberry',
		NULL,
	], $newArray);
});

test(function() {
	$array = [
		'Apple',
		'Orange',
		'Strawberry',
		'Banana'
	];

	$orderKeysArray = [
		3 => 'Foo',
		2 => 'Foo',
		0 => 'Foo',
		1 => 'Foo',
	];

	$orderedArray = RELIGIS\Arrays::sortArrayByArray_key($array, $orderKeysArray);
	Assert::same([
		3 => 'Banana',
		2 => 'Strawberry',
		0 => 'Apple',
		1 => 'Orange',
	], $orderedArray);
});