<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 */
class Users implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $idUser;

    /**
     * @Assert\NotNull()
     * @Assert\Length(
     *      min = 6,
     *      max = 254,
     *      minMessage = "Your first name must be at least {{ limit }} characters long",
     *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
     * )
     *  * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $email;

    /**
     * @Assert\Length(
     *   min = 6,
     *   max = 50,
     *   minMessage = "Your password must be at least {{ limit }} characters long",
     *   maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $nickname;

    /**
     * @Assert\Length(
     *   max = 20,
     *   maxMessage = "Your name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

     /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="token_verification_email", type="string", length=16)
     */
    private $TokenVerificationEmail;

    /**
     * @ORM\Column(name="email_verified", type="boolean")
     */
    private $emailVerified = false;
    
    /* Config for showing TODOs*/

    /**
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @ORM\Column(name="config_numrows", type="integer")
     */
    private $configNumRows = 20;

    /**
     *  @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @ORM\Column(name="config_showall", type="smallint")
     */
    private $configShowAll = 0;

    /**
     * 
     * @ORM\Column(name="config_showdatestart", type="boolean")
     */
    private $configShowDateStart = false;

    /**
     * @ORM\Column(name="config_showdatefinish", type="boolean")
     */
    private $configShowDateFinish = false;

    /**
     * @Assert\Choice({"en", "es_ES"})
     * @ORM\Column(type="string", length=20)
     */
    private $locale = "en";

    public function __construct()
    {
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid('', true));
    }

    public function getId()
    {
        return $this->idUser;
    }

    public function getIdUser(): ?string
    {
        return $this->idUser;
    }

    public function setIdUser(string $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }
/*
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
*/
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTokenVerificationEmail(): ?string
    {
        return $this->TokenVerificationEmail;
    }

    public function setTokenVerificationEmail(string $TokenVerificationEmail): self
    {
        $this->TokenVerificationEmail = $TokenVerificationEmail;

        return $this;
    }

    public function getemailVerified(): ?bool
    {
        return $this->emailVerified;
    }

    public function setemailVerified(string $emailVerified): self
    {
        $this->emailVerified = $emailVerified;

        return $this;
    }

    public function getConfigNumRows(): ?int
    {
        return $this->configNumRows;
    }

    public function setConfigNumRows(int $configNumRows): self
    {
        $this->configNumRows = $configNumRows;

        return $this;
    }

    public function getConfigShowAll(): ?int
    {
        return $this->configShowAll;
    }

    public function setConfigShowAll(int $configShowAll): self
    {
        $this->configShowAll = $configShowAll;

        return $this;
    }
    
    public function getConfigShowDateStart(): ?bool
    {
        return $this->configShowDateStart;
    }

    public function setConfigShowDateStart(string $configShowDateStart): self
    {
        if ($configShowDateStart=="false") { $configShowDateStart=false; } //to prevent string "false" being true
        $this->configShowDateStart = (bool) $configShowDateStart;

        return $this;
    }

    public function getConfigShowDateFinish(): ?bool
    {
        return $this->configShowDateFinish;
    }

    public function setConfigShowDateFinish(string $configShowDateFinish): self
    {
        if ($configShowDateFinish=="false") { $configShowDateFinish=false; } //to prevent string "false" being true
        $this->configShowDateFinish = (bool) $configShowDateFinish;

        return $this;
    }
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->idUser,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->idUser,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized, ['allowed_classes' => false]);
    }

}
