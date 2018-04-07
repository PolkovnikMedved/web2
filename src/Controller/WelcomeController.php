<?php
/**
 * Created by PhpStorm.
 * User: sviktor
 * Date: 7/04/18
 * Time: 16:01
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController extends AbstractController
{

    /**
     * @Route("/")
     * @return Response
     */
    public function welcome()
    {
        return $this->render("welcome.html.twig", ['title' => 'Hi bro']);
    }


}