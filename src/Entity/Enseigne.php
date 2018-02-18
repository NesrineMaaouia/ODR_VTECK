<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Enseigne.
 *
 * @ORM\Table(name="enseigne")
 * @ORM\Entity(repositoryClass="App\Repository\EnseigneRepository")
 *
 */
class Enseigne
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="enseignename", type="string", length=255)
     *
     */

    private $enseignename;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="enseigne")
     */
    private $participations;

    /**
     * @return string
     */
    public function getEnseignename()
    {
        return $this->enseignename;
    }

    /**
     * @param string $enseignename
     * @return Enseigne
     */
    public function setEnseignename($enseignename)
    {
        $this->enseignename = $enseignename;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParticipations()
    {
        return $this->participations;
    }

    /**
     * @param mixed $participations
     * @return Enseigne
     */
    public function setParticipations($participations)
    {
        $this->participations = $participations;
        return $this;
    }

    /**
     * Enseigne constructor.
     * @param $id
     */
    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



}
