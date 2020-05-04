<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
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
    private $appointmentId;

    /**
     * @ORM\Column(type="integer")
     */
    private $sender;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppointmentId(): ?int
    {
        return $this->appointmentId;
    }

    public function setAppointmentId(int $appointmentId): self
    {
        $this->appointmentId = $appointmentId;

        return $this;
    }

    public function getSender(): ?int
    {
        return $this->sender;
    }

    public function setSender(int $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
