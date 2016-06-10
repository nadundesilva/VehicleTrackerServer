<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {
    public function homeAction() {
        return $this->render('CoreBundle:Home:home.html.twig');
    }
}
