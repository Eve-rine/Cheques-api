<?php
namespace app\components;

class Numbers
{
	
	public static function randomString($numerical=FALSE) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 9; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		$s= uniqid($randomString,true);
		$hex = substr($s, 0, 13);
		$dec = $s[13] . substr($s, 15); // skip the dot
		$unique = base_convert($hex, 16, 36) . base_convert($dec, 10, 36);
		if($numerical){$string=ltrim(crc32($unique.date('dmyhis')),'-');}else{$string=$unique;}
		
		if($string == null || $string == ''){
			return 'RAND'.time();
		}else{
			return $string;
		} //$string != null?$string:'TUM'.time();
	}
}