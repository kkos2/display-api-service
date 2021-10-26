<?php

namespace App\DataTransformer;

use App\Dto\Media as MediaDTO;
use App\Entity\Media;

class MediaOutputDataTransformer extends AbstractOutputDataTransformer
{
    /**
     * {@inheritdoc}
     */
    public function transform($media, string $to, array $context = []): MediaDTO
    {
        /** @var Media $media */
        $output = parent::transform($media, $to, $context);

        $output->license = $media->getLicense();
        $output->assets = [
            'type' => $media->getMimeType(),
            'uri' => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost().$this->storage->resolveUri($media, 'file'),
            'dimensions' => [
                'height' => $media->getHeight(),
                'width' => $media->getWidth(),
            ],
            'sha' => $media->getSha(),
            'size' => $media->getSize(),
        ];

        return $output;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return MediaDTO::class === $to && $data instanceof Media;
    }
}
