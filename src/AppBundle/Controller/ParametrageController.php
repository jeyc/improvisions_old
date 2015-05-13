<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/parametrage")
*/
class ParametrageController extends Controller
{
    /**
     * @Route("/", name="parametrage")
	 * @Template("parametrage.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}

