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
}
