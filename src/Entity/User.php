<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User.
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message="Vous avez déjà effectué une demande de remboursement.")
 * @ORM\HasLifecycleCallbacks()
 */
class User
{
    use TimestampableTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="civility", type="string", length=1)
     * @Assert\NotNull(message = "La civilité est obligatoire.")
     * @Serializer\Groups({"consumer"})
     * @Serializer\SerializedName("civility")
     */
    private $civility;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=80)
     * @Assert\NotBlank(message = "Le nom est obligatoire.")
     * @Assert\Length(
     *      max = 80,
     *      exactMessage = "Le nombre de caractères est trop important."
     * )
     * @Serializer\Groups({"consumer"})
     * @Serializer\SerializedName("lastname")
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=80)
     * @Assert\NotBlank(message = "Le prénom est obligatoire.")
     * @Assert\Length(
     *      max = 80,
     *      exactMessage = "Le nombre de caractères est trop important."
     * )
     * @Serializer\Groups({"consumer"})
     * @Serializer\SerializedName("firstname")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     * @Assert\NotBlank(message = "L'adresse email est obligatoire.")
     * @Assert\Email(
     *     message = "L'email est incorrect.",
     *     strict="true"
     * )
     * @Assert\Length(
     *      max = 50,
     *      exactMessage = "Le nombre de caractères est trop important."
     * )
     * @Serializer\Groups({"consumer"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=38)
     * @Assert\NotBlank(message="L'adresse est obligatoire.")
     * @Assert\Length(
     *      max = 38,
     *      exactMessage = "Le nombre de caractères est trop important."
     * )
     * @Serializer\Groups({"consumer"})
     * @Serializer\SerializedName("address_3")
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse2", type="string", length=38, nullable=true)
     * @Assert\Length(
     *      max = 38,
     *      exactMessage = "Le nombre de caractères est trop important."
     * )
     * @Serializer\Groups({"consumer"})
     * @Serializer\SerializedName("address_4")
     */
    private $address2;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=5)
     * @Assert\NotBlank(message="Le code postal est obligatoire.")
     * @Assert\Regex(pattern="[^[0-9]*$]" , message="le code postal ne semble pas être au bon format")
     *  @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      exactMessage = "Le code postal doit être à 5 chiffres"
     * )
     * @Serializer\Groups({"consumer"})
     * @Serializer\SerializedName("postal_code")
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=38)
     * @Assert\NotBlank(message="La ville est obligatoire.")
     * @Assert\Length(
     *      max = 38,
     *      exactMessage = "Le nombre de caractères est trop important."
     * )
     * @Serializer\Groups({"consumer"})
     * @Serializer\SerializedName("city")
     */
    private $ville;

    /**
     * @var string
     * @ORM\Column(name="token", type="string", length=32)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Participation", mappedBy="user")
     */
    private $participations;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->token = md5(random_bytes(20));
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set civilite.
     *
     * @param bool $civility
     *
     * @return User
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civilite.
     *
     * @return bool
     */
    public function getCivility()
    {
        return (int) $this->civility;
    }

    /**
     * Set nom.
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get nom.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->firstname.' '.$this->lastname;
    }

    /**
     * Set prenom.
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get prenom.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set codePostal.
     *
     * @param string $codePostal
     *
     * @return User
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal.
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set ville.
     *
     * @param string $ville
     *
     * @return User
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville.
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Add participation
     *
     * @param \App\Entity\Participation $participation
     *
     * @return User
     */
    public function addParticipation(\App\Entity\Participation $participation)
    {
        $this->participations[] = $participation;

        return $this;
    }

    /**
     * Remove participation
     *
     * @param \App\Entity\Participation $participation
     */
    public function removeParticipation(\App\Entity\Participation $participation)
    {
        $this->participations->removeElement($participation);
    }

    /**
     * Get participations
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParticipations()
    {
        return $this->participations;
    }
}
