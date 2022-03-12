<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255), Groups('product')]
    private $name;

    #[ORM\Column(type: 'integer'), Groups('product')]
    private $price;

    #[ORM\Column(type: 'string', length: 255), Groups('product')]
    private $imagePath;

    #[ORM\ManyToOne(targetEntity: Categorie::class, inversedBy: 'products',fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false), Groups('product')]
    private $categorie;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CommandeLine::class)]
    private $commandeLines;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: CartLine::class)]
    private $cartLines;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    public function __construct()
    {
        $this->commandeLines = new ArrayCollection();
        $this->cartLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|CommandeLine[]
     */
    public function getCommandeLines(): Collection
    {
        return $this->commandeLines;
    }

    public function addCommandeLine(CommandeLine $commandeLine): self
    {
        if (!$this->commandeLines->contains($commandeLine)) {
            $this->commandeLines[] = $commandeLine;
            $commandeLine->setProduct($this);
        }

        return $this;
    }

    public function removeCommandeLine(CommandeLine $commandeLine): self
    {
        if ($this->commandeLines->removeElement($commandeLine)) {
            // set the owning side to null (unless already changed)
            if ($commandeLine->getProduct() === $this) {
                $commandeLine->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CartLine[]
     */
    public function getCartLines(): Collection
    {
        return $this->cartLines;
    }

    public function addCartLine(CartLine $cartLine): self
    {
        if (!$this->cartLines->contains($cartLine)) {
            $this->cartLines[] = $cartLine;
            $cartLine->setProduct($this);
        }

        return $this;
    }

    public function removeCartLine(CartLine $cartLine): self
    {
        if ($this->cartLines->removeElement($cartLine)) {
            // set the owning side to null (unless already changed)
            if ($cartLine->getProduct() === $this) {
                $cartLine->setProduct(null);
            }
        }

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
}
