<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
/**
 * @ORM\HasLifecycleCallbacks()
 *
 * @ApiResource(
 *
 *     mercure=true,
 *
 *     collectionOperations={
 *          "get",
 *          "post"
 *     },
 *     itemOperations={
 *          "get",
 *          "put",
 *          "patch"={"security"="is_granted('ROLE_USER')"},
 *          "delete"={"security"="is_granted('ROLE_MODERATOR')"}
 *     }
 * )
 * @ApiFilter (
 *     OrderFilter::class, properties={"created_at"}
 *     )
 * @ApiFilter (
 *     SearchFilter::class, properties={"author", "addressee", "user.id"}
 *     )
 * @ApiFilter (
 *     OrderFilter::class, properties={"created_at"}
 *     )
 *
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Discussion::class, inversedBy="messages")
     */
    private $conversation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     */
    private $author;


    public function __toString()
    {
        $contentPositionNumber = stripos(' ', $this->getContent());
        $content = substr($this->getContent(), 0, $contentPositionNumber );
        return 'Message : '. $content;
    }
    public function getId(): ?int
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
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPersist()
    {
        $this->created_at = new \DateTime();
        $this->updated_at = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->updated_at = new \DateTime();
    }


    public function getConversation(): ?Discussion
    {
        return $this->conversation;
    }

    public function setConversation(?Discussion $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }
}
