<?php

namespace TicketingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketingController extends Controller
{
    public function indexAction()
    {
        return $this->render('TicketingBundle:Ticketing:index.html.twig');
    }

    public function userAction()
    {
        return $this->render('TicketingBundle:Ticketing:user.html.twig');
    }

    public function techAction()
    {
        return $this->render('TicketingBundle:Ticketing:tech.html.twig');
    }

    public function adminAction()
    {
        return $this->render('TicketingBundle:Ticketing:admin.html.twig');
    }
}
