<?php

namespace Web\Bundle\ShopBundle\Entity;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Web\Bundle\ShopBundle\Entity\UserRepository")
 */
class User  implements UserInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="Password", type="string", length=255)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=255)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;
    
    
    /**
     * @var datetime
     *
     * @ORM\Column(name="last_login", type="datetime",nullable=true)
     */
    private  $lastLogin;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string" ,nullable=true)
     */
    private $ip;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles")
     */
    private $roles;
    

    /**
     * @ORM\OneToMany(targetEntity="Adress", mappedBy="User")
     */
    protected $adresss;
    
    /**
     * @ORM\OneToMany(targetEntity="Cart", mappedBy="User")
     */
    protected $carts;
    
    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="user")
     */
    protected $votes;
    
    
    /**
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="User")
     */
    protected $bills;
    
    
    /**
     * cosntruct
     */
    public function  __construct(){
    	$this->salt = md5(uniqid());
    	
    	$this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->adresss = new \Doctrine\Common\Collections\ArrayCollection(); 
    	$this->carts = new \Doctrine\Common\Collections\ArrayCollection();  
    	$this->bills = new \Doctrine\Common\Collections\ArrayCollection(); 
    }
    
    
    /*
     * methods for UserCheckerInterface
    */
    public function getRole()
    {
    	return $this->getRole()->toArray();
    }
    
    public function eraseCredentials()
    {
    
    }
    
    public function equals(UserInterface $user)
    {
    	return ($this->getUsername() == $user->getUsername() || $this->getEmail() == $user->getUsername());
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
    	return "U-000".$this->getId();
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return User
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    
        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime 
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return User
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    
        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }



    /**
     * Add roles
     *
     * @param \Web\Bundle\ShopBundle\Entity\Role $roles
     * @return User
     */
    public function addRole(\Web\Bundle\ShopBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;
    
        return $this;
    }

    /**
     * Remove roles
     *
     * @param \Web\Bundle\ShopBundle\Entity\Role $roles
     */
    public function removeRole(\Web\Bundle\ShopBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles->toArray();
    }
    



    /**
     * Add adresss
     *
     * @param \Web\Bundle\ShopBundle\Entity\Adress $adresss
     * @return User
     */
    public function addAdress(\Web\Bundle\ShopBundle\Entity\Adress $adresss)
    {
        $this->adresss[] = $adresss;
    
        return $this;
    }

    /**
     * Remove adresss
     *
     * @param \Web\Bundle\ShopBundle\Entity\Adress $adress
     */
    public function removeAdress(\Web\Bundle\ShopBundle\Entity\Adress $adresss)
    {
        $this->adress->removeElement($adresss);
    }

    /**
     * Get adresss
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdress()
    {
        return $this->adresss;
    }

    /**
     * Get adresss
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdresss()
    {
        return $this->adresss;
    }

    /**
     * Add carts
     *
     * @param \Web\Bundle\ShopBundle\Entity\Cart $carts
     * @return User
     */
    public function addCart(\Web\Bundle\ShopBundle\Entity\Cart $carts)
    {
        $this->carts[] = $carts;
    
        return $this;
    }

    /**
     * Remove carts
     *
     * @param \Web\Bundle\ShopBundle\Entity\Cart $carts
     */
    public function removeCart(\Web\Bundle\ShopBundle\Entity\Cart $carts)
    {
        $this->carts->removeElement($carts);
    }

    /**
     * Get carts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCarts()
    {
        return $this->carts;
    }

    /**
     * Add bills
     *
     * @param \Web\Bundle\ShopBundle\Entity\Bill $bills
     * @return User
     */
    public function addBill(\Web\Bundle\ShopBundle\Entity\Bill $bills)
    {
        $this->bills[] = $bills;
    
        return $this;
    }

    /**
     * Remove bills
     *
     * @param \Web\Bundle\ShopBundle\Entity\Bill $bills
     */
    public function removeBill(\Web\Bundle\ShopBundle\Entity\Bill $bills)
    {
        $this->bills->removeElement($bills);
    }

    /**
     * Get bills
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBills()
    {
        return $this->bills;
    }

    /**
     * Add votes
     *
     * @param \Web\Bundle\ShopBundle\Entity\Vote $votes
     * @return User
     */
    public function addVote(\Web\Bundle\ShopBundle\Entity\Vote $votes)
    {
        $this->votes[] = $votes;
    
        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Web\Bundle\ShopBundle\Entity\Vote $votes
     */
    public function removeVote(\Web\Bundle\ShopBundle\Entity\Vote $votes)
    {
        $this->votes->removeElement($votes);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }
}