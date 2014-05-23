<?php

namespace UAH\GestorActividadesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/{pagina}", requirements={"pagina" = "\d+"}, defaults={"pagina" = 1})
     * @Route("/pag/{pagina}", requirements={"pagina" = "\d+"}, defaults={"pagina" = 1})
     */
    public function indexAction($pagina)
    {
        $name = 'pepe';
        return $this->render('UAHGestorActividadesBundle:Default:index.html.twig', array('name' => $pagina));
    }
}
