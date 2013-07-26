<?php

namespace Web\Bundle\ShopBundle\DataFixtures\ORM;

use Web\Bundle\ShopBundle\Entity\Cart;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;



use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadCartData extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder(){
		return 7;
	}
	
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
  	
	
  	$cart1 = new Cart();


  	$manager->persist($cart1);


    
    $this->addReference('cart1', $cart1);
  	
  	
    $manager->flush();
  	
    
  }
}

?>