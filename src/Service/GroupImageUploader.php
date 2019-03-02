<?php

namespace App\Service;

use App\Entity\Group;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GroupImageUploader
{
    /**
     * @var string
     */
    private $directory;

    /**
     * GroupImageUploader constructor.
     * @param string $directory
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImageToGroup(UploadedFile $file, Group $group): void
    {
        $this->deleteImageIfExists($group);
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getDirectory(), $fileName);
        } catch (FileException $e) {
            $group->setImagePath('');
        }

        $group->setImagePath($fileName);
    }

    /**
     * @param Group $group
     */
    private function deleteImageIfExists(Group $group): void
    {
        if(empty($group->getImagePath())) {
            return;
        }

        $imagePath = $this->getDirectory() . '/' . $group->getImagePath();

        if(!file_exists($imagePath)) {
            return;
        }

        unlink($imagePath);

        $group->setImagePath('');
    }

    /**
     * @return string
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

}