<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Entity\Compte;
use AppBundle\Collection\CompteCollection;

use AppBundle\Form\CompteType;
use AppBundle\Form\CompteCollectionType;

/**
 * Compte controller.
 *
 * @Route("/parametrage/comptes")
 */
class CompteController extends Controller
{

    /**
     * Crée un ensemble d'entités "Compte"
     *
     * @Route("/", name="parametrage_comptes_create")
     * @Method("POST")
     * @Template("compte/new.html.twig")
     */
    public function createAction(Request $request)
    {
		$comptes = new CompteCollection();
        
        $form = $this->createCreateForm($comptes);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
			foreach ($comptes->getComptes() as $compte)
			{
				$em->persist($compte);
			}
			
			$em->flush();
			
            return $this->redirect($this->generateUrl('parametrage_comptes'));
        }

        return array(
            'comptes' => $comptes,
            'form'   => $form->createView(),
        );
    }

    /**
     * Crée un formulaire pour créer n entités Compte.
     *
     * @param CompteCollection $comptes Des comptes
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(CompteCollection $comptes)
    {
		$nb_comptes = 10;
		
		for ($i = 0; $i < $nb_comptes; $i++)
		{
			$comptes->addCompte(new Compte());
		}
		
        $form = $this->createForm(new CompteCollectionType(), $comptes, array(
            'action' => $this->generateUrl('parametrage_comptes_create'),
            'method' => 'POST',
        ));

        
        return $form;
    }

    /**
     * Affiche un formulaire pour créer n entités Compte
     *
     * @Route("/new", name="parametrage_comptes_new")
     * @Method("GET")
     * @Template("compte/new.html.twig")
     */
    public function newAction()
    {
		$comptes = new CompteCollection();
        $form   = $this->createCreateForm($comptes);

        return array(
            'comptes' => $comptes,
            'form'   => $form->createView(),
        );
    }

}
