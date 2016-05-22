<?php

namespace Wbc\AdministratorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wbc\AdministratorBundle\Entity\Configuracion;
use Wbc\AdministratorBundle\Form\ConfiguracionType;

/**
 * Configuracion controller.
 *
 */
class ConfiguracionController extends Controller {

    /**
     * Lists all Configuracion entities.
     *
     */
    public function indexAction() {
        $this->get('Services')->setMenuItem('Configuracion');
        $em = $this->getDoctrine()->getManager();

        $configuracions = $em->getRepository('WbcAdministratorBundle:Configuracion')->findAll();

        return $this->render('configuracion/index.html.twig', array(
                    'configuracions' => $configuracions,
        ));
    }

    /**
     * Principal function
     * view
     * @return type
     */
    public function terminalAction() {
        $this->get('Services')->setMenuItem('Terminal');
        return $this->render('configuracion/terminal.html.twig');
    }

    /**
     * Creates a new Configuracion entity.
     *
     */
    public function newAction(Request $request) {
        $this->get('Services')->setMenuItem('Configuracion');

        $configuracion = new Configuracion();
        $form = $this->createForm('Wbc\AdministratorBundle\Form\ConfiguracionType', $configuracion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $now = new \DateTime('now');
            $configuracion->setCreacion($now);

            $em = $this->getDoctrine()->getManager();
            $em->persist($configuracion);
            $em->flush();

            $this->get('Services')->addFlash('success', $this->get('translator')->trans('Configuracion created!'));

            return $this->redirectToRoute('configuracion_index');
        }

        return $this->render('configuracion/new.html.twig', array(
                    'configuracion' => $configuracion,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Configuracion entity.
     *
     */
    public function showAction(Configuracion $configuracion) {

        $this->get('Services')->setMenuItem('Configuracion');
        $changes = $this->get('Services')->getLogsByEntity($configuracion);

        return $this->render('configuracion/show.html.twig', array(
                    'configuracion' => $configuracion,
                    'changes' => $changes,
        ));
    }

    /**
     * Displays a form to edit an existing Configuracion entity.
     *
     */
    public function editAction(Request $request, Configuracion $configuracion) {
        $this->get('Services')->setMenuItem('Configuracion');
        $editForm = $this->createForm('Wbc\AdministratorBundle\Form\ConfiguracionType', $configuracion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($configuracion);
            $em->flush();

            $this->get('Services')->addFlash('success', $this->get('translator')->trans('Configuracion updated!'));

            return $this->redirectToRoute('configuracion_index');
        }

        return $this->render('configuracion/edit.html.twig', array(
                    'configuracion' => $configuracion,
                    'form' => $editForm->createView()
        ));
    }

    /**
     * Deletes a Configuracion entity.
     *
     */
    public function deleteAction(Request $request, Configuracion $configuracion) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($configuracion);
        $em->flush();

        $this->get('Services')->setFlash('danger', $this->get('translator')->trans('Configuracion deleted!'));

        return $this->redirectToRoute('configuracion_index');
    }

}
