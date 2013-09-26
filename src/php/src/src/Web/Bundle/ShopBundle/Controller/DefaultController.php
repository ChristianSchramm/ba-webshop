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
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	
    	// Hole bestehende Filter ausSession
    	$search = $session->get('search');
    	$type = $session->get('type');
    	$cond = $session->get('cond');
    	
      // erstellen Temporäeren Warenkorb für unregistrierte User
    	$tmpUser = $session->get('tmpUser');
    	$tmpCart = $session->get('tmpCart');
    	if (is_null($tmpUser)){
    		$tmpUser = new User();
    		$session->set('tmpUser', $tmpUser);
    		$tmpCart = new Cart();
    		$session->set('tmpCart', $tmpCart);  
    		   		
    	}
    	
    	
    	// Filter Preis von initialisieren
    	$from = $session->get('from');
    	if (is_null($from)){
    		$from = 0;
    	}
    	// Filter Preis bis initialisieren
    	$until = $session->get('until');
    	if (is_null($until)){
    		$until = 100;
    	}
    	
    	// Testen ob until kleiner als from ist, notfalls tauschen
    	if ($until < $from){
    		$tmp = $until;
    		$until = $from;
    		$from = $tmp;
    	}
    	
    	// Filter für Typ initialisieren
    	if (is_null($type)){
    		$type = $em->getRepository('WebShopBundle:Type')->findOneByName("BD");
    		$cond = "New";
    	}
    	$filter = $session->get('filter');
    	
    	// gefilterte Produkte aus Datenbank laden
    	$products = $em->getRepository('WebShopBundle:Product')->findAllByFilter($type, $cond, $search, $filter, $from, $until);
        	
    	
    	// Votes und Berwertungen für Produkte berechnen
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
    	
      // User aus session laden
    	$user = $this->get('security.context')->getToken()->getUser();
    	

    	// Wenn User eingeloggt ist, Warenkorb aus Datenbank holen
    	// Wenn nicht, dann hole den Session Warenkorb
    	if (is_object($user)){
    		// warenkorb befüllen, wenn der user sich neu einloggt
    		$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUserIdOverview($user);
    		
    		// Nur wenn Warenkorb nicht leer ist
    		if ($tmpCart->getCartProducts()->count() > 0){
    			// Alle Proukte abarbeiten
    			foreach ($tmpCart->getCartProducts() as $key => $value){
    				$product = $em->getRepository('WebShopBundle:Product')->findOneById($value->getProduct()->getPubId());
    				
    				$cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $product->getId());
    				 
    				// Wenn Artikel schon einmal im Warenkorb ist, einfach die Menge anpassen
    				if (!is_null($cartProduct)){
    					$cartProduct->setAmount($cartProduct->getAmount()+$value->getAmount());
    				}else {
    				// Produkt neu in den Warenkorb legen
	    				$cartProduct = new CartProduct();
	    				$cartProduct->setCart($cart);
	    				$cartProduct->setAmount($value->getAmount());
	    				$cartProduct->setProduct($product);
    				}
    				// Warenkorb speichern
    				$em->persist($cartProduct);
    				$em->flush();
    			}
    		}
				// Session Warenkorb löschen
    		$session->set('tmpUser', null);
    		$session->set('tmpCart', null);
    	}else{
    		$cart = $session->get('tmpCart');
    	}
    	
    	// Alle Genres für Filter aus der Datenbank laden
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
      		         'from' => $from. " €",
      		         'until' => $until. " €"
      		);
    }
    
    
    /**
     * @Route("/dvd/{cond}", name="dvd", defaults={"cond" = "New"})
     * @Template()
     */
    public function dvdAction($cond)
    {
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	// Filter Type auf DVD setzen
    	$type = $em->getRepository('WebShopBundle:Type')->findOneByName("DVD");
    	
      $session->set('type', $type);
      $session->set('cond', $cond);
      
      // Zurück zur Übersicht      
      return $this->redirect($this->generateUrl('default'));
    }
    
    /**
     * @Route("/bd/{cond}", name="bd", defaults={"cond" = "New"})
     * @Template()
     */
    public function bdAction($cond)
    {
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	 
    	$em = $this->getDoctrine()->getManager();
    	
    	// Filter Type auf BluRay setzen
    	$type = $em->getRepository('WebShopBundle:Type')->findOneByName("BD");
    	 
    	$session->set('type', $type);
      $session->set('cond', $cond);

      // Zurück zur Übersicht
    	return $this->redirect($this->generateUrl('default'));
    }
    
    /**
     * @Route("/cd/{cond}", name="cd", defaults={"cond" = "New"})
     * @Template()
     */
    public function cdAction($cond)
    {
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	 
    	$em = $this->getDoctrine()->getManager();
    	
    	// Filter Type auf CD setzen
    	$type = $em->getRepository('WebShopBundle:Type')->findOneByName("CD");
    	 
    	$session->set('type', $type);
      $session->set('cond', $cond);

      // Zurück zur Übersicht
    	return $this->redirect($this->generateUrl('default'));
    }
    
    
    /**
     * @Route("/search/", name="search")
     * @Template()
     */
    public function searchAction()
    {
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	
    	// Suchfilter setzen
    	$session->set('search', $_POST['search']);
    	

    	// Zurück zur Übersicht
    	return $this->redirect($this->generateUrl('default'));
    }
    
    /**
     * @Route("/filter/", name="filter")
     * @Template()
     */
    public function filterAction()
    {
    	// Session Laden
    	$session = $this->getRequest()->getSession();
    	
    	// Genre Filter und From/Until Filter laden
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

    	// Zurück zur Übersicht
    	return $this->redirect($this->generateUrl('default'));
    }
}
