<?php

namespace Web\Bundle\ShopBundle\DataFixtures\ORM;

use Web\Bundle\ShopBundle\Entity\Genre;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;



use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadGenreData extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder(){
		return 1;
	}
	
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
  	
	
  	$genre1 = new Genre();
  	$genre1->setName('Horror');
  	
  	$genre2 = new Genre();
  	$genre2->setName('Animation');
  	
  	$genre3 = new Genre();
  	$genre3->setName('Action');



  	$manager->persist($genre1);
  	$manager->persist($genre2);
  	$manager->persist($genre3);
    
    $this->addReference('genre1', $genre1);
    $this->addReference('genre2', $genre2);
    $this->addReference('genre3', $genre3);

    $manager->flush();
  	
    
  }
}

?>