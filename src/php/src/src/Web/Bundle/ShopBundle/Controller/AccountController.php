<?php

namespace Web\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AccountController extends Controller
{
    
    /**
     * @Route("/account/", name="account")
     * @Template()
     */
    public function indexAction()
    {
    	if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
    		return $this->redirect($this->generateUrl('login'));

    	}    	
    	return array( );
    
    }

}
