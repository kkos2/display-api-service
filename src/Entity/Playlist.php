<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use RRule\RRule;

/**
 * @ORM\Entity(repositoryClass=PlaylistRepository::class)
 */
class Playlist implements EntitySharedInterface, EntityPublishedInterface
{
    use EntityIdTrait;
    use EntityPublishedTrait;
    use EntityTitleDescriptionTrait;
    use EntityModificationTrait;
    use TimestampableEntity;

    /**
     * @ORM\ManyToMany(targetEntity=Screen::class, mappedBy="playlists")
     */
    private Collection $screens;

    /**
     * @ORM\OneToMany(targetEntity=PlaylistScreenRegion::class, mappedBy="playlist", orphanRemoval=true)
     */
    private Collection $playlistScreenRegions;

    /**
     * @ORM\OneToMany(targetEntity=PlaylistSlide::class, mappedBy="playlist", orphanRemoval=true)
     * @ORM\OrderBy({"weight" = "ASC"})
     */
    private Collection $playlistSlides;

    /**
     * @ORM\Column(type="rrule", nullable=true)
     */
    public ?RRule $schedule = null;

    public function __construct()
    {
        $this->screens = new ArrayCollection();
        $this->playlistScreenRegions = new ArrayCollection();
        $this->playlistSlides = new ArrayCollection();
    }

    /**
     * @return Collection|Screen[]
     */
    public function getScreens(): Collection
    {
        return $this->screens;
    }

    public function addScreen(Screen $screen): self
    {
        if (!$this->screens->contains($screen)) {
            $this->screens->add($screen);
            $screen->addPlaylist($this);
        }

        return $this;
    }

    public function removeScreen(Screen $screen): self
    {
        if ($this->screens->removeElement($screen)) {
            $screen->removePlaylist($this);
        }

        return $this;
    }

    public function removeAllScreens(): self
    {
        foreach ($this->screens as $screen) {
            $screen->removePlaylist($this);
        }

        $this->screens->clear();

        return $this;
    }

    /**
     * @return Collection|PlaylistScreenRegion[]
     */
    public function getPlaylistScreenRegions(): Collection
    {
        return $this->playlistScreenRegions;
    }

    public function addPlaylistScreenRegion(PlaylistScreenRegion $playlistScreenRegion): self
    {
        if (!$this->playlistScreenRegions->contains($playlistScreenRegion)) {
            $this->playlistScreenRegions->add($playlistScreenRegion);
            $playlistScreenRegion->setPlaylist($this);
        }

        return $this;
    }

    public function removePlaylistScreenRegion(PlaylistScreenRegion $playlistScreenRegion): self
    {
        if ($this->playlistScreenRegions->removeElement($playlistScreenRegion)) {
            // set the owning side to null (unless already changed)
            if ($playlistScreenRegion->getPlaylist() === $this) {
                $playlistScreenRegion->removePlaylist();
            }
        }

        return $this;
    }

    public function removeAllPlaylistScreenRegions(): self
    {
        foreach ($this->playlistScreenRegions as $playlistScreenRegion) {
            // set the owning side to null (unless already changed)
            if ($playlistScreenRegion->getPlaylist() === $this) {
                $playlistScreenRegion->removePlaylist();
            }
        }

        $this->playlistScreenRegions->clear();

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
            $playlistSlide->setPlaylist($this);
        }

        return $this;
    }

    public function removePlaylistSlide(PlaylistSlide $playlistSlide): self
    {
        if ($this->playlistSlides->removeElement($playlistSlide)) {
            // set the owning side to null (unless already changed)
            if ($playlistSlide->getPlaylist() === $this) {
                $playlistSlide->setPlaylist(null);
            }
        }

        return $this;
    }

    public function getSchedule(): ?RRule
    {
        return $this->schedule;
    }

    public function setSchedule(?RRule $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }
}
