<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use App\Traits\Hydrator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Filter\RandomFilter;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *
 *     collectionOperations={
 *          "get",
 *          "post"
 *     },
 *     itemOperations={
 *          "get",
 *          "put"={"security"="is_granted('ROLE_USER')"},
 *          "patch"={"security"="is_granted('ROLE_USER')"},
 *          "delete"={"security"="is_granted('ROLE_USER')"},
 *     },
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}},
 *
 * )

 * @ApiFilter (SearchFilter::class, properties={"tags.name", "companyPro": "partial" })
 * @ApiFilter (BooleanFilter::class, properties={"producer"})
 * @ApiFilter (OrderFilter::class, properties={"id": "ASC"})
 * @ApiFilter(BooleanFilter::class, properties={"producer"})
 * @ApiFilter(RandomFilter::class, properties={"random"})
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    use Hydrator;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"write","read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"write", "read"})
     *
     */
    private string $username;


    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"write", "read"})
     * @Assert\NotBlank
     */
    private string $password;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     * @Groups ({"write", "read"})
     *
     */
    private ?string $firstname;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"write", "read"})
     * @Assert\Email
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $phone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups ({"write", "read"})
     *
     */
    private ?bool $producer;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $image;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private ?\DateTimeInterface $updated_at;

    /**
     * @ORM\Column(type="datetime")
     *
     */
    private ?\DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $emailPro;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $phonePro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $websitePro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $imagePro;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $descriptionPro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $siretPro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $companyPro;

    /**
     * @ORM\OneToMany(targetEntity=Address::class, mappedBy="user" , orphanRemoval=true)
     * @Groups ("read")
     */
    private  $addresses;

    /**
     * @ORM\ManyToMany(targetEntity=Itinerary::class, mappedBy="user", orphanRemoval=true)
     */
    private  $itineraries;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     */
    private  $commentsAuthor;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="addressee", orphanRemoval=true)
     */
    private  $commentsAddressee;


    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="user", orphanRemoval=true)
     */
    private  $products;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="users", orphanRemoval=true)
     * @Groups("read")
     */
    private  $tags;

    /**
     * @ORM\ManyToOne(targetEntity=Role::class, inversedBy="users")
     * @Groups("read")
     * @ApiSubresource(maxDepth=1)
     */
    private ?Role $role;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups ({"write", "read"})
     */
    private ?string $description;

    /**
     * @ORM\ManyToMany(targetEntity=Discussion::class, inversedBy="users")
     *
     */
    private  $discussions;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="author")
     */
    private  $messages;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $active = false;


    public function __construct($datas = [])
    {
        $this->addresses = new ArrayCollection();
        $this->itineraries = new ArrayCollection();
        $this->commentsAuthor = new ArrayCollection();
        $this->commentsAddressee = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->discussions = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->hydrate($datas);
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function getRoles()
    {
        return  array($this->getRole()->getRoleName());

    }


    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function hashPassword(UserPasswordEncoderInterface $encoder)
    {
        $encoded = $encoder->encodePassword($this, $this->password);
        $this->setPassword($encoded);
    }
    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }
    public function isProducer(): bool
    {
        return $this->producer;
    }

    public function getProducer(): ?bool
    {
        return $this->producer;
    }

    public function setProducer(?bool $producer): self
    {
        $this->producer = $producer;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getEmailPro(): ?string
    {
        return $this->emailPro;
    }

    public function setEmailPro(?string $emailPro): self
    {
        $this->emailPro = $emailPro;

        return $this;
    }

    public function getPhonePro(): ?string
    {
        return $this->phonePro;
    }

    public function setPhonePro(?string $phonePro): self
    {
        $this->phonePro = $phonePro;

        return $this;
    }

    public function getWebsitePro(): ?string
    {
        return $this->websitePro;
    }

    public function setWebsitePro(?string $websitePro): self
    {
        $this->websitePro = $websitePro;

        return $this;
    }

    public function getImagePro(): ?string
    {
        return $this->imagePro;
    }

    public function setImagePro(?string $imagePro): self
    {
        $this->imagePro = $imagePro;

        return $this;
    }

    public function getDescriptionPro(): ?string
    {
        return $this->descriptionPro;
    }

    public function setDescriptionPro(?string $descriptionPro): self
    {
        $this->descriptionPro = $descriptionPro;

        return $this;
    }

    public function getSiretPro(): ?string
    {
        return $this->siretPro;
    }

    public function setSiretPro(?string $siretPro): self
    {
        $this->siretPro = $siretPro;

        return $this;
    }

    public function getCompanyPro(): ?string
    {
        return $this->companyPro;
    }

    public function setCompanyPro(?string $companyPro): self
    {
        $this->companyPro = $companyPro;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses[] = $address;
            $address->setUser($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
            // set the owning side to null (unless already changed)
            if ($address->getUser() === $this) {
                $address->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Itinerary[]
     */
    public function getItineraries(): Collection
    {
        return $this->itineraries;
    }

    public function addItinerary(Itinerary $itinerary): self
    {
        if (!$this->itineraries->contains($itinerary)) {
            $this->itineraries[] = $itinerary;
            $itinerary->addUser($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): self
    {
        if ($this->itineraries->contains($itinerary)) {
            $this->itineraries->removeElement($itinerary);
            $itinerary->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getCommentsAuthor(): Collection
    {
        return $this->commentsAuthor;
    }

    public function addCommentsAuthor(Comment $commentsAuthor): self
    {
        if (!$this->commentsAuthor->contains($commentsAuthor)) {
            $this->commentsAuthor[] = $commentsAuthor;
            $commentsAuthor->setAuthor($this);
        }

        return $this;
    }

    public function removeCommentsAuthor(Comment $commentsAuthor): self
    {
        if ($this->commentsAuthor->contains($commentsAuthor)) {
            $this->commentsAuthor->removeElement($commentsAuthor);
            // set the owning side to null (unless already changed)
            if ($commentsAuthor->getAuthor() === $this) {
                $commentsAuthor->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getCommentsAddressee(): Collection
    {
        return $this->commentsAddressee;
    }

    public function addCommentsAddressee(Comment $commentsAddressee): self
    {
        if (!$this->commentsAddressee->contains($commentsAddressee)) {
            $this->commentsAddressee[] = $commentsAddressee;
            $commentsAddressee->setAddressee($this);
        }

        return $this;
    }

    public function removeCommentsAddressee(Comment $commentsAddressee): self
    {
        if ($this->commentsAddressee->contains($commentsAddressee)) {
            $this->commentsAddressee->removeElement($commentsAddressee);
            // set the owning side to null (unless already changed)
            if ($commentsAddressee->getAddressee() === $this) {
                $commentsAddressee->setAddressee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addUser($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeUser($this);
        }

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

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Discussion[]
     */
    public function getDiscussions(): Collection
    {
        return $this->discussions;
    }

    public function addDiscussion(Discussion $discussion): self
    {
        if (!$this->discussions->contains($discussion)) {
            $this->discussions[] = $discussion;
        }

        return $this;
    }

    public function removeDiscussion(Discussion $discussion): self
    {
        if ($this->discussions->contains($discussion)) {
            $this->discussions->removeElement($discussion);
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }


}
