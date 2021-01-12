<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\SerializedName;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *     itemOperations={ "get", "put", "delete"},
 *     collectionOperations={"get", "post"},
 *     normalizationContext={
 *          "groups"={"user"}
 *     }
 * )
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    const ROLE_AGENT = 'ROLE_AGENT';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    const DEFAULT_ROLES = [self::ROLE_AGENT];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user","operation"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","operation"})
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","operation"})
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","operation"})
     * @Assert\NotBlank()
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user","operation"})
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user"})
     * @Assert\Regex(
     *     pattern="/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}/",
     *     message="La chaîne doit contenir au moins 1 lettre minuscule, 1 majuscule, 1 caractère numérique et doit comporter au moins 8 caractères",
     *     groups={"post"}
     *     )
     */
    private $password;

    /**
     * @Assert\Expression(
     *     "this.getPassword() === this.getRetypedPassword()",
     *     message="Passwords does not match" )
     */
    private $retypedPassword;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"user","operation"})
     * @Assert\NotBlank()
     */
    private $telephone;

    /**
     * @ORM\Column(type="simple_array", length=200)
     * @Groups({"user","operation"})
     */
    private $roles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Station", mappedBy="user", cascade={"persist", "remove"})
     */
    private $stations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Panel", mappedBy="user")
     */
    private $panels;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Operation", mappedBy="user", cascade={"persist", "remove"})
     * @Groups({"user"})
     */
    private $operationUser;





    public function __construct()
    {
        $this->stations = new ArrayCollection();
        $this->panels = new ArrayCollection();
        $this->operationUser = new ArrayCollection();
        $this->roles = self::DEFAULT_ROLES;
        $this->etat = true;

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
		//if ($password != null || $this->getPassword() == $password){
		
			$this->password = $password;
			return $this;
		//}
		//return $this;
    }

    public function getRetypedPassword(): ?string
    {
        return $this->retypedPassword;
    }

    public function setRetypedPassword(string $retypedPassword): void
    {
        $this->retypedPassword = $retypedPassword;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getRoles(): array
    {
        return $this->roles;
//        return ['ROLE_USER']
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getEnabled()
    {
        return $this->etat;
    }

    public function setEnabled($enabled): void
    {
        $this->etat = $enabled;
    }



    public function getStations()
    {
        return $this->stations;
    }

    public function setStations($stations): void
    {
        $this->stations = $stations;
    }

    public function getPanels()
    {
        return $this->panels;
    }

    public function setPanels($panels): void
    {
        $this->panels = $panels;
    }

    public function getOperationUser()
    {
        return $this->operationUser;
    }

    public function setOperationUser($operationUser): void
    {
        $this->operationUser = $operationUser;
    }

    public function addStation(Station $station): self
    {
        if (!$this->stations->contains($station)) {
            $this->stations[] = $station;
            $station->setUser($this);
        }

        return $this;
    }

    public function removeStation(Station $station): self
    {
        if ($this->stations->contains($station)) {
            $this->stations->removeElement($station);
            // set the owning side to null (unless already changed)
            if ($station->getUser() === $this) {
                $station->setUser(null);
            }
        }

        return $this;
    }

    public function addPanel(Panel $panel): self
    {
        if (!$this->panels->contains($panel)) {
            $this->panels[] = $panel;
            $panel->setUser($this);
        }

        return $this;
    }

    public function removePanel(Panel $panel): self
    {
        if ($this->panels->contains($panel)) {
            $this->panels->removeElement($panel);
            // set the owning side to null (unless already changed)
            if ($panel->getUser() === $this) {
                $panel->setUser(null);
            }
        }

        return $this;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operationUser->contains($operation)) {
            $this->operationUser[] = $operation;
            $operation->setUser($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operationUser->contains($operation)) {
            $this->operationUser->removeElement($operation);
            // set the owning side to null (unless already changed)
            if ($operation->getUser() === $this) {
                $operation->setUser(null);
            }
        }

        return $this;
    }


    public function __toString()
    {
        return $this->getNom();
    }

    public function getSalt()
    {
        return null;
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }








}
