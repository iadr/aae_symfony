<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TutorHoursRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class TutorHours
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tutorHours")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Exclude()
     */
    private $tutorId;

    /**
     * @ORM\Column(type="time")
     */
    private $hour;

    /**
     * @ORM\Column(type="integer")
     */
    private $dayOfWeek;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTutorId(): ?User
    {
        return $this->tutorId;
    }

    public function setTutorId(?User $tutorId): self
    {
        $this->tutorId = $tutorId;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        return $this->hour;
    }

    public function setHour(\DateTimeInterface $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getDayOfWeek(): ?string
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(string $dayOfWeek): self
    {
        $this->dayOfWeek = $dayOfWeek;

        return $this;
    }
}
