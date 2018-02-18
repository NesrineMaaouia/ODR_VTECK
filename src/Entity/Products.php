<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductsRepository")
 */
class Products
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *@ORM\Column(name="productean", type="string", length=20, nullable=false)
     */
    private $productean;
    /**
     * @var string
     *@ORM\Column(name="productname", type="string", length=255, nullable=false)
     */
    private $productname;

    /**
     * @var int
     *@ORM\Column(name="stock", type="integer", length=4, nullable=false)
     */
    private $stock;

    /**
     * @var string
     *@ORM\Column(name="image", type="string", length=255, nullable=true)
     */

    private $image;

    /**
     * @var string
     *@ORM\Column(name="numeoreference", type="string", length=10, nullable=false, unique=true)
     */
    private $numeoreference;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="product")
     */
    private $participations;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     */
    public function setParticipations($participations)
    {
        $this->participations = $participations;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getProductean()
    {
        return $this->productean;
    }

    /**
     * @param string $productean
     */
    public function setProductean($productean)
    {
        $this->productean = $productean;
    }

    /**
     * @return string
     */
    public function getProductname()
    {
        return $this->productname;
    }

    /**
     * @param string $productname
     */
    public function setProductname($productname)
    {
        $this->productname = $productname;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock($stock)
    {
        $this->stock = $stock;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getNumeoreference()
    {
        return $this->numeoreference;
    }

    /**
     * @param string $numeoreference
     */
    public function setNumeoreference($numeoreference)
    {
        $this->numeoreference = $numeoreference;
    }





}
