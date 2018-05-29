<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;

/**
 * @ORM\Table(name="atl_orders")
 * @ORM\Entity
 */
class Order {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\OneToOne(targetEntity="JMS\Payment\CoreBundle\Entity\PaymentInstruction") */
    private $paymentInstruction;

    /** @ORM\Column(type="string", unique = true) */
    private $orderNumber;

    /** @ORM\Column(type="decimal", precision=10, scale=5) */
    private $amount;

    /** @ORM\OneToOne(targetEntity="User") */
    private $user;

    /** @ORM\OneToOne(targetEntity="Event") */
    private $event;

    public function __construct($amount, $user, $event) {
        $this->amount = $amount;
        $this->user = $user;
        $this->event = $event;
    }

    public function getId() {
        return $this->id;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getPaymentInstruction() {
        return $this->paymentInstruction;
    }

    public function setPaymentInstruction(PaymentInstruction $instruction) {
        $this->paymentInstruction = $instruction;
    }

}
