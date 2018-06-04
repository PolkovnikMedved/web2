<?php
/**
 * Created by IntelliJ IDEA.
 * User: sviktor
 * Date: 4/06/18
 * Time: 19:36
 */

namespace App\Repository;


use App\Entity\Cart;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function getLastUserCartNumber(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select('MAX(c.cartNumber)')
            ->where('c.customer = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleResult();
    }

    public function getUserCarts(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select(array('c.cartNumber', 'SUM(c.totalPrice) as sum', 'c.createdAt'))
            ->where('c.customer = :user')
            ->setParameter('user', $user)
            ->groupBy('c.cartNumber')
            ->orderBy('c.cartNumber')
            ->orderBy('c.createdAt')
            ->getQuery()
            ->getResult();
    }

    public function getUserCart(User $user, int $cartNumber)
    {
        return $this->createQueryBuilder('c')
            ->where('c.customer = :user')
            ->andWhere('c.cartNumber = :cartNumber')
            ->setParameter('user', $user)
            ->setParameter('cartNumber', $cartNumber)
            ->getQuery()
            ->getResult();
    }
}