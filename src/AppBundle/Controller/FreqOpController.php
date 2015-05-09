<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\FreqOp;
use AppBundle\Form\FreqOpType;
use AppBundle\Form\OperationType;
use AppBundle\Entity\FreqOpMouvement;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Mouvement;

/**
 * FreqOp controller.
 *
 * @Route("/operations-frequentes")
 */
class FreqOpController extends Controller
{

    /**
     * Lists all FreqOp entities.
     *
     * @Route("/", name="operations_frequentes")
     * @Method("GET")
     * @Template("FreqOp/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:FreqOp')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new FreqOp entity.
     *
     * @Route("/", name="operations_frequentes_create")
     * @Method("POST")
     * @Template("FreqOp/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new FreqOp();
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

            return $this->redirect($this->generateUrl('operations_frequentes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a FreqOp entity.
     *
     * @param FreqOp $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(FreqOp $entity)
    {
		for ($i = 0; $i < 10; $i++)
		{
			$mouvement = new FreqOpMouvement();
			$mouvement->setFreqOp($entity);
		}
        $form = $this->createForm(new FreqOpType(), $entity, array(
            'action' => $this->generateUrl('operations_frequentes_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new FreqOp entity.
     *
     * @Route("/new", name="operations_frequentes_new")
     * @Method("GET")
     * @Template("FreqOp/new.html.twig")
     */
    public function newAction()
    {
        $entity = new FreqOp();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a FreqOp entity.
     *
     * @Route("/{id}", name="operations_frequentes_show")
     * @Method("GET")
     * @Template("FreqOp/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:FreqOp')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FreqOp entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FreqOp entity.
     *
     * @Route("/{id}/edit", name="operations_frequentes_edit")
     * @Method("GET")
     * @Template("FreqOp/edit.html.twig")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:FreqOp')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FreqOp entity.');
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
    * Creates a form to edit a FreqOp entity.
    *
    * @param FreqOp $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(FreqOp $entity)
    {
        $form = $this->createForm(new FreqOpType(), $entity, array(
            'action' => $this->generateUrl('operations_frequentes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing FreqOp entity.
     *
     * @Route("/{id}", name="operations_frequentes_update")
     * @Method("PUT")
     * @Template("FreqOp/edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:FreqOp')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FreqOp entity.');
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

            return $this->redirect($this->generateUrl('operations_frequentes_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FreqOp entity.
     *
     * @Route("/{id}", name="operations_frequentes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:FreqOp')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FreqOp entity.');
            }

            $em->remove($entity);
            $em->flush();
			
			$this->get('session')->getFlashBag()->add(
				'notice',
				'L\'entité a bien été supprimée.'
			);

        }

        return $this->redirect($this->generateUrl('operations_frequentes'));
    }

    /**
     * Creates a form to delete a FreqOp entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('operations_frequentes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
	
	/**
     * Displays a form to create a new Operation entity from FreqOp entity.
     *
     * @Route("/{id}/new", name="operations_new_from_freqop")
     * @Method("GET")
     * @Template("FreqOp/new_op_from_freqop.html.twig")
     */
    public function newOperationAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $freqop = $em->getRepository('AppBundle:FreqOp')->find($id);

        if (!$freqop) {
            throw $this->createNotFoundException('Unable to find FreqOp entity.');
        }
		
        $op = new Operation();
        $op->setLibelle($freqop->getLibelle());
		
		
		$form = $this->createCreateOperationForm($op, $freqop);

        return array(
			'freqop' => $freqop,
            'op' => $op,
            'form'   => $form->createView(),
        );
    }
	
    /**
     * Creates a form to create a Operation entity from FreqOp entity.
     *
     * @param Operation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateOperationForm(Operation $entity, FreqOp $freqop)
    {

		foreach ($freqop->getMouvements() as $fmouvement)
		{
			$mouvement = new Mouvement();
			$mouvement->setType($fmouvement->getType());
			$mouvement->setCompte($fmouvement->getCompte());
			$mouvement->setOperation($entity);
		}
	
        $form = $this->createForm(new OperationType(), $entity, array(
            'action' => $this->generateUrl('operations_create_from_freqop', array('id' => $freqop->getId())),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Creates a new Operation entity from freqop entity.
     *
     * @Route("/{id}", name="operations_create_from_freqop")
     * @Method("POST")
     * @Template("FreqOp/new_op_from_freqop.html.twig")
     */
    public function createOperationAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $freqop = $em->getRepository('AppBundle:FreqOp')->find($id);

        if (!$freqop) {
            throw $this->createNotFoundException('Unable to find FreqOp entity.');
        }
		
		$entity = new Operation();
		
		$form = $this->createCreateOperationForm($entity, $freqop);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();
			
			$this->get('session')->getFlashBag()->add(
				'notice',
				'L\'entité a bien été créée.'
			);

            return $this->redirect($this->generateUrl('operations_show', array('id' => $entity->getId())));
        }

        return array(
			'freqop' => $freqop,
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

	
	
}
