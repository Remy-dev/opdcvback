<?php

namespace App\Entity;

use App\Message\CommentMessage;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\EntityListeners;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
/**
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource (
 *     collectionOperations={
 *          "get",
 *          "post"={"security"="is_granted('ROLE_USER')"}
 *     },
 *     itemOperations={
 *          "get"={"security"="is_granted('IS_AUTHENTICATED_ANONYMOUSLY')"},
 *          "put"={"security"="is_granted('ROLE_USER')"},
 *          "patch"={"security"="is_granted('ROLE_USER')"},
 *          "delete"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"post"}},
 *
 * )
 * @ApiFilter (
 *     SearchFilter::class, properties={"author", "addressee"}
 *     )
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 *
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"read", "post"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups ({"read", "post"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups ({"read", "post"})
     */
    private $content;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups ({"read", "post"})
     */
    private $rating;

    /**
     * @ORM\Column(type="datetime")
     * @Groups ({"read", "post"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     * @Groups ({"read", "post"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentsAuthor", cascade={"persist"})
     * @Groups ("read")
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commentsAddressee", cascade={"persist"})
     * @Groups ("read")
     */
    private $addressee;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "submitted"})
     */
    private $state = 'submitted';

    public function __toString()
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

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

    public function getAddressee(): ?User
    {
        return $this->addressee;
    }

    public function setAddressee(?User $addressee): self
    {
        $this->addressee = $addressee;

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

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function show(MessageBusInterface $bus)
    {

        $bus->dispatch(new CommentMessage($this->getId()));
    }
}
