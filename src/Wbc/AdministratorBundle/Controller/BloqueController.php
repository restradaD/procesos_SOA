<?php

namespace Wbc\AdministratorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Wbc\AdministratorBundle\Entity\Bloque;
use Wbc\AdministratorBundle\Form\BloqueType;

/**
 * Bloque controller.
 *
 */
class BloqueController extends Controller
{
    /**
     * Lists all Bloque entities.
     *
     */
    public function indexAction()
    {
        $this->get('Services')->setMenuItem('Bloque');
        $em = $this->getDoctrine()->getManager();

        $bloques = $em->getRepository('WbcAdministratorBundle:Bloque')->findAll();

        return $this->render('bloque/index.html.twig', array(
            'bloques' => $bloques,
        ));
    }

    /**
     * Creates a new Bloque entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->get('Services')->setMenuItem('Bloque');

        $bloque = new Bloque();
        $form = $this->createForm('Wbc\AdministratorBundle\Form\BloqueType', $bloque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $now = new \DateTime('now');            
            $bloque->setCreacion($now);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($bloque);
            $em->flush();

            $this->get('Services')->addFlash('success', $this->get('translator')->trans('Bloque created!'));

            return $this->redirectToRoute('bloque_index');
        }

        return $this->render('bloque/new.html.twig', array(
            'bloque' => $bloque,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Bloque entity.
     *
     */
    public function showAction(Bloque $bloque)
    {

        $this->get('Services')->setMenuItem('Bloque');
        $changes = $this->get('Services')->getLogsByEntity($bloque);

        return $this->render('bloque/show.html.twig', array(
            'bloque' => $bloque,
            'changes' => $changes,
        ));
    }

    /**
     * Displays a form to edit an existing Bloque entity.
     *
     */
    public function editAction(Request $request, Bloque $bloque)
    {
        $this->get('Services')->setMenuItem('Bloque');
        $editForm = $this->createForm('Wbc\AdministratorBundle\Form\BloqueType', $bloque);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bloque);
            $em->flush();

            $this->get('Services')->addFlash('success', $this->get('translator')->trans('Bloque updated!'));

            return $this->redirectToRoute('bloque_index');
        }

        return $this->render('bloque/edit.html.twig', array(
            'bloque' => $bloque,
            'form'        => $editForm->createView()
        ));
    }

    /**
     * Deletes a Bloque entity.
     *
     */
    public function deleteAction(Request $request, Bloque $bloque)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($bloque);
        $em->flush();

        $this->get('Services')->setFlash('danger', $this->get('translator')->trans('Bloque deleted!'));

        return $this->redirectToRoute('bloque_index');
    }

}
