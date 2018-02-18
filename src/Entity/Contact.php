<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as ODRAssert;

/**
 * Contact.
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 * @ODRAssert\EmailParticipationExist()
 */
class Contact
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
     * @ORM\Column(name="civility", type="boolean")
     * @Assert\NotNull(message = "La civilité est obligatoire.")
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
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     * @Assert\NotBlank(message = "L'adresse email est obligatoire.")
     * @Assert\Email(
     *     message = "L'email est incorrect."
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255)
     *
     * @Assert\NotBlank(message = "La référence est obligatoire.")
     * @Assert\Length(
     *      max = 255,
     *      exactMessage = "Le nombre de caractères est trop important."
     * )
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=255)
     * @Assert\NotBlank(message = "L'objet est obligatoire.")
     * @Assert\Length(
     *      max = 255,
     *      exactMessage = "Le nombre de caractères est trop important."
     * )
     */
    private $object;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     * @Assert\NotBlank(message = "Le message est obligatoire.")
     */
    private $message;

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
     * Set civility.
     *
     * @param bool $civility
     *
     * @return Contact
     */
    public function setCivility($civility)
    {
        $this->civility = $civility;

        return $this;
    }

    /**
     * Get civility.
     *
     * @return bool
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * Set nom.
     *
     * @param string $lastname
     *
     * @return Contact
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
     * Set prenom.
     *
     * @param string $firstname
     *
     * @return Contact
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
     * @return Contact
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
     * Set reference.
     *
     * @param string $reference
     *
     * @return Contact
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference.
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set objet.
     *
     * @param string $object
     *
     * @return Contact
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get objet.
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return Contact
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
