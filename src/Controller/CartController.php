<?php
/**
 * Created by IntelliJ IDEA.
 * User: sviktor
 * Date: 27/05/18
 * Time: 11:09
 */

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\ProductRepository;
use function PhpParser\filesInDir;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class CartController extends Controller
{
    /**
     * @Route("/cart/{productId}/add", name="add_to_cart")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addToCart(int $productId, SessionInterface $session, LoggerInterface $logger)
    {
        $oldArticlesArray = $session->get('cart', array()); // Retrieve cart data from session

        $logger->debug('ARTICLES ARRAY BEGIN = '.json_encode($oldArticlesArray));

        $found = false;
        if(empty($oldArticlesArray))
        {
            $logger->debug('EMPTY');
            $oldArticlesArray[0]['productId'] = $productId;
            $oldArticlesArray[0]['quantity'] = 1;
        }
        else
        {
            foreach ($oldArticlesArray as $key => $value)
            {
                if($oldArticlesArray[$key]['productId'] == $productId)
                {
                    $logger->debug('FOUND');
                    $found = true;
                    if($oldArticlesArray[$key]['quantity'] < 10)
                    {
                        $oldArticlesArray[$key]['quantity'] += 1;
                    }
                }
            }

            if(!$found)
            {
                $logger->debug('NOT FOUND');
                $oldArticlesArray[] = ['productId' => $productId, 'quantity' => 1];
            }
        }

        $logger->debug('ARTICLES ARRAY END = '.json_encode($oldArticlesArray));

        $session->set('cart', $oldArticlesArray); // add article id to session

        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/{productId}/remove", name="remove_from_cart")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function removeFromCart(int $productId, SessionInterface $session)
    {
        $articlesArray = $session->get('cart', array()); // Retrieve cart data from session

        // If we find $productId in the cart array we decrement quantity
        foreach ($articlesArray as $key => $value)
        {
            if($articlesArray[$key]['productId'] == $productId)
            {
                if($articlesArray[$key]['quantity'] > 1)
                {
                    $articlesArray[$key]['quantity'] -= 1;
                }
                else
                {
                    unset($articlesArray[$key]);
                }
            }
        }

        $session->set('cart', $articlesArray); // add article id to session

        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/", name="cart")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cart(SessionInterface $session, ProductRepository $productRepository, LoggerInterface $logger) :Response
    {
        $productIdQuantity = $session->get('cart', array());

        $logger->debug('ARTICLES ARRAY BEGIN = '.json_encode($productIdQuantity));

        $productQuantity = array();
        foreach ($productIdQuantity as $productQ)
        {
            $productQuantity[] = array($productRepository->find($productQ['productId']), $productQ['quantity']);
        }

        return $this->render('cart/index.html.twig', ['products' => $productQuantity]);
    }

    /**
     *
     * @Route("/cart/save", name="save_cart")
     * @Security("has_role('ROLE_USER')")
     *
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     * @param CartRepository $cartRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function saveCart(SessionInterface $session, ProductRepository $productRepository, CartRepository $cartRepository, LoggerInterface $logger) :Response
    {
        $productIdQuantity = $session->get('cart', array());

        $userLastCartNumber = $cartRepository->getLastUserCartNumber($this->getUser());
        $cartNumber = 0;

        $logger->info('LAST NUMBER = '.json_encode($userLastCartNumber));

        $logger->info('NUMBER = '.$userLastCartNumber[1]);
        if(intval($userLastCartNumber[1]) >= 0)
        {
            $logger->info('YES');
            $cartNumber = intval($userLastCartNumber[1]) + 1;
        }

        $logger->info('NEW CART NUMBER = '.$cartNumber);

        foreach ($productIdQuantity as $productQ)
        {
            $product = $productRepository->find($productQ['productId']);
            if($product){
                $total = $product->getPrice() * $productQ['quantity'];
            } else {
                $total = 0;
            }

            $cart = new Cart();
            $cart->setCartNumber($cartNumber);
            $cart->setCustomer($this->getUser());
            $cart->setProduct($product);
            $cart->setProductQuantity($productQ['quantity']);
            $cart->setTotalPrice($total);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cart);
            $em->flush();
        }

        $this->addFlash('success', 'The cart data has been saved.');
        $session->set('cart', array());
        return $this->redirectToRoute('welcome');
    }

    /**
     * @Route("/cart/user-carts", name="user_carts")
     * @Security("has_role('ROLE_USER')")
     *
     * @param CartRepository $cartRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userPurchases(CartRepository $cartRepository) :Response
    {
        return $this->render('cart/user-carts.html.twig', ['carts' => $cartRepository->getUserCarts($this->getUser())]);
    }

    /**
     * @Route("/cart/{cartNumber}/user-cart", name="user_cart")
     * @Security("has_role('ROLE_USER')")
     *
     * @param CartRepository $cartRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userOnePurchase(int $cartNumber, CartRepository $cartRepository):Response
    {
        return $this->render('cart/user-cart.html.twig', ['cart' =>$cartRepository->getUserCart($this->getUser(), $cartNumber)]);
    }
}