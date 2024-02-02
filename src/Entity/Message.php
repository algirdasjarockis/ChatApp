<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(indexes: ["name" => "created_at_index","columns" => "created_at"])]
#[ORM\HasLifecycleCallbacks]
class Message implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $appUser = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Conversation $conversation = null;

    private bool $isMine = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isMine(): bool
    {
        return $this->isMine;
    }

    public function setIsMine(bool $isMine)
    {
        $this->isMine = $isMine;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAppUser(): ?User
    {
        return $this->appUser;
    }

    public function setAppUser(?User $appUser): static
    {
        $this->appUser = $appUser;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "content" => $this->getContent(),
            "createdAt" => $this->getCreatedAt(),
            "mine" => $this->isMine(),
            "conversationId" => $this->getConversation()->getId(),
            "userAvatar" => $this->getAppUser()->getAvatarFileName(),
        ];
    }
}
