<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use TheDevs\WMS\Entity\User;

final class ImportProductFormData
{
    #[File(
        mimeTypes: ['text/xml', 'application/xml'],
        mimeTypesMessage: 'Nahraný soubor musí být XML.'
    )]
    public null|UploadedFile $file = null;

    #[NotBlank]
    public null|User $user = null;
}
