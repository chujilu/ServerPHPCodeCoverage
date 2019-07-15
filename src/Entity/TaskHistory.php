<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskHistoryRepository")
 */
class TaskHistory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Task")
     */
    private $task;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reportDir;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(Task $task): self
    {
        $this->task = $task;

        return $this;
    }

    public function getReportDir(): ?string
    {
        return $this->reportDir;
    }

    public function setReportDir(string $reportDir): self
    {
        $this->reportDir = $reportDir;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }
}
