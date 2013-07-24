<?php

namespace Web\Bundle\ShopBundle\Controller;

use Entities\User;

use Web\Bundle\ShopBundle\Repository;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Web\Bundle\ShopBundle\Form\Type\RegistrationType;
use Web\Bundle\ShopBundle\Form\Model\Registration;

use Symfony\Component\Security\Core\SecurityContext;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
    	
    	$request = $this->getRequest();
    	$session = $request->getSession();
    	
    	// get the login error if there is one
    	if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
    		$error = $request->attributes->get(
    				SecurityContext::AUTHENTICATION_ERROR
    		);
    	} else {
    		$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
    		$session->remove(SecurityContext::AUTHENTICATION_ERROR);
    	}
    	
    	return 
    			array(
    					// last username entered by the user
    					'last_username' => $session->get(SecurityContext::LAST_USERNAME),
    					'error'         => $error,
    			
    	);

    }
    
    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheckAction(){
 
    	// The security layer will intercept this request
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function  logoutAction(){
    	// The security layer will intercept this request
    	
    }
    
    /**
     * @Route("/register", name="register")
     * @Template()
     */
    public function registerAction(){
       $form = $this->createForm(
            new RegistrationType(),
            new Registration()
        );

        return array('form' => $form->createView());
    }
    
    
    /**
     * @Route("/create", name="create")
     * @Template()
     */
    public function createAction(){
			$em = $this->getDoctrine()->getManager();
			
	    $form = $this->createForm(new RegistrationType(), new Registration());
	
	    $form->bind($this->getRequest());
	
	    if ($form->isValid()) {
	    	
	    	$registration = $form->getData();
	     	$user = $registration->getUser();
	    	
	    	// unique user ?
	    	$unique = $em->getRepository('RCTracksUserBundle:User')->isUserUnique($user->getUsername());
	   
				if ($unique){
						
					//load inactive role
					$role = $em->getRepository('RCTracksUserBundle:Role')->findOneByName('ROLE_INACTIVE');
		      $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
		        //encode password using current encoder
		      $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
		        
		      //set encrypted password
	
		      $user->setPassword($password);
		      $user->addUserRole($role);
		        
		      $em->persist($user);
		        
		        
		      $activation = new Activation();
				  $activation->setUser($user);
		      $em->persist($activation);
	
		        
		      $adress = new Adress();
		      $profile = new Profile();
		      $adress->setProfile($profile);
		      $profile->setUser($user);
		        
		      $em->persist($profile);
		      $em->persist($adress);
		        
		      $em->flush();
		        
		      // send atctivation mail
		       	        
		      $message = \Swift_Message::newInstance()     // we create a new instance of the Swift_Message class
		        					->setSubject('RCTracks.net account activation')     // we configure the title
		        	        ->setFrom('notification@rctracks.net')     // we configure the sender
		        	        ->setTo($user->getEmail())     // we configure the recipient
		        	        ->setBody(
		        	        		$this->renderView('RCTracksUserBundle:Security:activation.email.html.twig', 
		        	        				              array( 'code' => $activation->getCode(), 'user' => $user)),
		        	        		'text/html'
		        	        		)
		        
		        // and we pass the $name variable to the text template which serves as a body of the message
		        ;
		      $this->get('mailer')->send($message);     // then we send the message.
		        
	     		//return array( );
		
		      return array('user' => $user);
				}
				
				
	    }
	
			return $this->render('RCTracksUserBundle:Security:new.html.twig', array(
					'user' => $user,
					'form' => $form->createView(),
	    ));
    }
    
    /**
     * @Route("/account", name="account")
     * @Template()
     */
    public function accountAction()
    {
    	if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
    		return $this->redirect($this->generateUrl('login'));

    	}    	
    	return array( );
    
    }
    
    /**
     * @Route("/activate/{code}", name="activate")
     * @Template()
     */
    public function activateAction($code){
    	$em = $this->getDoctrine()->getManager();
    	$user = $em->getRepository('RCTracksUserBundle:User')->loadUserByActivateCode($code);
    	
    	if ($user){
    		$user->removeUserRole($user->getUserRoles()->first());
    		$role = $em->getRepository('RCTracksUserBundle:Role')->findOneByName('ROLE_USER');
    		$user->addUserRole($role);
    		
    		$em->persist($user);
    		$em->flush();
    		
    		$activation = $em->getRepository('RCTracksUserBundle:Activation')->findOneByCode($code);
    		
    		if ($activation){
    			
    			$em->remove($activation);
    			$em->flush();

    		}
    		return array('user' => $user);
    	}
    	return array();
    }

    
}