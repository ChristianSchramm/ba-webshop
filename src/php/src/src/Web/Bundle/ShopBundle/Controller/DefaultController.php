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
    	
    	
    	
    	$user = $this->get('security.context')->getToken()->getUser();
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUserIdOverview($user);

    	
   	  if (!is_null($cart)){
	    	$cartCount = 0;
	    	$cartSum = 0;
	    	foreach ($cart->getCartProducts() as $product){
	    		$cartSum += $product->getAmount() * $product->getProduct()->getPrice();
	    		$cartCount += $product->getAmount();
	    	}
	    	$cartSum = number_format($cartSum, 2, ',', ' ');
   	  }
    	

      return array('products' => $products,
      		         'cartCount' => $cartCount,
      		         'cartSum' => $cartSum
      		);
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
