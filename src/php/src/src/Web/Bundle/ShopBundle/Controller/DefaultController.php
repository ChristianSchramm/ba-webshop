<?php

namespace Web\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$products = $em->getRepository('WebShopBundle:Product')->findAll();
    	

      return array('products' => $products);
    }
    
    
    /**
     * @Route("/search/{key}", name="search")
     * @Template()
     */
    public function searchAction($key)
    {
    	$em = $this->getDoctrine()->getManager();
    	$products = $em->getRepository('WebShopBundle:Product')->findAll();
    	 
    
    	return $this->render('WebShopBundle:Default:index.html.twig', array('products' => $products));
    }
}
