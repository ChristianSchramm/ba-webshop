<?php

namespace Web\Bundle\ShopBundle\DataFixtures\ORM;


use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Web\Bundle\ShopBundle\Entity\CartProduct;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadCartProductData extends AbstractFixture implements OrderedFixtureInterface
{
	
	public function getOrder(){
		return 8;
	}
	
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
  	
  	$cartProduct1 = new CartProduct();
  	$cartProduct1->setAmount(1);
  	$cartProduct1->setCart($this->getReference('cart1'));
  	$cartProduct1->setProduct($this->getReference('product1'));
  	

  	$cartProduct2 = new CartProduct();
  	$cartProduct2->setAmount(2);
  	$cartProduct2->setCart($this->getReference('cart1'));
  	$cartProduct2->setProduct($this->getReference('product3'));
  	
  	
  	$cart = $this->getReference('cart1');
  	$cart->getCartProducts()->count();

		
		
		$this->addReference('cartProduct1', $cartProduct1);
		$this->addReference('cartProduct2', $cartProduct2);
		
    $manager->persist($cartProduct1);
    $manager->persist($cartProduct2);
    
    $manager->flush();
    
  }
}

?>