<?php

namespace Web\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GenreController extends Controller
{
    /**
     * @Route("/admin/genre/", name="admin_genre")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$genres = $em->getRepository('WebShopBundle:Genre')->findAll();
    	
    	return array('genres' => $genres);
    }

}
