<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PredictionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"eventId", "marketType"})})
 */
class Prediction implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $eventId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marketType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prediction;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $status = 'unresolved';

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\PrePersist()
     */
    public function onEntityCreate(): void
    {
        $this->createdAt = new \DateTime('now');
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @ORM\PreUpdate()
     */
    public function onEntityUpdate(): void
    {
        $this->updatedAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getMarketType(): ?string
    {
        return $this->marketType;
    }

    public function setMarketType(string $marketType): self
    {
        $this->marketType = $marketType;

        return $this;
    }

    public function getPrediction(): ?string
    {
        return $this->prediction;
    }

    public function setPrediction(string $prediction): self
    {
        $this->prediction = $prediction;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
           'eventId' => $this->eventId,
           'marketType' => $this->marketType,
           'status' => $this->status,
           'prediction' => $this->prediction
           ];
    }
}
