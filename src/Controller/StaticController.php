<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Security\Core\User\UserInterface;

class StaticController extends Controller
{
    /**
     * @Route("/about", name="about")
     */
    public function about(Request $request)
    {
        //$translator = $this->get('translator');
        //$translator->setLocale('es_ES');
        //return new Response(var_dump($translator));
        //$request->setLocale("es_ES");
        //return new Response(dump($user));
        return $this->render('static/about.html.twig', [
            'controller_name' => 'StaticController',
        ]);
    }
}
