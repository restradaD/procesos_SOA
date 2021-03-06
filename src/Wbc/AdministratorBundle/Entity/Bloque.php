<?php

namespace Wbc\AdministratorBundle\Entity;

/**
 * Bloque
 */
class Bloque
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $espacio;

    /**
     * @var \DateTime
     */
    private $creacion;
    
    
    public function __toString() {
        return $this->espacio . "id " . $this->id;
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
     * Set espacio
     *
     * @param integer $espacio
     *
     * @return Bloque
     */
    public function setEspacio($espacio)
    {
        $this->espacio = $espacio;

        return $this;
    }

    /**
     * Get espacio
     *
     * @return int
     */
    public function getEspacio()
    {
        return $this->espacio;
    }

    /**
     * Set creacion
     *
     * @param \DateTime $creacion
     *
     * @return Bloque
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
     * @return Bloque
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
    /**
     * @var \Wbc\AdministratorBundle\Entity\Configuracion
     */
    private $configuracion;


    /**
     * Set configuracion
     *
     * @param \Wbc\AdministratorBundle\Entity\Configuracion $configuracion
     *
     * @return Bloque
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
     * @var integer
     */
    private $usado;

    /**
     * @var integer
     */
    private $disponible;


    /**
     * Set usado
     *
     * @param integer $usado
     *
     * @return Bloque
     */
    public function setUsado($usado)
    {
        $this->usado = $usado;

        return $this;
    }

    /**
     * Get usado
     *
     * @return integer
     */
    public function getUsado()
    {
        return $this->usado;
    }

    /**
     * Set disponible
     *
     * @param integer $disponible
     *
     * @return Bloque
     */
    public function setDisponible($disponible)
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Get disponible
     *
     * @return integer
     */
    public function getDisponible()
    {
        return $this->disponible;
    }
}
