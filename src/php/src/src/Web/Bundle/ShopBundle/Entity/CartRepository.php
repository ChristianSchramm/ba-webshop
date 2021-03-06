<?php

namespace Web\Bundle\ShopBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CartRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartRepository extends EntityRepository
{
	
	public function findOneByUserIdOverview($user){
		
		if (is_object($user)){
		
			$q = $this
			->createQueryBuilder('c')
			->where('c.user = :userid')
			->setParameter('userid', $user->getId())
			->getQuery()
			;
			
			try {
				$cart = $q->getSingleResult();
			} catch (NoResultException $e) {
				return array();
			}

			return $cart;
		}
		return null;

	}
	
	
}
