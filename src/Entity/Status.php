<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'allowed_statuses')]
    private Collection $allowed_in;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'status')]
    private Collection $used_in;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
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
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        $this->projects->removeElement($project);

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
            $usedIn->setStatus($this);
        }

        return $this;
    }

    public function removeUsedIn(Task $usedIn): static
    {
        if ($this->used_in->removeElement($usedIn)) {
            // set the owning side to null (unless already changed)
            if ($usedIn->getStatus() === $this) {
                $usedIn->setStatus(null);
            }
        }

        return $this;
    }
}
