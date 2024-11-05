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
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private ?bool $archived = null;

    #[ORM\ManyToMany(targetEntity: Status::class, mappedBy: 'allowedIn')]
    private Collection $allowedStatuses;

    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $tasks;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'projects')]
    private Collection $members;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'allowedIn')]
    private Collection $allowedTags;

    public function __construct()
    {
        $this->allowedStatuses = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->allowedTags = new ArrayCollection();
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
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

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
    public function getAllowedStatuses(): Collection
    {
        return $this->allowedStatuses;
    }

    public function setAllowedStatuses(array|Collection $allowedStatuses): static
    {
        $this->allowedStatuses = is_array($allowedStatuses) ? new ArrayCollection($allowedStatuses) : $allowedStatuses;
        return $this;
    }

    public function addAllowedStatuses(Status $status): static
    {
        if (!$this->allowedStatuses->contains($status)) {
            $this->allowedStatuses->add($status);
            $status->addAllowedIn($this);
        }

        return $this;
    }

    public function removeAllowedStatuses(Status $status): static
    {
        if ($this->allowedStatuses->removeElement($status)) {
            $status->removeAllowedIn($this);
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

    public function setTasks(array|Collection $tasks): static
    {
        $this->tasks = is_array($tasks) ? new ArrayCollection($tasks) : $tasks;
        return $this;
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
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function setMembers(array|Collection $members): static
    {
        $this->members = is_array($members) ? new ArrayCollection($members) : $members;
        return $this;
    }

    public function addMember(Employee $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
        }

        return $this;
    }

    public function removeMember(Employee $member): static
    {
        $this->members->removeElement($member);

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getAllowedTags(): Collection
    {
        return $this->allowedTags;
    }

    public function setAllowedTags(array|Collection $allowedTags): static
    {
        $this->allowedTags = is_array($allowedTags) ? new ArrayCollection($allowedTags) : $allowedTags;
        return $this;
    }

    public function addAllowedTag(Tag $allowedTag): static
    {
        if (!$this->allowedTags->contains($allowedTag)) {
            $this->allowedTags->add($allowedTag);
        }

        return $this;
    }

    public function removeAllowedTag(Tag $allowedTag): static
    {
        $this->allowedTags->removeElement($allowedTag);

        return $this;
    }
}
