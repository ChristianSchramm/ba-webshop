<?php

namespace Web\Bundle\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Payment
 *
 * @ORM\Table(name="payments")
 * @ORM\Entity(repositoryClass="Web\Bundle\ShopBundle\Entity\PaymentRepository")
 */
class Payment
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
     * @ORM\Column(name="Name", type="string", length=255)
     */
    private $name;
    
    
    /**
     * @var string
     *
     * @ORM\Column(name="AccountNumber", type="string", length=255, nullable=true)
     */
    private $accountNumber;
    
    /**
     * @var string
     *
     * @ORM\Column(name="BankCode", type="string", length=255, nullable=true)
     */
    private $bankCode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="BankName", type="string", length=255, nullable=true)
     */
    private $bankName;


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
     * Set name
     *
     * @param string $name
     * @return Payment
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     * @return Payment
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;
    
        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string 
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set bankCode
     *
     * @param string $bankCode
     * @return Payment
     */
    public function setBankCode($bankCode)
    {
        $this->bankCode = $bankCode;
    
        return $this;
    }

    /**
     * Get bankCode
     *
     * @return string 
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     * @return Payment
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
    
        return $this;
    }

    /**
     * Get bankName
     *
     * @return string 
     */
    public function getBankName()
    {
        return $this->bankName;
    }
}