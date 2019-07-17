<?php


namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $pathPicture;

    public function __construct($targetDirectory, $pathPicture)
    {
        $this->targetDirectory = $targetDirectory;
        $this->pathPicture = $pathPicture;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        //dd($this->getTargetDirectory());
        try {
            $file->move($this->getTargetDirectory(), $fileName);

        } catch (FileException $e) {
// ... handle exception if something happens during file upload
        }

        return $this->pathPicture . $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}