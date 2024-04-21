<?php

namespace App\Entity;

use App\Repository\TodoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TodoRepository::class)]
#[ORM\Table(name: '`todo`')]
class Todo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['todos-listing' , 'user-todos'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['todos-listing' , 'user-todos'])]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Groups(['todos-listing' , 'user-todos'])]

    private ?string $currentStatus = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'todos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $assignee = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getCurrentStatus(): ?string
    {
        return $this->currentStatus;
    }

    public function setCurrentStatus(string $currentStatus): static
    {
        $this->currentStatus = $currentStatus;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): static
    {
        $this->assignee = $assignee;

        return $this;
    }


}
