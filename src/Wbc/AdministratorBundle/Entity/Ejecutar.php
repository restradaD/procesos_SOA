<?php

namespace Wbc\AdministratorBundle\Entity;

/**
 * Ejecutar
 */
class Ejecutar
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nombreProceso;

    /**
     * @var int
     */
    private $tiempo;

    /**
     * @var int
     */
    private $memoria;

    /**
     * @var bool
     */
    private $terminado;

    /**
     * @var \DateTime
     */
    private $creacion;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombreProceso
     *
     * @param string $nombreProceso
     *
     * @return Ejecutar
     */
    public function setNombreProceso($nombreProceso)
    {
        $this->nombreProceso = $nombreProceso;

        return $this;
    }

    /**
     * Get nombreProceso
     *
     * @return string
     */
    public function getNombreProceso()
    {
        return $this->nombreProceso;
    }

    /**
     * Set tiempo
     *
     * @param integer $tiempo
     *
     * @return Ejecutar
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    /**
     * Get tiempo
     *
     * @return int
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set memoria
     *
     * @param integer $memoria
     *
     * @return Ejecutar
     */
    public function setMemoria($memoria)
    {
        $this->memoria = $memoria;

        return $this;
    }

    /**
     * Get memoria
     *
     * @return int
     */
    public function getMemoria()
    {
        return $this->memoria;
    }

    /**
     * Set terminado
     *
     * @param boolean $terminado
     *
     * @return Ejecutar
     */
    public function setTerminado($terminado)
    {
        $this->terminado = $terminado;

        return $this;
    }

    /**
     * Get terminado
     *
     * @return bool
     */
    public function getTerminado()
    {
        return $this->terminado;
    }

    /**
     * Set creacion
     *
     * @param \DateTime $creacion
     *
     * @return Ejecutar
     */
    public function setCreacion($creacion)
    {
        $this->creacion = $creacion;

        return $this;
    }

    /**
     * Get creacion
     *
     * @return \DateTime
     */
    public function getCreacion()
    {
        return $this->creacion;
    }
    /**
     * @var \Wbc\AdministratorBundle\Entity\Configuracion
     */
    private $configuracion;

    /**
     * @var \Wbc\AdministratorBundle\Entity\PalabraReservada
     */
    private $palabra_reservada;

    /**
     * @var \Wbc\AdministratorBundle\Entity\User
     */
    private $user;


    /**
     * Set configuracion
     *
     * @param \Wbc\AdministratorBundle\Entity\Configuracion $configuracion
     *
     * @return Ejecutar
     */
    public function setConfiguracion(\Wbc\AdministratorBundle\Entity\Configuracion $configuracion)
    {
        $this->configuracion = $configuracion;

        return $this;
    }

    /**
     * Get configuracion
     *
     * @return \Wbc\AdministratorBundle\Entity\Configuracion
     */
    public function getConfiguracion()
    {
        return $this->configuracion;
    }

    /**
     * Set palabraReservada
     *
     * @param \Wbc\AdministratorBundle\Entity\PalabraReservada $palabraReservada
     *
     * @return Ejecutar
     */
    public function setPalabraReservada(\Wbc\AdministratorBundle\Entity\PalabraReservada $palabraReservada)
    {
        $this->palabra_reservada = $palabraReservada;

        return $this;
    }

    /**
     * Get palabraReservada
     *
     * @return \Wbc\AdministratorBundle\Entity\PalabraReservada
     */
    public function getPalabraReservada()
    {
        return $this->palabra_reservada;
    }

    /**
     * Set user
     *
     * @param \Wbc\AdministratorBundle\Entity\User $user
     *
     * @return Ejecutar
     */
    public function setUser(\Wbc\AdministratorBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Wbc\AdministratorBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pendiente;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pendiente = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pendiente
     *
     * @param \Wbc\AdministratorBundle\Entity\Pendiente $pendiente
     *
     * @return Ejecutar
     */
    public function addPendiente(\Wbc\AdministratorBundle\Entity\Pendiente $pendiente)
    {
        $this->pendiente[] = $pendiente;

        return $this;
    }

    /**
     * Remove pendiente
     *
     * @param \Wbc\AdministratorBundle\Entity\Pendiente $pendiente
     */
    public function removePendiente(\Wbc\AdministratorBundle\Entity\Pendiente $pendiente)
    {
        $this->pendiente->removeElement($pendiente);
    }

    /**
     * Get pendiente
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPendiente()
    {
        return $this->pendiente;
    }
}
