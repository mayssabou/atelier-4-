<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)] //class bch twali table ORM\Entity
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column] //il vas les cree dans base donne si je veux pas la variable dans base de donne je met pas ORM\Column
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $username = null;

    #[ORM\Column(length: 30)]
    private ?string $email = null;


    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Book::class)]
    private Collection $books;

    #[ORM\Column]
    private ?int $nb_book = null;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
            $book->setAuthor($this);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        if ($this->books->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getAuthor() === $this) {
                $book->setAuthor(null);
            }
        }

        return $this;
    }

    public function getNbBook(): ?int
    {
        return $this->nb_book;
    }

    public function setNbBook(int $nb_book): static
    {
        $this->nb_book = $nb_book;

        return $this;
    }
}
