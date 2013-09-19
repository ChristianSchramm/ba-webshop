<?php

namespace Web\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BillController extends Controller
{
    /**
     * @Route("/account/bill/", name="account_bill")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();    	 
    	$user = $this->get('security.context')->getToken()->getUser();
    	$bills = $em->getRepository('WebShopBundle:Bill')->findByUser($user->getId());
    	
    	return array('bills' => $bills);
    }

}
