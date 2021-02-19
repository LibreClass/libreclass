<?php

function titleCase($string,
	$delimiters = [' ', '-', '.', "'", "O'", 'Mc'],
	$exceptions = ['da', 'das', 'de', 'do', 'dos', 'e', 'o', 'and', 'to', 'of', 'ou', 'no', 'com', 'em', 'sem', 'I', 'II', 'III', 'IV', 'V', 'VI']) {
	/*
	 * Exceptions in lower case are words you don't want converted
	 * Exceptions all in upper case are any words you don't want converted to title case
	 *   but should be converted to upper case, e.g.:
	 *   king henry viii or king henry Viii should be King Henry VIII
	 */
	$string = mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
	foreach ($delimiters as $dlnr => $delimiter) {
		$words    = explode($delimiter, $string);
		$newwords = [];
		foreach ($words as $wordnr => $word) {
			if (in_array(mb_strtoupper($word, 'UTF-8'), $exceptions)) {
				// check exceptions list for any words that should be in upper case
				$word = mb_strtoupper($word, 'UTF-8');
			} else if (in_array(mb_strtolower($word, 'UTF-8'), $exceptions)) {
				// check exceptions list for any words that should be in upper case
				$word = mb_strtolower($word, 'UTF-8');
			} else if (!in_array($word, $exceptions)) {
				// convert to uppercase (non-utf8 only)
				$word = ucfirst($word);
			}
			array_push($newwords, $word);
		}
		$string = join($delimiter, $newwords);
	}

	return $string;
}

if (!function_exists('title_case')) {
	//não está sendo usada, devido a já existir uma função com mesmo nome no vendor, aguardando correção
	function title_case($value)
	{
		return titleCase($value);
	}
}
