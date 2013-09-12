<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Entity\Vote;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class VoteController extends Controller
{
    /**
     * @Route("/vote/{productid}/{value}", name="vote")
     */
    public function indexAction($productid, $value)
    {
    	$em = $this->getDoctrine()->getManager();
    	
    	$user = $this->get('security.context')->getToken()->getUser();

    	$vote = $em->getRepository('WebShopBundle:Vote')->findOneBy(array('user' => $user->getId(), 'product' => $productid));
    	$product = $em->getRepository('WebShopBundle:Product')->findOneById($productid);
    	
    	if (!is_object($vote)){
    		$vote = new Vote();
    	}
    	
    	$vote->setValue($value);
    	$vote->setUser($user);
    	$vote->setProduct($product);
    	
    	$em->persist($vote);
    	$em->flush();
    	
    	return $this->redirect($this->generateUrl('default'));
    }

}
