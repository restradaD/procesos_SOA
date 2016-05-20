<?php

namespace Wbc\AdministratorBundle\Entity;

/**
 * Configuracion
 */
class Configuracion
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $proceso;

    /**
     * @var int
     */
    private $memoria;

    /**
     * @var int
     */
    private $cantidadBloque;

    /**
     * @var bool
     */
    private $activo;

    /**
     * @var \DateTime
     */
    private $creacion;

    /**
     * @var \DateTime
     */
    private $fechaExpira;

    /**
     * @var int
     */
    private $expira;


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
     * Set proceso
     *
     * @param integer $proceso
     *
     * @return Configuracion
     */
    public function setProceso($proceso)
    {
        $this->proceso = $proceso;

        return $this;
    }

    /**
     * Get proceso
     *
     * @return int
     */
    public function getProceso()
    {
        return $this->proceso;
    }

    /**
     * Set memoria
     *
     * @param integer $memoria
     *
     * @return Configuracion
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
     * Set cantidadBloque
     *
     * @param integer $cantidadBloque
     *
     * @return Configuracion
     */
    public function setCantidadBloque($cantidadBloque)
    {
        $this->cantidadBloque = $cantidadBloque;

        return $this;
    }

    /**
     * Get cantidadBloque
     *
     * @return int
     */
    public function getCantidadBloque()
    {
        return $this->cantidadBloque;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Configuracion
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return bool
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set creacion
     *
     * @param \DateTime $creacion
     *
     * @return Configuracion
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
     * Set fechaExpira
     *
     * @param \DateTime $fechaExpira
     *
     * @return Configuracion
     */
    public function setFechaExpira($fechaExpira)
    {
        $this->fechaExpira = $fechaExpira;

        return $this;
    }

    /**
     * Get fechaExpira
     *
     * @return \DateTime
     */
    public function getFechaExpira()
    {
        return $this->fechaExpira;
    }

    /**
     * Set expira
     *
     * @param integer $expira
     *
     * @return Configuracion
     */
    public function setExpira($expira)
    {
        $this->expira = $expira;

        return $this;
    }

    /**
     * Get expira
     *
     * @return int
     */
    public function getExpira()
    {
        return $this->expira;
    }
    /**
     * @var \Wbc\AdministratorBundle\Entity\Politica
     */
    private $politica;


    /**
     * Set politica
     *
     * @param \Wbc\AdministratorBundle\Entity\Politica $politica
     *
     * @return Configuracion
     */
    public function setPolitica(\Wbc\AdministratorBundle\Entity\Politica $politica)
    {
        $this->politica = $politica;

        return $this;
    }

    /**
     * Get politica
     *
     * @return \Wbc\AdministratorBundle\Entity\Politica
     */
    public function getPolitica()
    {
        return $this->politica;
    }
}
