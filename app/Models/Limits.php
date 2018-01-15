<?php
class Limits extends Eloquent
{
	protected $table = 'limits';
	public function cleanText($text){		
		return preg_replace("/[^a-zA-Z0-9\-]/", "", strtolower($text));
	}
}
?>