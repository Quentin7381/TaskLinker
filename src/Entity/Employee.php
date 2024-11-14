<?php

namespace App\Entity;

use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $enabled = true;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contractType = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $employmentDate = null;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'members')]
    private Collection $projects;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'assignee')]
    private Collection $assignedTo;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->assignedTo = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(?string $contractType): static
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getEmploymentDate(): ?\DateTimeInterface
    {
        return $this->employmentDate;
    }

    public function setEmploymentDate(?\DateTimeInterface $employmentDate): static
    {
        $this->employmentDate = $employmentDate;

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function setProjects(array|Collection $projects): static
    {
        $this->projects = is_array($projects) ? new ArrayCollection($projects) : $projects;
        return $this;
    }

    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->addMember($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getAssignedTo(): Collection
    {
        return $this->assignedTo;
    }

    public function setAssignedTo(array|Collection $assignedTo): static
    {
        $this->assignedTo = is_array($assignedTo) ? new ArrayCollection($assignedTo) : $assignedTo;
        return $this;
    }

    public function addAssignedTo(Task $assignedTo): static
    {
        if (!$this->assignedTo->contains($assignedTo)) {
            $this->assignedTo->add($assignedTo);
            $assignedTo->setAssignee($this);
        }

        return $this;
    }

    public function removeAssignedTo(Task $assignedTo): static
    {
        if ($this->assignedTo->removeElement($assignedTo)) {
            if ($assignedTo->getAssignee() === $this) {
                $assignedTo->setAssignee(null);
            }
        }

        return $this;
    }
}
