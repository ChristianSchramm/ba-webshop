<?php

namespace Web\Bundle\ShopBundle\DataFixtures\ORM;

use Web\Bundle\ShopBundle\Entity\Product;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;



use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder(){
		return 6;
	}
	
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
  	
	
  	$product1 = new Product();
  	$product1->setTitle('Die Bourne Identität');
  	$product1->setType('BD');
  	$product1->setGenre('Action, Mystery, Thriller');
  	$product1->setStatus('Gebraucht');
  	$product1->setPrice(8.95);

  	$product2 = new Product();
  	$product2->setTitle('Silent Hill');
  	$product2->setType('DVD');
  	$product2->setGenre('Horror');
  	$product2->setStatus('Neu');
  	$product2->setPrice(3.95);

  	$product3 = new Product();
  	$product3->setTitle('Toy Story');
  	$product3->setType('BD');
  	$product3->setStatus('Neu');
  	$product3->setPrice(6);

  	$manager->persist($product1);
    $manager->persist($product2);
    $manager->persist($product3);

    
    $this->addReference('product1', $product1);
    $this->addReference('product2', $product2);
    $this->addReference('product3', $product3);
  	
  	
    $manager->flush();
  	
    
  }
}

?>