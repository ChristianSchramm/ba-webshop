<?php 
namespace Web\Bundle\ShopBundle\Form\Model;


use Symfony\Component\Validator\Constraints as Assert;

use Web\Bundle\ShopBundle\Entity\Adress;
use Web\Bundle\ShopBundle\Entity\User;


class SecurityForm
{
    /**
     * @Assert\Type(type="Web\Bundle\ShopBundle\Entity\User")
     */
    protected $security;
    

    
    /**
     * @Assert\Type(type="Web\Bundle\ShopBundle\Entity\Adress")
     */
    protected $adress;

    

    public function setSecurity(User $security)
    {
        $this->security = $security;
    }

    public function getSecurity()
    {
        return $this->security;
    }
    
    public function setAdress(Adress $adress)
    {
    	$this->adress = $adress;
    }
    
    public function getAdress()
    {
    	return $this->adress;
    }
    



}