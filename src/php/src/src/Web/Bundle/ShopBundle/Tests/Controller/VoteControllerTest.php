<?php

namespace Web\Bundle\ShopBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

class VoteControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

    }
	
	
    public function testVote()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/vote');
        
        $url = $client->getHistory()->current()->getUri();
        
        $this->assertRegExp('/\/vote$/', $url);

        $this->assertTrue($crawler->filter('html:contains("No route found")')->count() > 0);
    }
    
    public function testArticleVote()
    {
    	$client = static::createClient();
    	
   	
    	$user = $this->em->getRepository('WebShopBundle:User')->findAll();
    	$product = $this->em->getRepository('WebShopBundle:Product')->findAll();
    
    	// login
    	$crawler = $client->request('GET', '/');
    	
   	
    	echo $url = $client->getHistory()->current()->getUri();
    	// fill form
    	$form = $crawler->filter('.login-form button')->first()->form();
    	$form['_username'] = 'Tobias';
    	$form['_password'] = 'lol123';
    	$crawler = $client->submit($form);
    	
    	$url = $client->getHistory()->current()->getUri();
    	$this->assertRegExp('/\/$/', $url);
    	
    	// vote for produkt    	
    
    	$crawler = $client->request('GET', '/vote/'.$product[0]->getId().'/'.$user[0]->getId());
    
    	$url = $client->getHistory()->current()->getUri();
    
    	$this->assertRegExp('/\/$/', $url);
    
    	$this->assertTrue($crawler->filter('html:contains("No route found")')->count() > 0);
    }
    
    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
    	parent::tearDown();
    	$this->em->close();
    }

}
