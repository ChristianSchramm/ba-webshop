<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Entity\Adress;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


use Web\Bundle\ShopBundle\Form\Type\AdressFormType;
use Web\Bundle\ShopBundle\Form\Type\AdressType;
use Web\Bundle\ShopBundle\Form\Model\AdressForm;

class AdressController extends Controller
{
	
	/**
	 * @Route("/account/adress/", name="account_adress")
	 * @Route("/account/", name="account")
	 * @Template()
	 */
	public function indexAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
	
		$em = $this->getDoctrine()->getManager();
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());

		$form = $this->createForm(new AdressType(), $adress);
			
		return array('form' => $form->createView() );
	}
	
	/**
	 * @Route("/account/adress/edit/", name="account_adress_edit")
	 * @Template()
	 */
	public function editAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		
		$em = $this->getDoctrine()->getManager();
		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
			
		$form = $this->createForm(new AdressType(), $adress);
			
		return array('form' => $form->createView() );
	}
	
	/**
	 * @Route("/account/adress/save/", name="account_adress_save")
	 * @Template()
	 */
	public function saveAction()
	{
		$user = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getManager();

		$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
		
		$form = $this->createForm(new AdressType(), $adress);
		$form->bind($this->getRequest());
		
		if ($form->isValid()) {
			$adress = $form->getData();

			$em->persist($adress);
			$em->flush();
			return $this->redirect($this->generateUrl('account_adress'));
		}
		return $this->render('WebShopBundle:Adress:edit.html.twig',
				array('form' => $form->createView())
		);
		
	}
	
}
