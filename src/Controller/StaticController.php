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
        $response = $this->render('static/about.html.twig');

        //$expireDate= new \DateTime('now + 5 minutes');
        //$response->setExpires($expireDate);
        //$response->headers->addCacheControlDirective('must-revalidate', true);
        //$response->setMaxAge(300);
        return $response;
    }
}
