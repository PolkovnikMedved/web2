<?php
/**
 * Created by PhpStorm.
 * User: sviktor
 * Date: 7/04/18
 * Time: 16:01
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class WelcomeController
{

    /**
     * @Route("/")
     * @return Response
     */
    public function welcome()
    {
        return new Response('Première page en Symfony 4');
    }
}