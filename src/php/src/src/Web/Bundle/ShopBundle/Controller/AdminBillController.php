<?php

namespace Web\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminBillController extends Controller
{
	/**
	 * @Route("/admin/user/{id}/bill", name="admin_user_bill")
	 * @Template()
	 */
	public function indexAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		
		// User und Rechnung für die View abfragen
		$user = $em->getRepository('WebShopBundle:User')->findOneById($id);		
		$bills = $em->getRepository('WebShopBundle:Bill')->findByUser($user->getId());

		// User und Rechnung an die View übergeben
		return array('user' => $user, 'bills' => $bills);
	}
	
	/**
	 * @Route("/admin/bill/set/{id}", name="admin_bill_set")
	 * @Template()
	 */
	public function setAction($id)
	{
		$em = $this->getDoctrine()->getManager();	
		
		// Rechnung aus der Datenbank holen
		$bill = $em->getRepository('WebShopBundle:Bill')->findOneById($id);
		
		// Rechnung auf bezhalt oder unbezhalt setzen
		if ($bill->getPaid()){
			$bill->setPaid(false);
		}else {
			$bill->setPaid(true);
		}
		
		//Rechnung in der Datenbank speichern		
		$em->persist($bill);
		$em->flush();
		
	  // Weiterleitung zur Übersicht
		return $this->redirect($this->generateUrl('admin_user_bill', array('id' => $bill->getUser()->getId())));
	}
	
}