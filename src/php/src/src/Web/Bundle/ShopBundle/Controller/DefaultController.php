<?php

namespace Web\Bundle\ShopBundle\Controller;

use Web\Bundle\ShopBundle\Entity\CartProduct;

use Doctrine\Common\Collections\ArrayCollection;

use Web\Bundle\ShopBundle\Entity\Cart;

use Web\Bundle\ShopBundle\Entity\User;

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
    	
    	// hole filter aus der session
    	$search = $session->get('search');
    	$type = $session->get('type');
    	$cond = $session->get('cond');
    	
      // anonym user
    	$tmpUser = $session->get('tmpUser');
    	$tmpCart = $session->get('tmpCart');
    	if (is_null($tmpUser)){
    		$tmpUser = new User();
    		$session->set('tmpUser', $tmpUser);
    		$tmpCart = new Cart();
    		$session->set('tmpCart', $tmpCart);  
    		   		
    	}
    	
    	
    	
    	$from = $session->get('from');
    	if (is_null($from)){
    		$from = 0;
    	}
    	$until = $session->get('until');
    	if (is_null($until)){
    		$until = 100;
    	}
    	
    	// testen ob untel kleiner als from ist, notfalls tauschen
    	if ($until < $from){
    		$tmp = $until;
    		$until = $from;
    		$from = $tmp;
    	}
    	
    	if (is_null($type)){
    		$type = $em->getRepository('WebShopBundle:Type')->findOneByName("BD");
    		$cond = "New";
    	}
    	$filter = $session->get('filter');
    	$products = $em->getRepository('WebShopBundle:Product')->findAllByFilter($type, $cond, $search, $filter, $from, $until);
        	
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
    	
    	// set cart if temp not empty
    	
    	
    	
    	
    	if (is_object($user)){
    		// warenkorb befüllen, wenn der user sich neu einloggt
    		$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUserIdOverview($user);
    		
    		if ($tmpCart->getCartProducts()->count() > 0){
    			foreach ($tmpCart->getCartProducts() as $key => $value){
    				$product = $em->getRepository('WebShopBundle:Product')->findOneById($value->getProduct()->getPubId());
    				
    				$cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $product->getId());
    				 
    				if (!is_null($cartProduct)){
    					$cartProduct->setAmount($cartProduct->getAmount()+$value->getAmount());
    				}else {
    				
	    				$cartProduct = new CartProduct();
	    				$cartProduct->setCart($cart);
	    				$cartProduct->setAmount($value->getAmount());
	    				$cartProduct->setProduct($product);
    				}
    				
    				$em->persist($cartProduct);
    				$em->flush();
    			}
    			

    		}
    		
    		
    		
    		$session->set('tmpUser', null);
    		$session->set('tmpCart', null);
    	}else{
    		$cart = $session->get('tmpCart');
    	}
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


   	  
   	  
   	  // Variablen an die View übergeben
      return array('products' => $products,

      		         'search' => $search,
      		         'genres' => $genres,
      		         'type' => $type,
      		         'cond' => $cond,
      		         'from' => $from,
      		         'until' => $until
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
    	if (isset($_POST['from'])){
    	  $session->set('from', (int)$_POST['from']);
    	}
    	if (isset($_POST['until'])){
    	  $session->set('until', (int)$_POST['until']);
    	}
    
    	return $this->redirect($this->generateUrl('default'));
    }
}
