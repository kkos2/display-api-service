<?php

namespace App\Entity;

use App\Repository\SlideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=SlideRepository::class)
 */
class Slide implements EntitySharedInterface, EntityPublishedInterface
{
    use EntityIdTrait;
    use EntityPublishedTrait;
    use EntityTitleDescriptionTrait;
    use EntityModificationTrait;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity=Template::class, inversedBy="slides")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Template $template = null;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private array $templateOptions = [];

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $duration = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $content = [];

    /**
     * @ORM\ManyToMany(targetEntity=Media::class, inversedBy="slides")
     */
    private Collection $media;

    /**
     * @ORM\OneToMany(targetEntity=PlaylistSlide::class, mappedBy="slide", fetch="EXTRA_LAZY")
     */
    private Collection $playlistSlides;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->playlistSlides = new ArrayCollection();
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function removeTemplate(): self
    {
        $this->template = null;

        return $this;
    }

    public function getTemplateOptions(): array
    {
        return $this->templateOptions;
    }

    public function setTemplateOptions(array $templateOptions): self
    {
        $this->templateOptions = $templateOptions;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function setContent(array $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        $this->media->removeElement($medium);

        return $this;
    }

    public function removeAllMedium(): self
    {
        $this->media->clear();

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPlaylistSlides(): Collection
    {
        return $this->playlistSlides;
    }

    public function addPlaylistSlide(PlaylistSlide $playlistSlide): self
    {
        if (!$this->playlistSlides->contains($playlistSlide)) {
            $this->playlistSlides[] = $playlistSlide;
            $playlistSlide->setSlide($this);
        }

        return $this;
    }

    public function removePlaylistSlide(PlaylistSlide $playlistSlide): self
    {
        if ($this->playlistSlides->removeElement($playlistSlide)) {
            // set the owning side to null (unless already changed)
            if ($playlistSlide->getSlide() === $this) {
                $playlistSlide->setSlide(null);
            }
        }

        return $this;
    }
}
