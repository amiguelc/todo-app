<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodosRepository")
 */
class Todos
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="bigint")
     */
    private $id_todo;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 1,
     *      max = 254,
     *      minMessage = "Your todo name must be at least {{ limit }} characters long",
     *      maxMessage = "Your todo name cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="text", nullable=false)
     */
    private $text;

    /**
   * @Assert\Length(
     *      max = 2540,
     *      maxMessage = "Your todo details cannot be longer than {{ limit }} characters"
     * )
     * @ORM\Column(type="text", nullable=true)
     */
    private $details;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_finish;

    /**
     * @ORM\Column(type="bigint")
     */
    private $creator;

    /**
     * @ORM\Column(type="smallint", options={"default" : 0})
     */
    private $state;

    public function __construct()
    {
        $this->date_creation=new \DateTime("now");
    }


    public function getId()
    {
        return $this->id_todo;
    }
    public function getIdTodo()
    {
        return $this->id_todo;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }
    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTime $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getDateStart(): ?\DateTime
    {
        return $this->date_start;
    }

    public function setDateStart(?\DateTime $date_start): self
    {
        $this->date_start = $date_start;

        return $this;
    }

    public function getDateFinish(): ?\DateTime
    {
        return $this->date_finish;
    }

    public function setDateFinish(?\DateTime $date_finish): self
    {
        $this->date_finish = $date_finish;

        return $this;
    }

    public function getCreator(): ?int
    {
        return $this->creator;
    }

    public function setCreator(int $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getState(): ?int
    {
        return $this->state;
    }

    public function setState(int $state): self
    {
        $this->state = $state;

        return $this;
    }
}
