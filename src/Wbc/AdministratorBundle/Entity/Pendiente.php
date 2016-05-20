<?php

namespace Wbc\AdministratorBundle\Entity;

/**
 * Pendiente
 */
class Pendiente
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var bool
     */
    private $completado;

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
     * Set completado
     *
     * @param boolean $completado
     *
     * @return Pendiente
     */
    public function setCompletado($completado)
    {
        $this->completado = $completado;

        return $this;
    }

    /**
     * Get completado
     *
     * @return bool
     */
    public function getCompletado()
    {
        return $this->completado;
    }

    /**
     * Set creacion
     *
     * @param \DateTime $creacion
     *
     * @return Pendiente
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
     * @var \Wbc\AdministratorBundle\Entity\Ejecutar
     */
    private $ejecutar;


    /**
     * Set ejecutar
     *
     * @param \Wbc\AdministratorBundle\Entity\Ejecutar $ejecutar
     *
     * @return Pendiente
     */
    public function setEjecutar(\Wbc\AdministratorBundle\Entity\Ejecutar $ejecutar)
    {
        $this->ejecutar = $ejecutar;

        return $this;
    }

    /**
     * Get ejecutar
     *
     * @return \Wbc\AdministratorBundle\Entity\Ejecutar
     */
    public function getEjecutar()
    {
        return $this->ejecutar;
    }
}
