<?php

namespace CheckInBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CheckInBundle:Default:index.html.twig');
    }
}
