<?php

namespace Web\Bundle\ShopBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
	public function testIndex()
	{
		 
	
		$client = static::createClient();
	
		$crawler = $client->request('GET', '/');
	
	}
}
