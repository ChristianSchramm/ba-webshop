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
  	$cart1->setUser($this->getReference('user1'));
  	

  	$cart2 = new Cart();
  	$cart2->setUser($this->getReference('user2'));
  	

  	$cart3 = new Cart();
  	$cart3->setUser($this->getReference('user3'));
  	

  	$cart4 = new Cart();
  	$cart4->setUser($this->getReference('user4'));


  	$manager->persist($cart1);
  	$manager->persist($cart2);
  	$manager->persist($cart3);
  	$manager->persist($cart4);

    $this->addReference('cart1', $cart1);
    $this->addReference('cart2', $cart2);
    $this->addReference('cart3', $cart3);
    $this->addReference('cart4', $cart4);
  	
  	
    $manager->flush();
  	
    
  }
}

?>