<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
use App\Validator\Constraints as AppAssert;

/**
 * Participation
 *
 * @ORM\Table(name="participation")
 * @ORM\Entity(repositoryClass="App\Repository\ParticipationRepository")
 */
class Participation
{

    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Serializer\Groups({"global"})
     * @Serializer\SerializedName("participation_id")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="num", type="string", length=13, nullable=true, unique=true)
     */
    private $num;


    /**
     * @ORM\ManyToOne(targetEntity="Enseigne", inversedBy="participations")
     * @ORM\JoinColumn(name="enseigne_id", referencedColumnName="id")
     */
    private $enseigne;


    /**
     * @var string
     *
     * @ORM\Column(name="ean_product", type="string", length=13)
     * @Assert\NotBlank(message = "Le code-barres est obligatoire.")
     * @AppAssert\Checklist(
     *     message = "Le code-barres n'est pas valide.",
     *     entity="App\Entity\Whitelist",
     *     field="item",
     *     isWhiteList="true"
     * )
     */
    private $eanProduct;

    /**
     * @var string
     *
     * @ORM\Column(name="ean_product2", type="string", length=13)
     * @Assert\NotBlank(message = "Le code-barres est obligatoire.")
     * @AppAssert\Checklist(
     *     message = "Le code-barres n'est pas valide.",
     *     entity="App\Entity\Whitelist",
     *     field="item",
     *     isWhiteList="true"
     * )
     */
    private $eanProduct2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_purchased", type="date", nullable=true)
     * @Assert\NotBlank(message = "La date d'achat est obligatoire.")
     * @Assert\Date(message="Veuillez saisir une date valide.")
     */
    private $datePurchased;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="participations", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Products", inversedBy="participations", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private $product;

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return mixed
     */
    public function getEnseigne()
    {
        return $this->enseigne;
    }

    /**
     * @param mixed $enseigne
     */
    public function setEnseigne($enseigne)
    {
        $this->enseigne = $enseigne;
    }


    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
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
     * Set num
     *
     * @param string $num
     *
     * @return Participation
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }


    /**
     * @return string
     */
    public function getEanProduct()
    {
        return $this->eanProduct;
    }

    /**
     * @param string $eanProduct
     *
     * @return Participation
     */
    public function setEanProduct($eanProduct)
    {
        $this->eanProduct = $eanProduct;

        return $this;
    }

    /**
     * @return string
     */
    public function getEanProduct2()
    {
        return $this->eanProduct2;
    }

    /**
     * @param string $eanProduct2
     */
    public function setEanProduct2($eanProduct2)
    {
        $this->eanProduct2 = $eanProduct2;
    }

    /**
     * @return \DateTime
     */
    public function getDatePurchased()
    {
        return $this->datePurchased;
    }

    /**
     * @param \DateTime $datePurchased
     *
     * @return Participation
     */
    public function setDatePurchased($datePurchased)
    {
        $this->datePurchased = $datePurchased;

        return $this;
    }


    /**
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("date_participation")
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     * @Serializer\Groups({"global"})
     *
     * @return string
     */
    public function getParticipationDate()
    {
        return $this->createdAt;
    }


}
