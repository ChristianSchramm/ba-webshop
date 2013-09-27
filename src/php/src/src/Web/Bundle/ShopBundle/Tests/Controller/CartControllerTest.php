<?php

namespace Web\Bundle\ShopBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cart');
        
        $url = $client->getHistory()->current()->getUri();
        
        $this->assertRegExp('/\/cart$/', $url);
    }

}
