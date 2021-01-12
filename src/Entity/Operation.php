<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OperationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OperationRepository::class)
 * @ApiResource(
 *     itemOperations={ "get", "put","delete"},
 *     collectionOperations={"get", "post"},
 *      normalizationContext={
 *          "groups"={"operation"}
 *     }
 * )
 */
class Operation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user","operation"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user","operation"})
     */
    private $dateCreation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","operation"})
     */
    private $imageAfter;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","operation"})
     */
    private $commentaireAfter;

    /**
     * @ORM\Column(type="simple_array", length=255)
     * @Groups({"user","operation"})
     */
    private $localisationAfter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="operationUser")
     * @Groups({"operation"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Station", inversedBy="operationStation")
     * @Groups({"operation"})
     */
    private $station;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Panel", inversedBy="operationPanel")
     * @Groups({"operation"})
     */
    private $panel;


    public function __construct()
    {
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    

    public function getImageAfter()
    {
        return $this->imageAfter;
    }

    public function setImageAfter($imageAfter): void
    {
        $this->imageAfter = $imageAfter;
    }

    public function getCommentaireAfter()
    {
        return $this->commentaireAfter;
    }

    public function setCommentaireAfter($commentaireAfter): void
    {
        $this->commentaireAfter = $commentaireAfter;
    }

    public function getLocalisationAfter()
    {
        return $this->localisationAfter;
    }


    public function setLocalisationAfter($localisationAfter): void
    {
        $this->localisationAfter = $localisationAfter;
    }

    public function getUser():?User
    {
        return $this->user;
    }

    public function setUser($user): void
    {
        $this->user = $user;
    }

    public function getStation() : ?Station
    {
        return $this->station;
    }

    public function setStation($station): void
    {
        $this->station = $station;
    }

    public function getPanel(): ?Panel
    {
        return $this->panel;
    }

    public function setPanel(Panel $panel): void
    {
        $this->panel = $panel;
    }

    public function addPanel(Panel $panel): self
    {
        if (!$this->panel->contains($panel)) {
            $this->panel[] = $panel;
            $panel->setOperationPanel($this);
        }

        return $this;
    }

    public function removePanel(Panel $panel): self
    {
        if ($this->panel->contains($panel)) {
            $this->panel->removeElement($panel);
            // set the owning side to null (unless already changed)
            if ($panel->getOperationPanel() === $this) {
                $panel->setOperationPanel(null);
            }
        }
        return $this;
    }

}
