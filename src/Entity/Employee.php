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

    #[ORM\Column(length: 36)]
    private ?string $password = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    private ?string $last_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contract_type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $employement_date = null;

    #[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'Employee')]
    private Collection $projects;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'asignee')]
    private Collection $asigned_to;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->asigned_to = new ArrayCollection();
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

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
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contract_type;
    }

    public function setContractType(?string $contract_type): static
    {
        $this->contract_type = $contract_type;

        return $this;
    }

    public function getEmployementDate(): ?\DateTimeInterface
    {
        return $this->employement_date;
    }

    public function setEmployementDate(?\DateTimeInterface $employement_date): static
    {
        $this->employement_date = $employement_date;

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
            $project->addEmployee($this);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        if ($this->projects->removeElement($project)) {
            $project->removeEmployee($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getAsignedTo(): Collection
    {
        return $this->asigned_to;
    }

    public function addAsignedTo(Task $asignedTo): static
    {
        if (!$this->asigned_to->contains($asignedTo)) {
            $this->asigned_to->add($asignedTo);
            $asignedTo->setAsignee($this);
        }

        return $this;
    }

    public function removeAsignedTo(Task $asignedTo): static
    {
        if ($this->asigned_to->removeElement($asignedTo)) {
            // set the owning side to null (unless already changed)
            if ($asignedTo->getAsignee() === $this) {
                $asignedTo->setAsignee(null);
            }
        }

        return $this;
    }
}
