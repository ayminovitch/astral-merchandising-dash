<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StationRepository::class)
 * @ApiResource(
 *     itemOperations={ "get", "put", "delete"},
 *     collectionOperations={"get", "post"},
 *      normalizationContext={
 *          "groups"={"station"}
 *     }
 * )
 */
class Station
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"operation","station"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"operation","station"})
     */
    private $nom;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="stations")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Panel", mappedBy="station", cascade={"remove"})
     * @Groups({"station"})
     */
    private $panels;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Operation", mappedBy="station")
     */
    private $operationStation;


    public function __construct()
    {
        $this->panels = new ArrayCollection();
        $this->operationStation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom() :? string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }


    public function getUser():?User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getPanels(): Collection
    {
        return $this->panels;
    }

    public function setPanels($panels): void
    {
        $this->panels = $panels;
    }

    public function addPanel(Panel $panel): self
    {
        if (!$this->panels->contains($panel)) {
            $this->panels[] = $panel;
//            $panel->setStation($this);
        }

        return $this;
    }

    public function removePanel(Panel $panel): self
    {
        if ($this->panels->contains($panel)) {
            $this->panels->removeElement($panel);
        }
        return $this;
    }

    public function getOperationStation(): Collection
    {
        return $this->operationStation;
    }

    public function setOperationStation(Collection $operationStation): void
    {
        $this->operationStation = $operationStation;
    }

    public function __toString()
    {
        return (string)$this->getNom();
    }

    //
//    public function getUser(): Collection
//    {
//        return $this->user;
//    }


}
