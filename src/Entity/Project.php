<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column]
    private ?bool $archived = null;

    #[ORM\ManyToMany(targetEntity: Status::class, mappedBy: 'projects')]
    private Collection $allowed_statuses;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $tasks;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'projects')]
    private Collection $Employee;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'allowed_in')]
    private Collection $allowed_tags;

    public function __construct()
    {
        $this->allowed_statuses = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->Employee = new ArrayCollection();
        $this->allowed_tags = new ArrayCollection();
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return Collection<int, Status>
     */
    public function getallowed_statuses(): Collection
    {
        return $this->allowed_statuses;
    }

    public function addStatus(Status $status): static
    {
        if (!$this->allowed_statuses->contains($status)) {
            $this->allowed_statuses->add($status);
            $status->addProject($this);
        }

        return $this;
    }

    public function removeStatus(Status $status): static
    {
        if ($this->allowed_statuses->removeElement($status)) {
            $status->removeProject($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployee(): Collection
    {
        return $this->Employee;
    }

    public function addEmployee(Employee $employee): static
    {
        if (!$this->Employee->contains($employee)) {
            $this->Employee->add($employee);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): static
    {
        $this->Employee->removeElement($employee);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getAllowedTags(): Collection
    {
        return $this->allowed_tags;
    }

    public function addAllowedTag(Tag $allowedTag): static
    {
        if (!$this->allowed_tags->contains($allowedTag)) {
            $this->allowed_tags->add($allowedTag);
        }

        return $this;
    }

    public function removeAllowedTag(Tag $allowedTag): static
    {
        $this->allowed_tags->removeElement($allowedTag);

        return $this;
    }
}
