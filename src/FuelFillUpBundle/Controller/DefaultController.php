<?php

namespace FuelFillUpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('FuelFillUpBundle:Default:index.html.twig');
    }
}
