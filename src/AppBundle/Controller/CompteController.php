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
     * Lists all Compte entities.
     *
     * @Route("/", name="parametrage_comptes")
     * @Method("GET")
     * @Template("compte/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comptes = $em->getRepository('AppBundle:Compte')->findBy(array(), array('code' => 'ASC'));

        return array(
            'comptes' => $comptes,
        );
    }
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
		
			$this->get('session')->getFlashBag()->add(
            'notice',
            'compte.action_result.collection_ok_added'
			);
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

    
    /**
     * Displays a form to edit an existing Compte entity.
     *
     * @Route("/{code}", name="parametrage_comptes_edit")
     * @Method("GET")
     * @Template("compte/edit.html.twig")
     */
    public function editAction($code)
    {
        $em = $this->getDoctrine()->getManager();

        $compte = $em->getRepository('AppBundle:Compte')->findOneByCode($code);

        if (!$compte) {
			$str = $this->get('translator')->trans('compte.not_found', array('%code%' => $code));
            throw $this->createNotFoundException($str);
        }

        $editForm = $this->createEditForm($compte);
        $deleteForm = $this->createDeleteForm($code);

        return array(
            'compte'      => $compte,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Compte entity.
    *
    * @param Compte $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Compte $compte)
    {
        $form = $this->createForm(new CompteType(), $compte, array(
            'action' => $this->generateUrl('parametrage_comptes_update', array('code' => $compte->getCode())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Compte entity.
     *
     * @Route("/{code}", name="parametrage_comptes_update")
     * @Method("PUT")
     * @Template("compte/edit.html.twig")
     */
    public function updateAction(Request $request, $code)
    {
        $em = $this->getDoctrine()->getManager();

        $compte = $em->getRepository('AppBundle:Compte')->findOneByCode($code);

        if (!$compte) {
            $str = $this->get('translator')->trans('compte.not_found', array('%code%' => $code));
            throw $this->createNotFoundException($str);
        }

        $deleteForm = $this->createDeleteForm($code);
        $editForm = $this->createEditForm($compte);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
			
			$this->get('session')->getFlashBag()->add(
            'notice',
            'compte.action_result.ok_edited'
			);
            
			return $this->redirect($this->generateUrl('parametrage_comptes_edit', array('code' => $code)));
        }

        return array(
            'compte'      => $compte,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Compte entity.
     *
     * @Route("/{code}", name="parametrage_comptes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $code)
    {
        $form = $this->createDeleteForm($code);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $compte = $em->getRepository('AppBundle:Compte')->findOneByCode($code);

            if (!$compte) {
                $str = $this->get('translator')->trans('compte.not_found', array('%code%' => $code));
				throw $this->createNotFoundException($str);
            }

            $em->remove($compte);
            $em->flush();
			
			$this->get('session')->getFlashBag()->add(
            'notice',
            'compte.action_result.ok_deleted'
			);
            
        }

        return $this->redirect($this->generateUrl('parametrage_comptes'));
    }

    /**
     * Creates a form to delete a Compte entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($code)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parametrage_comptes_delete', array('code' => $code)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
