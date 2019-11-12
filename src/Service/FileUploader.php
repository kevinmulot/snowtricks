<?php

namespace App\Service;

use App\Entity\Picture;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class FileUploader
 * @package App\Service
 */
class FileUploader
{
    /**
     * @var
     */
    private $targetDirectory;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     * @return string $fileName
     */
    public function upload(UploadedFile $file)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        return $fileName;
    }

    /**
     * @param $fileName
     */
    public function removeTrickPicture($fileName)
    {
        $fsys = new Filesystem();
        if ($fileName != 'default.jpg') {
            $fsys->remove('../public/images/tricks/' . $fileName);
            $fsys->remove('../public/media/cache/my_thumb/images/tricks/' . $fileName);
        }
    }

    /**
     * @param $fileName
     */
    public function removeUserPicture($fileName)
    {
        $fsys = new Filesystem();
        if ($fileName != 'default.jpg') {
            $fsys->remove('../public/media/cache/my_thumb/images/users/' . $fileName);
        }
    }

    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
