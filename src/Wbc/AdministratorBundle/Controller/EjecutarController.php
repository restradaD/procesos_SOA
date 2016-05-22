<?php

namespace Wbc\AdministratorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Wbc\AdministratorBundle\Entity\Ejecutar;
use Wbc\AdministratorBundle\Entity\Configuracion;
use Wbc\AdministratorBundle\Form\EjecutarType;

/**
 * Ejecutar controller.
 *
 */
class EjecutarController extends Controller
{
    /**
     * Lists all Ejecutar entities.
     *
     */
    public function indexAction()
    {
        $this->get('Services')->setMenuItem('Ejecutar');
        $em = $this->getDoctrine()->getManager();

        $ejecutars = $em->getRepository('WbcAdministratorBundle:Ejecutar')->findAll();

        return $this->render('ejecutar/index.html.twig', array(
            'ejecutars' => $ejecutars,
        ));
    }

    /**
     * Creates a new Ejecutar entity.
     *
     */
    public function newAction(Configuracion $configuracion, Request $request)
    { 
        $this->get('Services')->setMenuItem('Ejecutar');

        $ejecutar = new Ejecutar();
        $form = $this->createForm('Wbc\AdministratorBundle\Form\EjecutarType', $ejecutar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {            
            
            $now = new \DateTime('now');            
            $ejecutar->setCreacion($now);
            $ejecutar->setConfiguracion($configuracion);
            $ejecutar->setUser($this->getUser());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($ejecutar);
            $em->flush();

            $this->get('Services')->addFlash('success', $this->get('translator')->trans('Ejecutar created!'));

            return $this->redirectToRoute('ejecutar_index');
        }

        return $this->render('ejecutar/new.html.twig', array(
            'ejecutar' => $ejecutar,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Ejecutar entity.
     *
     */
    public function showAction(Ejecutar $ejecutar)
    {

        $this->get('Services')->setMenuItem('Ejecutar');
        $changes = $this->get('Services')->getLogsByEntity($ejecutar);

        return $this->render('ejecutar/show.html.twig', array(
            'ejecutar' => $ejecutar,
            'changes' => $changes,
        ));
    }

    /**
     * Displays a form to edit an existing Ejecutar entity.
     *
     */
    public function editAction(Request $request, Ejecutar $ejecutar)
    {
        $this->get('Services')->setMenuItem('Ejecutar');
        $editForm = $this->createForm('Wbc\AdministratorBundle\Form\EjecutarType', $ejecutar);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ejecutar);
            $em->flush();

            $this->get('Services')->addFlash('success', $this->get('translator')->trans('Ejecutar updated!'));

            return $this->redirectToRoute('ejecutar_index');
        }

        return $this->render('ejecutar/edit.html.twig', array(
            'ejecutar' => $ejecutar,
            'form'        => $editForm->createView()
        ));
    }

    /**
     * Deletes a Ejecutar entity.
     *
     */
    public function deleteAction(Request $request, Ejecutar $ejecutar)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($ejecutar);
        $em->flush();

        $this->get('Services')->setFlash('danger', $this->get('translator')->trans('Ejecutar deleted!'));

        return $this->redirectToRoute('ejecutar_index');
    }

}
