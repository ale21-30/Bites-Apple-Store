<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Illuminate\Http\UploadedFile;

class FirebaseService
{
    protected $storage;
    protected $bucketName;

    public function __construct()
    {
        // Deshabilitar verificación SSL para desarrollo en Windows
        $certPath = base_path('storage/app/cacert.pem');
        if (file_exists($certPath)) {
            putenv('CURL_CA_BUNDLE=' . $certPath);
        } else {
            // Si no existe el certificado, deshabilitar verificación SSL temporalmente
            putenv('GCLOUD_SKIP_SSL_VERIFICATION=true');
        }
        
        $factory = (new Factory)
            ->withServiceAccount(
                base_path(env('FIREBASE_CREDENTIALS'))
            );

        $this->storage = $factory->createStorage();
        $this->bucketName = env('FIREBASE_STORAGE_BUCKET');
    }

    /**
     * Subir imagen a Firebase Storage
     */
    public function uploadImage(UploadedFile $file, string $folder = 'images'): string
    {
        $fileName = $folder . '/' . time() . '_' . $file->getClientOriginalName();

        $bucket = $this->storage->getBucket($this->bucketName);

        $object = $bucket->upload(
            fopen($file->getPathname(), 'r'),
            [
                'name' => $fileName,
                'metadata' => [
                    'contentType' => $file->getMimeType(),
                ],
            ]
        );

        // Hacer público el archivo
        $object->update(['acl' => []], ['predefinedAcl' => 'publicRead']);

        // Retornar URL pública
        return "https://firebasestorage.googleapis.com/v0/b/{$this->bucketName}/o/" . urlencode($fileName) . "?alt=media";
    }
}

