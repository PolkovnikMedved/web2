<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\Table(name="T_MESSAGE")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=150)
     */
    private $content;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, name = "last_modified_at")
     */
    private $lastModifiedAt;

    /**
     * @ORM\Column(type="integer", nullable=true, name = "fk_author")
     */
    private $author;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }


    public function getId()
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getLastModifiedAt(): ?\DateTimeInterface
    {
        return $this->lastModifiedAt;
    }

    public function setLastModifiedAt(?\DateTimeInterface $last_modified_at): self
    {
        $this->lastModifiedAt = $last_modified_at;

        return $this;
    }

    public function getFkAuthor(): ?int
    {
        return $this->author;
    }

    public function setFkAuthor(?int $fk_author): self
    {
        $this->author = $fk_author;

        return $this;
    }
}
