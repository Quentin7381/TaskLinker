<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'allowed_tags')]
    private Collection $allowed_in;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'tags')]
    private Collection $used_in;

    public function __construct()
    {
        $this->allowed_in = new ArrayCollection();
        $this->used_in = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getAllowedIn(): Collection
    {
        return $this->allowed_in;
    }

    public function addAllowedIn(Project $allowedIn): static
    {
        if (!$this->allowed_in->contains($allowedIn)) {
            $this->allowed_in->add($allowedIn);
            $allowedIn->addAllowedTag($this);
        }

        return $this;
    }

    public function removeAllowedIn(Project $allowedIn): static
    {
        if ($this->allowed_in->removeElement($allowedIn)) {
            $allowedIn->removeAllowedTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getUsedIn(): Collection
    {
        return $this->used_in;
    }

    public function addUsedIn(Task $usedIn): static
    {
        if (!$this->used_in->contains($usedIn)) {
            $this->used_in->add($usedIn);
            $usedIn->addTag($this);
        }

        return $this;
    }

    public function removeUsedIn(Task $usedIn): static
    {
        if ($this->used_in->removeElement($usedIn)) {
            $usedIn->removeTag($this);
        }

        return $this;
    }
}
