<?php

namespace Web\Bundle\ShopBundle\DataFixtures\ORM;

use Web\Bundle\ShopBundle\Entity\Type;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadTypeData extends AbstractFixture implements OrderedFixtureInterface
{
	public function getOrder(){
		return 1;
	}
	
  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager)
  {
  	
	
  	$type1 = new Type();
  	$type1->setName('VHS');
  	
  	$type2 = new Type();
  	$type2->setName('DVD');
  	
  	$type3 = new Type();
  	$type3->setName('CD');
  	
  	$type4 = new Type();
  	$type4->setName('BD');



  	$manager->persist($type1);
  	$manager->persist($type2);
  	$manager->persist($type3);
  	$manager->persist($type4);
    
    $this->addReference('type1', $type1);
    $this->addReference('type2', $type2);
    $this->addReference('type3', $type3);
    $this->addReference('type4', $type4);

    $manager->flush();
  	
    
  }
}

?>