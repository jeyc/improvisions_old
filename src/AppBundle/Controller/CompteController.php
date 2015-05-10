<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Compte;
use AppBundle\Form\CompteType;
use AppBundle\Form\CompteCollectionType;

use AppBundle\Entity\CompteCollection;

/**
 * Compte controller.
 *
 * @Route("/comptes")
 */
class CompteController extends Controller
{

    /**
     * Lists all Compte entities.
     *
     * @Route("/", name="comptes")
     * @Method("GET")
     * @Template("Compte/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Compte')->findBy(
			array(),
			array('numero' => 'ASC')
		);

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Compte entity.
     *
     * @Route("/", name="comptes_create")
     * @Method("POST")
     * @Template("Compte/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $comptes = new CompteCollection();
        $form = $this->createCreateCollectionForm($comptes);
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
				'Les comptes ont été correctement créés.'
			);

            return $this->redirect($this->generateUrl('comptes'));
        }

        return array(
            'form'   => $form->createView(),
        );
    }

	/**
     * Creates a form to create x Compte entities.
     *
     * @param Compte $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateCollectionForm(CompteCollection $comptes)
    {
		for ($i = 0; $i < 10; $i++)
		{
			$comptes->addCompte(new Compte);
		}
        
        $form = $this->createForm(new CompteCollectionType(), $comptes, array(
            'action' => $this->generateUrl('comptes_create'),
            'method' => 'POST',
        ));

        return $form;
    }

	
    /**
     * Displays a form to create a new Compte entity.
     *
     * @Route("/ajout", name="comptes_new")
     * @Method("GET")
     * @Template("Compte/new.html.twig")
     */
    public function newAction()
    {
		$comptes = new CompteCollection();
		$form   = $this->createCreateCollectionForm($comptes);

        return array(
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Compte entity.
     *
     * @Route("/{id}", name="comptes_show")
     * @Method("GET")
     * @Template("Compte/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Compte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compte entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Compte entity.
     *
     * @Route("/{id}/edit", name="comptes_edit")
     * @Method("GET")
     * @Template("Compte/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Compte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compte entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
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
    private function createEditForm(Compte $entity)
    {
        $form = $this->createForm(new CompteType(), $entity, array(
            'action' => $this->generateUrl('comptes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Compte entity.
     *
     * @Route("/{id}", name="comptes_update")
     * @Method("PUT")
     * @Template("Compte/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Compte')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Compte entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

			$this->get('session')->getFlashBag()->add(
				'notice',
				'L\'entité a bien été modifiée.'
			);

            return $this->redirect($this->generateUrl('comptes_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Compte entity.
     *
     * @Route("/{id}", name="comptes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Compte')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Compte entity.');
            }

            $em->remove($entity);
            $em->flush();
			
			$this->get('session')->getFlashBag()->add(
				'notice',
				'L\'entité a bien été supprimée.'
			);

        }

        return $this->redirect($this->generateUrl('comptes'));
    }

    /**
     * Creates a form to delete a Compte entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comptes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
