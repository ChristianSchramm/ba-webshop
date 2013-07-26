<?php

namespace Web\Bundle\ShopBundle\DataFixtures\ORM;

use Web\Bundle\ShopBundle\Entity\Adress;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;



use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadAdressData extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder(){
		return 1;
	}
	
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
  	
	
  	$adress1 = new Adress();
  	$adress1->setName('Tobias Dietrich');
  	$adress1->setCountry('Deutschland');
  	$adress1->setLocation('Dresden');
  	$adress1->setPostcode('01234');
  	$adress1->setStreet('Hauptstraße 1');


  	$manager->persist($adress1);


    
    $this->addReference('adress1', $adress1);
  	
  	
    $manager->flush();
  	
    
  }
}

?>