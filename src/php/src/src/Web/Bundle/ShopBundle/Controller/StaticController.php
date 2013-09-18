<?php

namespace Web\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StaticController extends Controller
{
	
	/**
	 * @Route("/kontakt", name="static_kontakt")
	 * @Template()
	 */
	public function kontaktAction()
	{
		return array();
	}
	
	/**
	 * @Route("/agb", name="static_agb")
	 * @Template()
	 */
	public function agbAction()
	{
		return array();
	}
	
	/**
	 * @Route("/shipping", name="static_shipping")
	 * @Template()
	 */
	public function shippingAction()
	{
		return array();
	}
	
	/**
	 * @Route("/payment", name="static_payment")
	 * @Template()
	 */
	public function paymentAction()
	{
		return array();
	}

}