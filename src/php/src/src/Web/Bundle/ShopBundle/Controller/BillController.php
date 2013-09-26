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
    	// User aus Session holen
    	$user = $this->get('security.context')->getToken()->getUser();
    	
    	// Alle Rechnungen aus der Datenbnak holen
    	$em = $this->getDoctrine()->getManager();    	 
    	$bills = $em->getRepository('WebShopBundle:Bill')->findByUser($user->getId());
    	
    	// Rechnungen an die View übergeben
    	return array('bills' => $bills);
    }

}
