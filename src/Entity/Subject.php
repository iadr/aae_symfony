<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubjectRepository")
 */
class Subject
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="subjects")
     */
    private $subjectTutor;

    public function __construct()
    {
        $this->subjectTutor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getSubjectTutor(): Collection
    {
        return $this->subjectTutor;
    }

    public function addSubjectTutor(User $subjectTutor): self
    {
        if (!$this->subjectTutor->contains($subjectTutor)) {
            $this->subjectTutor[] = $subjectTutor;
        }

        return $this;
    }

    public function removeSubjectTutor(User $subjectTutor): self
    {
        if ($this->subjectTutor->contains($subjectTutor)) {
            $this->subjectTutor->removeElement($subjectTutor);
        }

        return $this;
    }
}
