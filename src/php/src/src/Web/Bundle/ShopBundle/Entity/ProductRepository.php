<?php

namespace Web\Bundle\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityRepository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends EntityRepository
{
	
	public function findAllByFilter($type, $search, $filter) {
		
		// seach and type filter


		$q = $this
				->createQueryBuilder('c')
				->where('c.type = :type AND c.title LIKE :search ')
				->setParameter('type', $type->getId())
				->setParameter('search', "%".$search."%")
				->getQuery();

		
		$result = $q->getResult();

		$resultFinal = new ArrayCollection();
		
		
		// filter genre
		foreach ($result as $product){
			//echo $product->getTitle();
			$ja = 0;
			foreach ($product->getGenres() as $genre){
				//echo $genre->getName();
				if (!is_null($filter))
					foreach ($filter as $filt){
						if ($genre->getId() == $filt){
							$ja++;
						}
					}
			}
			if ($ja == count($filter)){
				$resultFinal->add($product);
			}
		}
		



		if (count($filter) > 0)
			return $resultFinal;
		else		
		  return $result;
	}
}
