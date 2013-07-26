<?php

namespace Web\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class AdressController extends Controller
{
	/**
	 * @Route("/account/adress", name="account_adress")
	 * @Template()
	 */
	public function indexAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
	
		$em = $this->getDoctrine()->getManager();
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());

		return array('adress' => $adress);
	}
	
	/**
	 * @Route("/account/adress/edit")
	 * @Template()
	 */
	public function editAction(Request $request)
	{
		$user = $this->get('security.context')->getToken()->getUser();
	
		$em = $this->getDoctrine()->getManager();
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
	
		
		$form = $this->createFormBuilder($adress)
		  ->add('name', 'text')
		  ->add('street', 'text')
		  ->getForm();
		
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			
			$adress = $form->getData();
			
		
			return $this->redirect($this->generateUrl('account_adress'));
		}
		
		return array( 'form' => $form->createView());
	}
	
}
