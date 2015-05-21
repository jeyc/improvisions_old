<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Annee;
use AppBundle\Form\AnneeType;

/**
 * Annee controller.
 *
 * @Route("/annees")
 */
class AnneeController extends Controller
{

    /**
     * Liste toutes les années.
     *
     * @Route("/", name="annees")
     * @Method("GET")
     * @Template("annee/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $annees = $em->getRepository('AppBundle:Annee')->findBy(
			array(),
			array('annee' => 'DESC')
		);

        return array(
            'annees' => $annees,
        );
    }
    /**
     * Crée une nouvelle année
     *
     * @Route("/", name="annees_create")
     * @Method("POST")
     * @Template("annee/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $annee = new Annee();
        $form = $this->createCreateForm($annee);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($annee);
            $em->flush();

            return $this->redirect($this->generateUrl('annees_show', array('annee_annee' => $annee->getAnnee())));
        }

        return array(
            'annee' => $annee,
            'form'   => $form->createView(),
        );
    }

    /**
     * Crée un formulaire de création d'année.
     *
     * @param Annee $annee
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Annee $annee)
    {
        $form = $this->createForm(new AnneeType(), $annee, array(
            'action' => $this->generateUrl('annees_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Affiche un formulaire de création d'année
     *
     * @Route("/new", name="annees_new")
     * @Method("GET")
     * @Template("annee/new.html.twig")
     */
    public function newAction()
    {
        $annee = new Annee();
        $form   = $this->createCreateForm($annee);

        return array(
            'annee' => $annee,
            'form'   => $form->createView(),
        );
    }

    /**
     * Trouve et affiche les détails d'une année.
     *
     * @Route("/{annee_annee}", name="annees_show")
     * @Method("GET")
     * @Template("annee/show.html.twig")
     */
    public function showAction($annee_annee)
    {
        $em = $this->getDoctrine()->getManager();

        $annee = $em->getRepository('AppBundle:Annee')->findOneByAnnee($annee_annee);

        if (!$annee) {
            throw $this->createNotFoundException('Unable to find Annee entity.');
        }

        return array(
            'annee'      => $annee,
        );
    }


}
