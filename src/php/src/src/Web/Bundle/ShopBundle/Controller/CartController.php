<?php

namespace Web\Bundle\ShopBundle\Controller;



use Doctrine\Common\Collections\ArrayCollection;

use Web\Bundle\ShopBundle\Entity\Product;

use Web\Bundle\ShopBundle\Entity\Bill;

use Web\Bundle\ShopBundle\Helper\FPDFHelper;

use Web\Bundle\ShopBundle\Entity\CartProduct;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CartController extends Controller
{
    /**
     * @Route("/cart/", name="cart")
     * @Template()
     */
    public function indexAction()
    {
    	// Session holen
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	
    	// User aus Session holen
    	$user = $this->get('security.context')->getToken()->getUser();
    	
    	// Wenn User eingeloggt ist, Warenkorb aus Datenbank holen
    	// Wenn nicht, dann hole den Session Warenkorb
    	if (is_object($user)){
    		// Datenbank Warenkorb laden
    		$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	}else {
    		// Session Warenkorb laden
    		$cart = $session->get('tmpCart');
    	}
    	
    	// Warenkorb an View übergeben
    	return array('cart' => $cart);
    }
    
    
    /**
     * @Template()
     */
    public function showCartAction()
    {
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	 
    	// User aus Session laden
    	$user = $this->get('security.context')->getToken()->getUser();
    	 
    	// Wenn User eingeloggt ist, Warenkorb aus Datenbank holen
    	// Wenn nicht, dann hole den Session Warenkorb
    	if (is_object($user)){
    		// Datenbank Warenkorb laden
    		$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	}else {
    		// Session Warenkorb laden
    		$cart = $session->get('tmpCart');
    	}
    	
    	// Warenkorb Übersicht berechnen
    	$cartCount = 0;
    	$cartSum = 0;
    	// Anzhal und Summe der Waren berechnen
    	if (!is_null($cart)){
    		foreach ($cart->getCartProducts() as $product){
    			$cartSum += $product->getAmount() * $product->getProduct()->getPrice();
    			$cartCount += $product->getAmount();
    		}
    	}
    	// Zahl formatieren
    	$cartSum = number_format($cartSum, 2, ',', ' ');

    	 
    	// Warenkrob und Eigenschaften an View übergeben
    	return array('cart' => $cart,
    			         'cartCount' => $cartCount,
      		         'cartSum' => $cartSum
    			        );
    }

    /**
     * @Route("/cart/add/{id}/", name="cart_add")
     * @Template()
     */
    public function addAction($id)
    {
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	
    	// User aus Session laden
    	$user = $this->get('security.context')->getToken()->getUser();

    	// Product aus Dtenbank laden
    	$product = $em->getRepository('WebShopBundle:Product')->findOneById($id);

    	
    	// Wenn User eingeloggt ist, dann die Waren zu dem Datenbank Warenkorb hinzufügen
    	// Wenn nicht, dann die Ware zu dem Session Warenkorb hinzufügen
    	if (is_object($user)){

    	  // Warenkorb des Users aus der Datenbnak laden
    	  $cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	  
    	  // Schaue ob die Waren schon einmal im Warenkorb liegt
    	  $cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $id);
    	  
    	  // Wenn ja, einfach Menge erhöhen
    	  // Wenn nicht, neu zu Warenkorb hinzufügen
    	  if (!is_null($cartProduct)){
    	  	$cartProduct->setAmount($cartProduct->getAmount()+1);
    	  }else {
    	  	$cartProduct = new CartProduct();
    	  	$cartProduct->setCart($cart);
    	  	$cartProduct->setAmount(1);
    	  	$cartProduct->setProduct($product);
    	  }
    	  
    	  // Warenkorb in der Datenbank speichern
    	  $em->persist($cartProduct);
    	  $em->flush();
    	  
    	}else {

    		
    		// Session Warenkorb und User laden, wenn der User nicht eingeloggt ist
    		$user = $session->get("tmpUser");
    		$cart = $session->get("tmpCart");
    		
    		$exits = false;
    		
    		// Testen ob das Product schon einmal im Warenkorb liegt
    		// Wenn ja einfach die Menge erhöhen
    		foreach ($cart->getCartProducts() as $key => $value){
    			if (!is_null($value) && $value->getProduct()->getPubId() == $id ){
    				// incremtnt ammount
    				$cart->getCartProducts()->get($key)->setAmount($value->getAmount()+1);
    				$exits = true;
    			}
    		}

    		// Wenn nicht, neu zum Warenkorb hinzufügen
    		if (!$exits){
    			// new
    			$prod = new Product();
    			$prod->setPrice($product->getPrice());
    			$prod->setTitle($product->getTitle());
    			$prod->setPubId($product->getId()) ;
    			
    			$cartProduct = new CartProduct();
    			$cartProduct->setCart($cart);
    			$cartProduct->setAmount(1);
    			$cartProduct->setProduct($prod);
    			
    			$cart->addCartProduct($cartProduct);
    		}
    		
    		// Warenkorb zurück in die Session speichern
    		$session->set("tmpCart", $cart);
    	}

			// Weiterleitung auf die letzte Seite
    	$url = $this->getRequest()->headers->get("referer");
    	
    	// Wenn Referer leer sein sollte, zurück zum Warenkorb
    	if (empty($url)){
    		return $this->redirect($this->generateUrl('cart'));
    	}
    	
    	return new RedirectResponse($url);
    }

    /**
     * @Route("/cart/del/{id}/", name="cart_del")
     * @Template()
     */
    public function removeAction($id)
    {
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	
    	$em = $this->getDoctrine()->getManager();   	
    	
    	// User aus Session laden
    	$user = $this->get('security.context')->getToken()->getUser();

    	// Wenn User eingeloggt ist, dann die Waren zu dem Datenbank Warenkorb hinzufügen
    	// Wenn nicht, dann die Ware zu dem Session Warenkorb hinzufügen
    	if (is_object($user)){
    		// Warenkorb aus Datenbank laden
	    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
	    	
	    	// Produkt finden und löschen
	    	$cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $id);
	    	
	    	// Warenkorb in Datenbank speichern
	    	$em->remove($cartProduct);
	    	$em->flush();
    	
    	}else {
    		// Warenkorb aus Session holen
    		$cart = $session->get("tmpCart");

    		// Produckt finden und löschen
    		foreach ($cart->getCartProducts() as $key => $value){
    			if (!is_null($value) && $value->getProduct()->getPubId() == $id ){
    				$cart->getCartProducts()->remove($key);
    			}
    		}
    		
    		// Warenkorb in der Session aktualisieren
    		$session->set("tmpCart", $cart);
    	}
    	
    	// Weiterleitung zum Warenkorb
    	return $this->redirect($this->generateUrl('cart'));
    }
    
    /**
     * @Route("/cart/order/", name="cart_order")
     * @Template()
     */
    public function orderAction()
    {
    	// Session laden
    	$session = $this->getRequest()->getSession();
    	$em = $this->getDoctrine()->getManager();
    	 
    	// User aus Session laden
    	$user = $this->get('security.context')->getToken()->getUser();
    	$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
    	
    	// Sollte sich der User nach dem auswahl von Produkte einloggen
    	// werden alle bestehenden Waren aus dem Session Warenkorb in 
    	// den echten Warenkorb übernommen
    	$tmpUser = $session->get('tmpUser');
    	$tmpCart = $session->get('tmpCart');
    	
    	// Testen ob die Session nciht leer ist
    	if (is_object($user) && $tmpUser != null && $tmpCart != null){
    		// warenkorb befüllen, wenn der user sich neu einloggt
    		$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUserIdOverview($user);
    	
    		// Wenn der Temporäre Warenkorb nicht leer ist
    		if ($tmpCart->getCartProducts()->count() > 0){
    			// Jedes Produkt durch gehen
    			foreach ($tmpCart->getCartProducts() as $key => $value){
    				
    				// Produkt in der Datenbank suchen
    				$product = $em->getRepository('WebShopBundle:Product')->findOneById($value->getProduct()->getPubId());
    	  		$cartProduct = $em->getRepository('WebShopBundle:CartProduct')->findOneByCartAndProduct($cart->getId(), $product->getId());
    					
    	  		// Wenn das Produkt im Warenkorb liegt, einfach die Menge angleichen
    				if (!is_null($cartProduct)){
    					$cartProduct->setAmount($cartProduct->getAmount()+$value->getAmount());
    				}else {
    	        // Neues Produkt in den Warenkorb legen
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

    		// Session Warenkorb leeren
    		$session->set('tmpUser', null);
    		$session->set('tmpCart', null);
    	}else{
    		// Session Warenorb laden
    		$cart = $session->get('tmpCart');
    	}
    	
    	// Warenkorb aus der Datenbank alsen    	
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	 
    	// Warenkorb und Adresse an View übergeben
    	return array('cart' => $cart, 'adress' => $adress);
    }
    
    
    
    /**
     * @Route("/cart/checkout/", name="cart_checkout")
     * @Template()
     */
    public function checkoutAction()
    {
    	// Rechnungsnummer ermitteln
    	$rechnungsNummer = "R-". (rand(100000,999999));
    	    	
    	$em = $this->getDoctrine()->getManager();
    	 
    	// User, Adresse und Warenkorb aus der Datenbnak laden
    	$user = $this->get('security.context')->getToken()->getUser();
    	$adress = $em->getRepository('WebShopBundle:Adress')->findOneByUser($user->getId());
    	$cart = $em->getRepository('WebShopBundle:Cart')->findOneByUser($user->getId());
    	
    	$products = $cart->getCartProducts();
    	
    	// Checkout nur ermöglichen, wenn der Warenkorb nicht leer ist
    	if ($products->count() == 0) {
    		// Weiterleitun zur Übersicht, falls der Warenkorb leer ist
    		return $this->redirect($this->generateUrl('cart'));
    	}
    	
    	// Rechnungs PDF erstellen
    	
      // Header einbauen
    	$pdf = new FPDFHelper();
    	$pdf->AddPage();
    	$pdf->SetFont('Arial','B',16);
    	$pdf->Cell(80);
    	$pdf->Cell(30,10,'Rechnung');
    	
    	// Adresse einfügen
    	$pdf->Ln(20);
    	$pdf->SetFont('Arial','',8);
    	$pdf->Cell(30,10,utf8_decode('ScheibenBude, Buden Straße 123, 01234 Scheibenheim'));

    	// Anschrift einfügen
    	$pdf->Ln(15);
    	$pdf->SetFont('Arial','',10);
    	$pdf->Cell(30,10,utf8_decode($adress->getName()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Kunden-Nr.: "));
    	$pdf->Cell(30,10,utf8_decode($user->getNumber()));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getStreet()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Rechnungs-Nr.: "));
    	$pdf->Cell(30,10,utf8_decode($rechnungsNummer));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getPostcode(). " " . $adress->getLocation()));
    	$pdf->Cell(100);
    	$pdf->Cell(30,10,utf8_decode("Datum: "));
    	$pdf->Cell(30,10,utf8_decode(date("d.m.Y")));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode($adress->getCountry()));

    	
    	// Betreff einfügen

    	$pdf->Ln(30);
    	$pdf->SetFont('Arial','',12);
    	$pdf->Cell(30,10,'Rechnung');
    	
    	// Waren auflisten
        // header
    	$pdf->Ln(10);
    	$pdf->SetFont('Arial','B',10);
    	$pdf->Cell(30,10,'Pos.','B');
    	$pdf->Cell(70,10,'Beschreibung','B');
    	$pdf->Cell(20,10,'Menge','B');
    	$pdf->Cell(30,10,'E-Preis','B', 0,'R');
    	$pdf->Cell(30,10,'Preis','B', 0,'R');
        //content
    	$pdf->SetFont('Arial','',10);
    	
      $summe = 0;
      $versand = 6.90;
    	for ($i = 0 ; $i < $products->count(); $i++){
    		$prod = $products[$i];
    		
	    	$pdf->Ln(10);
	    	$pdf->Cell(30,10,$i+1);
	    	$pdf->Cell(70,10,utf8_decode(substr($prod->getProduct()->getTitle(),0,30)));
	    	$pdf->Cell(20,10,utf8_decode($prod->getAmount()));
	    	$pdf->Cell(30,10,utf8_decode(number_format($prod->getProduct()->getPrice(), 2, '.', ''). " EUR"), 0,0,'R');
	    	$pdf->Cell(30,10,utf8_decode(number_format($prod->getAmount() * $prod->getProduct()->getPrice() , 2, '.', ''). " EUR"), 0,0,'R');
	    	
	    	$summe += $prod->getAmount() * $prod->getProduct()->getPrice();
    	}
    	$pdf->Ln(1);
    	$pdf->Cell(180,10,'','B');
    	
    	// Preise ermitteln
    	$pdf->Ln(10);
    	$pdf->SetFont('Arial','',10);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"Netto Summe:");
    	$pdf->Cell(30,10,number_format($summe/1.19 , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"zzgl. 19.00% MwSt.:");
    	$pdf->Cell(30,10,number_format(($summe/119*19) , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"Versandkosten:");
    	$pdf->Cell(30,10,number_format($versand , 2, '.', '')." EUR", 0, 0, 'R');
    	$pdf->Ln(5);
    	$pdf->SetFont('Arial','B',10);
    	$pdf->Cell(120);
    	$pdf->Cell(30,10,"Gesamtbetrag:");
    	$pdf->Cell(30,10,number_format(($summe+$versand) , 2, '.', '')." EUR", 0, 0, 'R');
    	

    	// Abschließender Text
    	
    	$pdf->Ln(20);
    	$pdf->Cell(30,10,utf8_decode("Die Ware bleibt bis zur vollständigen Bezahlung Eigentum des Verkäufers."));
    	$pdf->Ln(5);
    	$pdf->Cell(30,10,utf8_decode("Zahlungsziel ist innerhalb von 14 Tagen nach Erhalt der Rechnung."));
    	$pdf->Ln(10);
    	$pdf->Cell(30,10,utf8_decode("Vielen Dank für Ihren Auftrag!"));
    	
    	//Footer erstellen
    	
    	$pdf->SetY(-25);
    	$pdf->SetFont('Arial','',8);
    	$pdf->Cell(30,0,utf8_decode('USt-ID: DE-13579123 - Bankverbindung: Budenbank - Kontonummer: 123456 - BLZ: 9876543'));
    	 
    	
    	// PDF im Dateisystem sepichern
    	
    	$fileName = "rechnung_".$user->getNumber()."_".$rechnungsNummer.".pdf";
    	
    	$pdf->Output("uploads/bills/".$fileName);
    	
    	// Rechnung in die Datenbank schreiben
    	$bill = new Bill();
    	$bill->setPath($fileName);
    	$bill->setUser($user);
    	$bill->setKundennummer($user->getNumber());
    	$bill->setRechnungsnummer($rechnungsNummer);
    	
    	$em->persist($bill);
    	
    	// amount verringern
    	for ($i = 0 ; $i < $products->count(); $i++){
    		// TODO
    	}
    	
    	// Warenkorb leeren
    	$products = $cart->getCartProducts();
    	for ($i = 0 ; $i < $products->count(); $i++){
    		$em->remove($products[$i]);
    	}
    	   	
    	$em->flush();
    	
    	// Email mit PDF verschicken
    	
    	$message = \Swift_Message::newInstance()     // we create a new instance of the Swift_Message class
    	->setSubject('Scheiben-Bude.de.vu')     // we configure the title
    	->setFrom('notification@scheiben-bude.de.vu')     // we configure the sender
    	->setTo($user->getEmail())     // we configure the recipient
    	->setBody(
    			$this->renderView('WebShopBundle:Cart:checkout.email.html.twig',
    					array( 'user' => $user)),
    			'text/html'
    	)
    	->attach(\Swift_Attachment::fromPath("uploads/bills/".$fileName))
    	// and we pass the $name variable to the text template which serves as a body of the message
    	;
    	$this->get('mailer')->send($message);     // then we send the message.
    	
    	// Erfolgsseite rednern    
    	return array('bill' => $bill);
    }
}
