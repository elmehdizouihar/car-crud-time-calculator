<?php
// src/Serializer/CaracteristiqueDenormalizer.php

namespace App\Serializer;

use App\Entity\Caracteristique;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CaracteristiqueDenormalizer implements DenormalizerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        // Validation : Vérifiez si la clé ou la valeur est vide
        if ((empty($data['cle']) && !empty($data['value'])) || (!empty($data['cle']) && empty($data['value']))) {
            throw new BadRequestHttpException('Les caractéristiques doivent avoir une clé et une valeur si l\'une est remplie.');
        }

        // Si id est présent, récupérez existante
        if (isset($data['id'])) {
            $caracteristique = $this->entityManager->getRepository(Caracteristique::class)->find($data['id']);
            if (!$caracteristique) {
                throw new \RuntimeException('Caracteristique not found');
            }
        } else {
            // Sinon créer une nouvelle
            $caracteristique = new Caracteristique();
        }

        $caracteristique->setCle($data['cle']);
        $caracteristique->setValue($data['value']);

        return $caracteristique;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $type === Caracteristique::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Caracteristique::class => true,
        ];
    }
}