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
  	

  	$adress2 = new Adress();
  	$adress3 = new Adress();
  	$adress4 = new Adress();


  	$manager->persist($adress1);
  	$manager->persist($adress2);
  	$manager->persist($adress3);
  	$manager->persist($adress4);


    
    $this->addReference('adress1', $adress1);
    $this->addReference('adress2', $adress2);
    $this->addReference('adress3', $adress3);
    $this->addReference('adress4', $adress4);
  	
  	
    $manager->flush();
  	
    
  }
}

?>