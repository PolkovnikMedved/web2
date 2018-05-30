<?php
/**
 * Created by IntelliJ IDEA.
 * User: sviktor
 * Date: 27/05/18
 * Time: 11:09
 */

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends Controller
{
    /**
     * @Route("/cart/{productId}/add", name="add_to_cart")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addToCart(int $productId, SessionInterface $session)
    {
        $articlesArray = $session->get('cart', array()); // Retrieve cart data from session
        $articlesArray[] = $productId;  // Add product id to array

        $session->set('cart', $articlesArray); // add article id to session

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



        return $this->redirectToRoute("cart");
    }

    /**
     * @Route("/cart/", name="cart")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cart(SessionInterface $session, ProductRepository $productRepository)
    {
        $productIds = $session->get('cart', array());

        $productQuantity = array();
        foreach($productIds as $id)
        {
            $numberOfProducts = 0;
            foreach($productIds as $secondId)
            {
                if($id == $secondId)
                {
                    $numberOfProducts++;
                }
            }
            $productQuantity[] = array($productRepository->find($id), $numberOfProducts);
        }

        return $this->render('cart/index.html.twig', ['products' => $productQuantity]);
    }
}