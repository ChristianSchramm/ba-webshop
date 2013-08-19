<?php

namespace Web\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bill
 *
 * @ORM\Table(name="bill")
 * @ORM\Entity(repositoryClass="Web\Bundle\ShopBundle\Entity\BillRepository")
 */
class Bill
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
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paid", type="boolean")
     */
    private $paid;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rechnungsnummer", type="string", length=255)
     */
    private $rechnungsnummer;
    
    /**
     * @var string
     *
     * @ORM\Column(name="kundennummer", type="string", length=255)
     */
    private $kundennummer;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="bill")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * construct
     */
    public function  __construct(){
    	$this->date = new \DateTime();
    	$this->paid = false;
    	
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
     * Set path
     *
     * @param string $path
     * @return Bill
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Bill
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     * @return Bill
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;
    
        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean 
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set user
     *
     * @param \Web\Bundle\ShopBundle\Entity\User $user
     * @return Bill
     */
    public function setUser(\Web\Bundle\ShopBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Web\Bundle\ShopBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set rechnungsnummer
     *
     * @param string $rechnungsnummer
     * @return Bill
     */
    public function setRechnungsnummer($rechnungsnummer)
    {
        $this->rechnungsnummer = $rechnungsnummer;
    
        return $this;
    }

    /**
     * Get rechnungsnummer
     *
     * @return string 
     */
    public function getRechnungsnummer()
    {
        return $this->rechnungsnummer;
    }

    /**
     * Set kundennummer
     *
     * @param string $kundennummer
     * @return Bill
     */
    public function setKundennummer($kundennummer)
    {
        $this->kundennummer = $kundennummer;
    
        return $this;
    }

    /**
     * Get kundennummer
     *
     * @return string 
     */
    public function getKundennummer()
    {
        return $this->kundennummer;
    }
}