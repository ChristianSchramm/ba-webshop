<?php

namespace Web\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default")
     * @Template()
     */
    public function indexAction()
    {
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	
    	$search = $session->get('search');
    	$type = $session->get('type');
    	$cond = $session->get('cond');
    	if (is_null($type)){
    		$type = $em->getRepository('WebShopBundle:Type')->findOneByName("BD");
    		$cond = "New";
    	}
    	$filter = $session->get('filter');
    	$products = $em->getRepository('WebShopBundle:Product')->findAllByFilter($type, $cond, $search, $filter);
        	
    	foreach ($products as $prod){
    		if (!is_null($prod->getImage())){
    			$prod->getImage()->setPath($prod->getImage()->getPath());
    		}
    		$prod->stars = 0;
    		if ($prod->getVotes()->count() > 0){
    		  foreach ($prod->getVotes() as $vote){
    	  	  $prod->stars += $vote->getValue(); 
    	    }
    	    $prod->stars /= $prod->getVotes()->count();
    		}
    }
    	
    	

    	  
    	$user = $this->get('security.context')->getToken()->getUser();
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUserIdOverview($user);
    	$genres = $em->getRepository('WebShopBundle:Genre')->findAll();
    	
    	for ($i = 0; $i < count($genres); $i++){
    		$genres[$i]->active = false;
    		if (!is_null($filter))
	    		foreach($filter as $filt){
	    			if ($filt == $genres[$i]->getId()){
	    				$genres[$i]->active = true;
	    			}
	    		}
    	}

    	// berechne Warenkorb
    	$cartCount = 0;
    	$cartSum = 0;
   	  if (!is_null($cart)){
	    	foreach ($cart->getCartProducts() as $product){
	    		$cartSum += $product->getAmount() * $product->getProduct()->getPrice();
	    		$cartCount += $product->getAmount();
	    	}
   	  }
    	
   	  $cartSum = number_format($cartSum, 2, ',', ' ');
   	  
   	  
   	  // Variablen an die View übergeben
      return array('products' => $products,
      		         'cartCount' => $cartCount,
      		         'cartSum' => $cartSum,
      		         'search' => $search,
      		         'genres' => $genres,
      		         'type' => $type,
      		         'cond' => $cond,
      		);
    }
    
    
    /**
     * @Route("/dvd/{cond}", name="dvd", defaults={"cond" = "New"})
     * @Template()
     */
    public function dvdAction($cond)
    {
    	$session = $this->getRequest()->getSession();
    	
    	$em = $this->getDoctrine()->getManager();
    	$type = $em->getRepository('WebShopBundle:Type')->findOneByName("DVD");
    	
      $session->set('type', $type);
      $session->set('cond', $cond);
      
      return $this->redirect($this->generateUrl('default'));
    }
    
    /**
     * @Route("/bd/{cond}", name="bd", defaults={"cond" = "New"})
     * @Template()
     */
    public function bdAction($cond)
    {
    	$session = $this->getRequest()->getSession();
    	 
    	$em = $this->getDoctrine()->getManager();
    	$type = $em->getRepository('WebShopBundle:Type')->findOneByName("BD");
    	 
    	$session->set('type', $type);
      $session->set('cond', $cond);
    
    	return $this->redirect($this->generateUrl('default'));
    }
    
    /**
     * @Route("/cd/{cond}", name="cd", defaults={"cond" = "New"})
     * @Template()
     */
    public function cdAction($cond)
    {
    	$session = $this->getRequest()->getSession();
    	 
    	$em = $this->getDoctrine()->getManager();
    	$type = $em->getRepository('WebShopBundle:Type')->findOneByName("CD");
    	 
    	$session->set('type', $type);
      $session->set('cond', $cond);
    
    	return $this->redirect($this->generateUrl('default'));
    }
    
    
    /**
     * @Route("/search/", name="search")
     * @Template()
     */
    public function searchAction()
    {
    	$session = $this->getRequest()->getSession();
    	$session->set('search', $_POST['search']);
    	
    
    	return $this->redirect($this->generateUrl('default'));
    }
    
    /**
     * @Route("/filter/", name="filter")
     * @Template()
     */
    public function filterAction()
    {
    	$session = $this->getRequest()->getSession();
    	if (isset($_POST['filter'])){
    	  $session->set('filter', $_POST['filter']);
    	}else {
    		$session->set('filter', array());
    	}
    
    	return $this->redirect($this->generateUrl('default'));
    }
}
