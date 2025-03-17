<?php
// src/Controller/CalculateTravelTimeAction.php

namespace App\Controller;

use App\Entity\TravelTimeOutput;
use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
class CalculateTravelTimeAction extends AbstractController
{
    public function __construct(private CarRepository $carRepository) {}

    public function __invoke(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['model'])) {
            throw new BadRequestHttpException('Le champ Modèle est requis et ne peut pas être vide.');
        }

        if (!isset($data['distance']) || $data['distance'] === null || $data['distance'] <= 0) {
            throw new BadRequestHttpException('La distance doit être un nombre positif.');
        }

        $model = $data['model'];
        $distance = $data['distance'];

        $car = $this->carRepository->findOneBy(['model' => $model]);

        if (!$car) {
            throw new NotFoundHttpException('Voiture non trouvée.');
        }


        $kmh = $car->getKmh();
        // Le champ `kmh` est déjà validé avant l'enregistrement, mais cette vérification est ajoutée pour renforcer la sécurité.
        if ($kmh === null || $kmh <= 0) {
            throw new BadRequestHttpException('La vitesse de la voiture est invalide.');
        }

        // Calculer le temps de trajet
        $timeInHours = $distance / $kmh;
        $heures = (int) $timeInHours;
        $minutes = (int) (($timeInHours - $heures) * 60);

        $travelTimeOutput = new TravelTimeOutput($heures, $minutes);

        return $this->json($travelTimeOutput, 200, [], ['groups' => ['travel_time:read']]);
    }
}