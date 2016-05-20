<?php

namespace Wbc\AdministratorBundle\Entity;

/**
 * PalabraReservada
 */
class PalabraReservada
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
     * @return PalabraReservada
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
     * @return PalabraReservada
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
     * @return PalabraReservada
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $ejecutar;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ejecutar = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ejecutar
     *
     * @param \Wbc\AdministratorBundle\Entity\Ejecutar $ejecutar
     *
     * @return PalabraReservada
     */
    public function addEjecutar(\Wbc\AdministratorBundle\Entity\Ejecutar $ejecutar)
    {
        $this->ejecutar[] = $ejecutar;

        return $this;
    }

    /**
     * Remove ejecutar
     *
     * @param \Wbc\AdministratorBundle\Entity\Ejecutar $ejecutar
     */
    public function removeEjecutar(\Wbc\AdministratorBundle\Entity\Ejecutar $ejecutar)
    {
        $this->ejecutar->removeElement($ejecutar);
    }

    /**
     * Get ejecutar
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEjecutar()
    {
        return $this->ejecutar;
    }
}
