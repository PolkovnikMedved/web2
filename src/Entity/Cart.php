<?php
/**
 * Created by IntelliJ IDEA.
 * User: sviktor
 * Date: 4/06/18
 * Time: 19:35
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartRepository")
 * @ORM\Table(name = "T_CART")
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", name="cart_number")
     */
    private $cartNumber;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="carts")
     * @ORM\JoinColumn(name = "fk_user_id")
     */
    private $customer;

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="carts")
     * @ORM\JoinColumn(name = "fk_product_id")
     */
    private $product;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $productQuantity;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, name ="total_price")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="datetime", name = "created_at")
     */
    private $createdAt;

    /**
     * Cart constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->productQuantity = 0;
    }

    /**
     * @return mixed
     */
    public function getCartNumber()
    {
        return $this->cartNumber;
    }

    /**
     * @param mixed $cartNumber
     */
    public function setCartNumber($cartNumber)
    {
        $this->cartNumber = $cartNumber;
    }


    /**
     * @return mixed
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @return int
     */
    public function getProductQuantity(): int
    {
        return $this->productQuantity;
    }

    /**
     * @param int $productQuantity
     */
    public function setProductQuantity(int $productQuantity)
    {
        $this->productQuantity = $productQuantity;
    }



    /**
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->totalPrice;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @param mixed $totalPrice
     */
    public function setTotalPrice($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }


}