<?php

namespace App\Service\DTO;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class GroupDTO
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=180)
     */
    private $name;

    /**
     * @var UploadedFile
     * @Assert\Image(
     *     minWidth = 100,
     *     maxWidth = 400,
     *     minHeight = 100,
     *     maxHeight = 400
     * )
     */
    private $image;

    /**
     * @var int
     */
    private $ownerId;

    /**
     * GroupDTO constructor.
     * @param string $name
     * @param int $ownerId
     */
    public function __construct(string $name, int $ownerId)
    {
        $this->name = $name;
        $this->ownerId = $ownerId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return UploadedFile
     */
    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    /**
     * @param UploadedFile $image
     */
    public function setImage(UploadedFile $image): void
    {
        $this->image = $image;
    }

    /**
     * @return int
     */
    public function getOwnerId(): int
    {
        return $this->ownerId;
    }

    /**
     * @param int $ownerId
     */
    public function setOwnerId(int $ownerId): void
    {
        $this->ownerId = $ownerId;
    }
}