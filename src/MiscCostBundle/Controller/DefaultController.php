<?php

namespace MiscCostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MiscCostBundle:Default:index.html.twig');
    }
}
