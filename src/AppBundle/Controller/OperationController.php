<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Mouvement;
use AppBundle\Form\OperationType;

/**
 * Operation controller.
 *
 * @Route("/operations")
 */
class OperationController extends Controller
{

    /**
     * Lists all Operation entities.
     *
     * @Route("/", name="operations")
     * @Method("GET")
     * @Template("Operation/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Operation')->findBy(
			array(),
			array('date' => 'DESC')
		);

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Operation entity.
     *
     * @Route("/", name="operations_create")
     * @Method("POST")
     * @Template("Operation/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Operation();
		
		$form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
			
			$this->get('session')->getFlashBag()->add(
				'notice',
				'L\'entité a bien été créée.'
			);

            return $this->redirect($this->generateUrl('operations_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Operation entity.
     *
     * @param Operation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Operation $entity)
    {
		for ($i = 0; $i < 10; $i++)
		{
			$mouvement = new Mouvement();
			$mouvement->setOperation($entity);
		}

        $form = $this->createForm(new OperationType(), $entity, array(
            'action' => $this->generateUrl('operations_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new Operation entity.
     *
     * @Route("/new", name="operations_new")
     * @Method("GET")
     * @Template("Operation/new.html.twig")
     */
    public function newAction()
    {
        $entity = new Operation();
        
		$form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Operation entity.
     *
     * @Route("/{id}", name="operations_show")
     * @Method("GET")
     * @Template("Operation/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Operation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Operation entity.
     *
     * @Route("/{id}/edit", name="operations_edit")
     * @Method("GET")
     * @Template("Operation/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Operation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
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
    * Creates a form to edit a Operation entity.
    *
    * @param Operation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Operation $entity)
    {
        $form = $this->createForm(new OperationType(), $entity, array(
            'action' => $this->generateUrl('operations_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Operation entity.
     *
     * @Route("/{id}", name="operations_update")
     * @Method("PUT")
     * @Template("Operation/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Operation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Operation entity.');
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

            return $this->redirect($this->generateUrl('operations_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Operation entity.
     *
     * @Route("/{id}", name="operations_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Operation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Operation entity.');
            }

            $em->remove($entity);
            $em->flush();
			
			$this->get('session')->getFlashBag()->add(
				'notice',
				'L\'entité a bien été supprimée.'
			);

        }

        return $this->redirect($this->generateUrl('operations'));
    }

    /**
     * Creates a form to delete a Operation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('operations_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
