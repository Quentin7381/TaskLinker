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

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'allowedTags')]
    private Collection $allowedIn;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'tags')]
    private Collection $usedIn;

    public function __construct()
    {
        $this->allowedIn = new ArrayCollection();
        $this->usedIn = new ArrayCollection();
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
        return $this->allowedIn;
    }

    public function setAllowedIn(array|Collection $allowedIn): static
    {
        $this->allowedIn = is_array($allowedIn) ? new ArrayCollection($allowedIn) : $allowedIn;
        return $this;
    }

    public function addAllowedIn(Project $allowedIn): static
    {
        if (!$this->allowedIn->contains($allowedIn)) {
            $this->allowedIn->add($allowedIn);
            $allowedIn->addAllowedTag($this);
        }

        return $this;
    }

    public function removeAllowedIn(Project $allowedIn): static
    {
        if ($this->allowedIn->removeElement($allowedIn)) {
            $allowedIn->removeAllowedTag($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getUsedIn(): Collection
    {
        return $this->usedIn;
    }

    public function setUsedIn(array|Collection $usedIn): static
    {
        $this->usedIn = is_array($usedIn) ? new ArrayCollection($usedIn) : $usedIn;
        return $this;
    }

    public function addUsedIn(Task $usedIn): static
    {
        if (!$this->usedIn->contains($usedIn)) {
            $this->usedIn->add($usedIn);
            $usedIn->addTag($this);
        }

        return $this;
    }

    public function removeUsedIn(Task $usedIn): static
    {
        if ($this->usedIn->removeElement($usedIn)) {
            $usedIn->removeTag($this);
        }

        return $this;
    }
}
