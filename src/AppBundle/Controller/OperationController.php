<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Ecriture;
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
     * @Template("operation/index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $operations = $em->getRepository('AppBundle:Operation')->findBy(array(), array('date' => 'DESC'));

        return array(
            'operations' => $operations,
        );
    }
    /**
     * Creates a new Operation entity.
     *
     * @Route("/", name="operations_create")
     * @Method("POST")
     * @Template("operation/new.html.twig")
     */
    public function createAction(Request $request)
    {
        $operation = new Operation();
        $form = $this->createCreateForm($operation);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($operation);
            $em->flush();

            return $this->redirect($this->generateUrl('operations_show', array('id' => $operation->getId())));
        }

        return array(
            'operation' => $operation,
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
    private function createCreateForm(Operation $operation)
    {
		$nb_ecritures = 5;
		
		for ($i = 0; $i < $nb_ecritures; $i++)
		{
			$operation->addEcriture(new Ecriture());
		}
        $form = $this->createForm(new OperationType(), $operation, array(
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
     * @Template("operation/new.html.twig")
     */
    public function newAction()
    {
        $operation = new Operation();
        $form   = $this->createCreateForm($operation);

        return array(
            'operation' => $operation,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Operation entity.
     *
     * @Route("/{id}", name="operations_show")
     * @Method("GET")
     * @Template("operation/show.html.twig")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $operation = $em->getRepository('AppBundle:Operation')->find($id);

        if (!$operation) {
            $str = $this->get('translator')->trans('operation.not_found');
			throw $this->createNotFoundException($str);
        }

        return array(
            'operation'      => $operation,
        );
    }

}
