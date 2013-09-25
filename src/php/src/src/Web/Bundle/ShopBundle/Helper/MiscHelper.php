<?php

namespace Web\Bundle\ShopBundle\Helper;



use Symfony\Component\Console\Helper\Helper;

class MiscHelper extends Helper 
{
	
	public function getRandString($len){
		
		if ($len > 32){
			$len = 32;
		}
		
		return substr(md5(uniqid()),0, $len);
	}	
	
	
	public function getName()
	{
		return 'MiscHelper';
	}

}