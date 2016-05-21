<?php

namespace Wbc\AdministratorBundle\Entity;

/**
 * Politica
 */
class Politica
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $descripcion;
    
    
    public function __toString() {
        return $this->nombre;
    } 

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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Politica
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Politica
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Politica
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $configuracion;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->configuracion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add configuracion
     *
     * @param \Wbc\AdministratorBundle\Entity\Configuracion $configuracion
     *
     * @return Politica
     */
    public function addConfiguracion(\Wbc\AdministratorBundle\Entity\Configuracion $configuracion)
    {
        $this->configuracion[] = $configuracion;

        return $this;
    }

    /**
     * Remove configuracion
     *
     * @param \Wbc\AdministratorBundle\Entity\Configuracion $configuracion
     */
    public function removeConfiguracion(\Wbc\AdministratorBundle\Entity\Configuracion $configuracion)
    {
        $this->configuracion->removeElement($configuracion);
    }

    /**
     * Get configuracion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConfiguracion()
    {
        return $this->configuracion;
    }
}
