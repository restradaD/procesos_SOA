<?php

namespace Wbc\AdministratorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Wbc\AdministratorBundle\Entity\Configuracion;
use Wbc\AdministratorBundle\Form\ConfiguracionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Wbc\AdministratorBundle\Entity\Bloque;
use Wbc\AdministratorBundle\Entity\Ejecutar;
use Wbc\AdministratorBundle\Form\EjecutarType;

/**
 * Configuracion controller.
 *
 */
class ConfiguracionController extends Controller {

    private $cantidad_procesos;
    private $politicaAjuste;
    private $memoria;
    private $numerodebloques;
    private $lastError;
    private $configuracionEntity;

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
    public function terminalAction(Request $request) {

        $configuracion = $this->traeUltimaConfiguracion();
        $bloques = "vacio";
        //validar despues que si esta activo o ya paso la fecha de expiracion

        if (!empty($configuracion)) {
            $ultimaConfiguracion = $configuracion;
            $bloques = $this->getBloquesByConfiguracion($ultimaConfiguracion->getId());
        }

        $ejecutar = new Ejecutar();
        $form = $this->createForm('Wbc\AdministratorBundle\Form\EjecutarType', $ejecutar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $ultimaConfig = $this->traeUltimaConfiguracion();
            if (empty($ultimaConfig)) {
                $this->get('Services')->setFlash('danger', $this->get('translator')->trans('No existe Configuracion en vigencia'));
                return $this->redirectToRoute('configuracion_terminal');
            }

            if (!$this->buscaPolitica($form->getData(), $ultimaConfig, $ejecutar)) {
                $this->get('Services')->setFlash('danger', $this->get('translator')->trans($this->lastError));
                return $this->redirectToRoute('configuracion_terminal');
            }

            $this->get('Services')->addFlash('success', $this->get('translator')->trans('Se ha ejecutado el comando correctamente!'));

            return $this->redirectToRoute('configuracion_terminal');
        }

        $this->get('Services')->setMenuItem('Terminal');
        return $this->render('configuracion/terminal.html.twig', array(
                    'configuracion' => $ultimaConfiguracion,
                    'bloques' => $bloques,
                    'form' => $form->createView(),
        ));
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

    /**
     * Guarda la data que contiene el archivo de configuracion
     * @param Request $request
     * @return JsonResponse
     */
    public function agregaConfiguracionAction(Request $request) {

        try {
            $data = $this->getFileData($request->getContent());

            if (!$data) {
                $code = 500;
                $response = array('msg' => 'Internal Error', 'code' => $code, 'recordset' => null, 'error' => $this->lastError);
            } else {
                $code = 200;
                $response = array('msg' => "Se ha guardado la configuracion inicial correctamente", 'code' => $code, 'recordset' => $this->configuracionEntity->getId(), 'error' => null);
            }
        } catch (Exception $ex) {
            $code = 404;
            $response = array('msg' => 'Not found', 'code' => $code, 'recordset' => null, 'error' => $ex->getMessage());
        }

        return new JsonResponse($response, $code);
    }

    /**
     * trae la informacion del archivo y la valida para guardar
     * @param array $data
     * @return boolean
     */
    private function getFileData($data) {
        $rute = '/home/rene/Documentos/proyecto soa/requerimientos/proyectoCONFIG.config';


        if (empty($rute) || !file_exists($rute)) {
            $this->lastError = 'No such file or directory';
            return false;
        }

        $file = file_get_contents($rute, true);

        $oui_split = preg_split("/[\s]+/", $file);

        if ($oui_split === false) {
            $this->lastError = "Se ha producido un error al tratar de sacar la información del archivo";
            return false;
        }

        return $this->saveDataConfiguration($oui_split);
    }

    /**
     * Evalua la data
     * @param array $oui_split
     * @return boolean
     */
    private function saveDataConfiguration($oui_split) {
        $bloques = $this->explodeParams($oui_split);

        if (count($bloques) !== intval($this->numerodebloques)) {
            $this->lastError = "El numero de bloques no coincide con la particion de estos";
            return false;
        }

        if (!$this->sumTotalBloques($bloques)) {
            return false;
        }

        if (intval($this->politicaAjuste) < 1 || intval($this->politicaAjuste) > 3) {
            $this->lastError = "La politica de ajuste no debe ser inferior a 1 ni superior a 3";
            return false;
        }

        $configuracion = $this->flushConfiguracion();

        if (!$configuracion) {
            return false;
        }

        if (count($bloques) > 0) {
            $this->flushBloques($bloques);
        }

        return true;
    }

    /**
     * Saca cada dato del archivo para guardar
     * @param array $oui_split
     * @return array
     */
    private function explodeParams($oui_split) {
        $bloques = array();

        foreach ($oui_split as $word) {
            $data = explode('=', $word);

            if ($data[0] === 'Procesos') {
                $this->cantidad_procesos = $data[1];  // cantidad_procesos  politicaAjuste memoria numerodebloques
                unset($word);
            } elseif ($data[0] === 'PoliticaAjuste') {
                $this->politicaAjuste = $data[1];
                unset($word);
            } elseif ($data[0] === 'Memoria') {
                $this->memoria = $data[1];
                unset($word);
            } elseif ($data[0] === 'Numerodebloques') {
                $this->numerodebloques = $data[1];
                unset($word);
            } else {

                if (!empty($data[0])) {

//                    $value_valor = $data[1];
//                    $value_name = $data[0];
                    $bloques[] = array('nombre' => $data[0], 'valor' => $data[1]);
                }
            }
        }

        return $bloques;
    }

    /**
     * Evalua si la suma de los bloques particionados coincide con la suma total
     * @param array $bloques
     * @return boolean
     */
    private function sumTotalBloques($bloques) {


        if (count($bloques) < 1) {
            return true;
        }

        $sum = 0;
        foreach ($bloques as $bloque) {
            $sum += intval($bloque["valor"]);
        }
        if (intval($sum) > intval($this->memoria)) {
            $this->lastError = "La memoria es inferior a la suma total de la memoria asignada a los bloqes. Memoria particionada en bloques: $sum. Memoria definida: " . $this->memoria;

            return false;
        }
        return true;
    }

    /**
     * Crea una nueva entidad de Configuracion
     * @return boolean
     */
    private function flushConfiguracion() {
        if (!$this->validateParams()) {
            return false;
        }

        try {

            $configuracion = new Configuracion();
            $now = new \DateTime('now');
            $em = $this->getDoctrine()->getManager();

            $politica = $em->getRepository('Wbc\AdministratorBundle\Entity\Politica')->findOneById($this->politicaAjuste);

            $configuracion->setProceso($this->cantidad_procesos);
            $configuracion->setMemoria($this->memoria);
            $configuracion->setMemoria($this->memoria);
            $configuracion->setActivo(1);
            $configuracion->setCantidadBloque($this->numerodebloques);
            $configuracion->setPolitica($politica);


            $configuracion->setCreacion($now);

            $em->persist($configuracion);

            $this->configuracionEntity = $configuracion;
            $em->flush();

            return true;
        } catch (Exception $ex) {
            $this->lastError = $ex->getMessage();
            return false;
        }
    }

    /**
     * Crea nueva entidad bloque
     * @param array $bloques
     * @return boolean
     */
    private function flushBloques($bloques) {
        $em = $this->getDoctrine()->getManager();

        foreach ($bloques as $bloque) {

            $bloqeEntity = new Bloque();
            $now = new \DateTime('now');

            $bloqeEntity->setConfiguracion($this->configuracionEntity);
            $bloqeEntity->setEspacio($bloque["valor"]);
            $bloqeEntity->setCreacion($now);
            $bloqeEntity->setDisponible(0);
            $bloqeEntity->setUsado(0);

            $em->persist($bloqeEntity);
        }
        $em->flush();

        return true;
    }

    /**
     * Valida que los parametros sean los correctos
     * @return boolean
     */
    private function validateParams() {
        if (empty($this->cantidad_procesos)) {
            $this->lastError = "Debe definir cual es la capacidad de procesos permitidos";
            return false;
        }
        if (empty($this->memoria)) {
            $this->lastError = "Debe definir cual es el tamaño de la memoria en Kylobytes";
            return false;
        }

        if (empty($this->politicaAjuste)) {
            $this->lastError = "Debe definir que politica de ajuste se va a utilizar";
            return false;
        }

        if (empty($this->numerodebloques)) {
            $this->lastError = "Debe definir cuantos bloques se crearan inicialmente";
            return false;
        }

        return true;
    }

    private function traeUltimaConfiguracion() {
        $em = $this->getDoctrine()->getManager();

        $configuraciones = $em->getRepository('WbcAdministratorBundle:Configuracion')->findAll();

        $total = count($configuraciones);

        if ($total >= 1) {
            $last = $total - 1;
            if ($configuraciones[$last]->getActivo()) {
                return $configuraciones[$last];
            }
        }
        return null;
    }

    /**
     * Trae los bloques por configuracion
     * @param int $configuracionId
     * @return array|string
     */
    public function getBloquesByConfiguracion($configuracionId) {

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery("SELECT b.id, b.espacio, b.creacion FROM Wbc\AdministratorBundle\Entity\Bloque b JOIN b.configuracion c WHERE c.id = :configuracionId ORDER BY b.id ASC");
        $query->setParameter('configuracionId', $configuracionId);
        $data = $query->getResult();

        if (empty($data)) {
            return "vacio";
        }

        $result = null;
        foreach ($data as $bloque) {
            $bloque["creacion"] = $bloque["creacion"]->format("d-m-Y H:i:s");
            $result[] = $bloque;
        }
//        dump($result);
//        dump($this->getColoresHex()); exit();
        return $result;
    }

    /**
     * Trae un color en exadecimal aleatoreamente
     * @return string
     */
    private function getColoresHex() {
        $colores = [
            "#F0F8FF", "#FAEBD7", "#00FFFF", "#0000FF", "#8A2BE2", "#A52A2A", "#7FFF00", "#D2691E", "#6495ED", "00FFFF",
            "#00008B", "#B8860B", "#A9A9A9", "#A9A9A9", "#006400", "#BDB76B", "#8B008B", "#FF8C00", "#8B0000", "#E9967A",
            "#00CED1", "#FF1493", "#1E90FF", "#228B22", "#FFD700", "#DAA520", "#008000", "#ADFF2F", "#CD5C5C", "#4B0082",
            "#7CFC00", "#ADD8E6", "#E0FFFF", "#90EE90", "#FFB6C1", "#FFA07A", "#87CEFA", "#00FF00", "#32CD32", "#0000CD",
            "#808000", "#FFA500", "#98FB98", "#CD853F", "#FF0000", "#FFFF00", "#00FF7F", "#F5DEB3", "#800080", "#3CB371"];

        $ramdom = rand(0, 49);

        $color = $colores[$ramdom];

        return $color;
    }

    //INICIO DE LAS POLITICAS DE AJUSTE

    /**
     * opera segun la politica de la configuracion
     * @param object $form
     * @param entity $configuracion
     * @param entity $ejecutar
     */
    private function buscaPolitica($form, $configuracion, $ejecutar) {


        $politica = $configuracion->getPolitica()->getId(); //$politica = $configuracion->getPolitica()->getNombre();


        if (intval($politica) === 1) { //Mejor ajuste
            return $this->politicaDeAjuste($form, $configuracion, $ejecutar, 'mejorAjuste');
        } elseif (intval($politica) === 2) { //Primer ajuste
            return $this->politicaDeAjuste($form, $configuracion, $ejecutar, 'primerAjuste');
        }
        //Peor ajuste
        return $this->politicaDeAjuste($form, $configuracion, $ejecutar, 'peorAjuste');
    }

    /**
     * Aplica la politica de Mejor ajuste en un bloque de memoria
     * @param object $form
     * @param entity $configuracion
     * @param entity $ejecutar
     * @return boolean
     */
    private function politicaDeAjuste($form, $configuracion, $ejecutar, $ajuste) {

        $em = $this->getDoctrine()->getManager();
        $bloques = $em->getRepository('Wbc\AdministratorBundle\Entity\Bloque')->findBy(array('configuracion' => $configuracion->getId()));

        if (empty($bloques)) {
            $this->lastError = 'La configuracion actual no tien bloques de memoria';
            return false;
        }

        $resta = 0;
        $pasan = "";
        $operaciones = "";

        foreach ($bloques as $bloque) {

            if ($bloque->getUsado() <= 0) {
                $resta = intval($bloque->getEspacio()) - intval($form->getMemoria());
            } else {
                $resta = intval($bloque->getDisponible()) - intval($form->getMemoria());
            }

            if ($resta >= 0) {
                $pasan[] = ['bloque' => $bloque, 'resultado' => $resta];
                $operaciones[] = [$resta];
            }
        }

        if (empty($pasan)) {
            $this->lastError = 'El proceso es demasiado grande no cambe en ningun bloque de memoria';
            return false;
        }

        $operacionClave = $this->getBloqueSegunPolitica($operaciones, $ajuste);

        return $this->guardaBloque($operacionClave[0], $pasan, $ejecutar, $form->getMemoria());
    }

    /**
     * Elige la operacion segun la politica de ajuste
     * @param array $operaciones
     * @param string $ajuste
     * @return int
     */
    private function getBloqueSegunPolitica($operaciones, $ajuste) {

        if ($ajuste === 'mejorAjuste') {
            $menor = min($operaciones);
            return $menor;
        } elseif ($ajuste === 'primerAjuste') {
            $primer = $operaciones[0];
            return $primer;
        }

        $mayor = max($operaciones);
        return $mayor;
    }

    /**
     * Trae la entitdad bloque segun la politica para guardar el registro
     * @param int $operacionClave
     * @param array $bloques
     * @param entity $ejecutar
     * @return boolean
     */
    private function guardaBloque($operacionClave, $bloques, $ejecutar, $ocupa) {

        $entidadBloque = "";
        foreach ($bloques as $bloque) {
            if (intval($bloque["resultado"]) === intval($operacionClave)) {
                $entidadBloque = $bloque["bloque"];
                break;
            }
        }

        $usado = intval($entidadBloque->getUsado()) + intval($ocupa);
        $disponible = intval($entidadBloque->getEspacio()) - $usado;

        return $this->flushEntityBloque($ejecutar, $entidadBloque, $usado, $disponible);
    }

    /**
     * Guarda el registro ingresado e la terminal y actualiza el bloque de memoria segun la politica
     * @param entity $ejecutar
     * @param entity $bloque
     * @param int $usado
     * @param int $disponible
     * @return boolean
     */
    private function flushEntityBloque($ejecutar, $bloque, $usado, $disponible) {
//        dump($usado);
//        dump($disponible);
//        dump($bloque);
//        dump($ejecutar);
//        exit();

        $now = new \DateTime('now');
        $ejecutar->setCreacion($now);
        $ejecutar->setBloque($bloque);
        $ejecutar->setUser($this->getUser());
        $ejecutar->setTerminado(false);

        //Actualiza campos en el bloque
        $bloque->setUsado($usado);
        $bloque->setDisponible($disponible);

        $em = $this->getDoctrine()->getManager();
        $em->persist($ejecutar);
        $em->persist($bloque);
        $em->flush();

        return true;
    }

    //FIN DE LAS POLITICAS DE AJUSTE
}
