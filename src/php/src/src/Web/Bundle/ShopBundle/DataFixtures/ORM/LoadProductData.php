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
  	$product1->setType($this->getReference('type2'));
  	$product1->addGenre($this->getReference('genre1'));
  	$product1->addGenre($this->getReference('genre2'));
  	$product1->addGenre($this->getReference('genre3'));
  	$product1->setStatus('Old');
  	$product1->setPrice(8.95);
  	$product1->setAmount(3);
  	$product1->setShipping(2);
  	$product1->setDescription("Based very loosely on Robert Ludlum's novel, the Bourne Identity is the story of a man whose wounded body is discovered by fishermen who nurse him back to health. He can remember nothing and begins to try to rebuild his memory based on clues such as the Swiss bank account, the number of which, is implanted in his hip. He soon realizes that he is being hunted and takes off with Marie on a search to find out who he is and why he is being hunted");

  	$product2 = new Product();
  	$product2->setTitle('Silent Hill');
  	$product2->setType($this->getReference('type3'));
  	$product2->addGenre($this->getReference('genre1'));
  	$product2->setStatus('New');
  	$product2->setPrice(3.95);
  	$product2->setAmount(22);
  	$product2->setShipping(14);

  	$product3 = new Product();
  	$product3->setTitle('Toy Story');
  	$product3->setType($this->getReference('type4'));
  	$product3->addGenre($this->getReference('genre2'));
  	$product3->setStatus('New');
  	$product3->setAmount(11);
  	$product3->setPrice(6);
  	$product3->setShipping(2);

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