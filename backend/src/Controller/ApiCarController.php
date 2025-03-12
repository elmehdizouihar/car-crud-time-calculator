<?php
// src/Controller/ApiCarController.php

namespace App\Controller;

use App\Entity\Caracteristique;
use App\Entity\Car;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiCarController extends AbstractController
{

    /**
     * @Route("/api/cars", name="app_api_car", methods={"POST"})
     */
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {

            $data = json_decode($request->getContent(), true);

            // Vérification si les données sont valides
            if (!$data) {
                return $this->json([
                    'message' => 'Données invalides envoyées.',
                    'error' => 'Le contenu de la requête n\'est pas valide.'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Validation des champs 'model' et 'kmh'
            if (empty($data['model'])) {
                return $this->json([
                    'message' => 'Le champ "Modèle" est requis.',
                    'error' => 'Le champ "Modèle" est vide.'
                ], Response::HTTP_BAD_REQUEST);
            }

            if (empty($data['kmh'])) {
                return $this->json([
                    'message' => 'Le champ "Kmh" est requis.',
                    'error' => 'Le champ "Kmh" est vide.'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Vérification si 'kmh' est un nombre valide
            if (!is_numeric($data['kmh']) || $data['kmh'] <= 0) {
                return $this->json([
                    'message' => 'Le champ "Kmh" doit être un nombre valide et supérieur à zéro.',
                    'error' => 'Valeur invalide pour "kmh".'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Vérifier si le modèle existe déjà
            $existingCar = $entityManager->getRepository(Car::class)->findOneBy(['model' => $data['model']]);

            if ($existingCar) {
                return $this->json([
                    'message' => 'Le modèle de la voiture existe déjà.',
                    'error' => 'Un autre véhicule avec le même modèle existe déjà.'
                ], Response::HTTP_BAD_REQUEST);
            }

            // dd($data);
            $car = new Car();
            $car->setKmh($data['kmh']);
            $car->setModel($data['model']);

            if (isset($data['caracteristiques']) && is_array($data['caracteristiques'])) {
                foreach ($data['caracteristiques'] as $caracteristiqueData) {
                    $caracteristique = new Caracteristique();
                    $caracteristique->setkey($caracteristiqueData['key']);
                    $caracteristique->setValue($caracteristiqueData['value']);
                    $car->addCaracteristique($caracteristique);
                    $entityManager->persist($caracteristique);
                }
            }

            $entityManager->persist($car);
            $entityManager->flush();

            return $this->json([
                'message' => 'La voiture a été créée avec succès',
                'data' => $data
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Erreur lors de la création de la Voiture',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/api/cars/{id}", name="app_api_car_update", methods={"PUT"})
     */
    public function update(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        try {
            // Décoder le contenu de la requête JSON
            $data = json_decode($request->getContent(), true);

            // Vérification si les données sont valides
            if (!$data) {
                return $this->json([
                    'message' => 'Données invalides envoyées.',
                    'error' => 'Le contenu de la requête n\'est pas valide.'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Validation des champs 'Model' et 'kmh'
            if (empty($data['model'])) {
                return $this->json([
                    'message' => 'Le champ "modelo" est requis.',
                    'error' => 'Le champ "modelo" est vide.'
                ], Response::HTTP_BAD_REQUEST);
            }

            if (empty($data['kmh'])) {
                return $this->json([
                    'message' => 'Le champ "Kmh" est requis.',
                    'error' => 'Le champ "Kmh" est vide.'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Vérification si 'kmh' est un nombre valide
            if (!is_numeric($data['kmh']) || $data['kmh'] <= 0) {
                return $this->json([
                    'message' => 'Le champ "Kmh" doit être un nombre valide et supérieur à zéro.',
                    'error' => 'Valeur invalide pour "Kmh".'
                ], Response::HTTP_BAD_REQUEST);
            }



            // Trouver la Voiture par son ID
            $car = $entityManager->getRepository(Car::class)->find($id);

            if (!$car) {
                return $this->json([
                    'message' => 'La Voiture n\'a pas été trouvée.',
                    'error' => 'La Voiture avec l\'ID spécifié n\'existe pas.'
                ], Response::HTTP_NOT_FOUND);
            }

            // Si le modèle a été modifié, vérifier qu'il n'existe pas déjà
            if ($car->getModel() !== $data['model']) {
                $existingCar = $entityManager->getRepository(Car::class)->findOneBy(['model' => $data['model']]);

                if ($existingCar) {
                    return $this->json([
                        'message' => 'Le modèle de la voiture existe déjà.',
                        'error' => 'Un autre véhicule avec ce modèle existe déjà.'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            $car->setKmh($data['kmh']);
            $car->setModel($data['model']);

            // Mettre à jour les caractéristiques si elles existent
            if (isset($data['caracteristiques']) && is_array($data['caracteristiques'])) {
                // Supprimer les anciennes caractéristiques
                $existingCaracteristiques = $car->getCaracteristiques();
                foreach ($existingCaracteristiques as $caracteristique) {
                    $car->removeCaracteristique($caracteristique);
                    $entityManager->remove($caracteristique);
                }

                // Ajouter les nouvelles caractéristiques
                foreach ($data['caracteristiques'] as $caracteristiqueData) {
                    $caracteristique = new Caracteristique();
                    $caracteristique->setkey($caracteristiqueData['key']);
                    $caracteristique->setValue($caracteristiqueData['value']);
                    $car->addCaracteristique($caracteristique);
                    $entityManager->persist($caracteristique);
                }
            }

            $entityManager->flush();

            return $this->json([
                'message' => 'La mise à jour de la voiture a été effectuée avec succès',
                'data' => $data
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Une erreur s\'est produite lors de la mise à jour de la Voiture.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/api/cars/{id}", name="app_api_car_delete", methods={"DELETE"})
     */
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        try {
            // Trouver la car par son ID
            $car = $entityManager->getRepository(Car::class)->find($id);

            if (!$car) {
                throw new NotFoundHttpException('Voiture non trouvée');
            }

            // Supprimer les caractéristiques associées
            $caracteristiques = $car->getCaracteristiques();
            foreach ($caracteristiques as $caracteristique) {
                $entityManager->remove($caracteristique);
            }

            // Supprimer la car
            $entityManager->remove($car);
            $entityManager->flush();

            return $this->json([
                'message' => 'La voiture a été supprimée avec succès'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Une erreur s\'est produite lors de la suppression de la Voiture.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @Route("/api/cars", name="app_api_car_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        try {
            $cars = $entityManager->getRepository(Car::class)->findBy([], ['id' => 'DESC']);
            $carsArray = [];
            foreach ($cars as $car) {
                $caracteristiquesArray = [];
                foreach ($car->getCaracteristiques() as $caracteristique) {
                    $caracteristiquesArray[] = [
                        'key' => $caracteristique->getKey(),
                        'value' => $caracteristique->getValue(),
                    ];
                }

                $carsArray[] = [
                    'id' => $car->getId(),
                    'model' => $car->getModel(),
                    'kmh' => $car->getKmh(),
                    'caracteristiques' => $caracteristiquesArray,
                ];
            }
            // dd($cars);

            return $this->json([
                'cars' => $carsArray
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Une erreur s\'est produite lors de la récupération des voitures.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Calculer le temps nécessaire à la voiture pour parcourir une distance donnée
    /**
     * @Route("/api/cars/calculate-time", name="app_api_car_calculate_time", methods={"POST"})
     */
    public function calculerTemps(Request $request, EntityManagerInterface $entityManager): Response
    {

        try {

            $data = json_decode($request->getContent(), true);

            if (!isset($data['distance']) || !isset($data['model'])) {
                return $this->json([
                    'message' => 'Les champs "Distance" et "Modèle" sont requis.',
                    'error' => 'Données manquantes'
                ], Response::HTTP_BAD_REQUEST);
            }

            $car = $entityManager->getRepository(Car::class)->findOneBy(['model' => $data['model']]);

            if (!$car) {
                return $this->json([
                    'message' => 'Ce modèle de voiture n\'a pas été trouvé.',
                    'error' => 'La voiture n\a pas été trouvée'
                ], Response::HTTP_NOT_FOUND); // 404 Not Found
            }

            // Récupérer la vitesse de la voiture
            $vitesse = $car->getKmh();

            // Validation de la vitesse
            if (!$vitesse || $vitesse <= 0) {
                return $this->json([
                    'message' => 'La vitesse du véhicule est invalide.',
                    'error' => 'Vitesse invalide'
                ], Response::HTTP_BAD_REQUEST);
            }

            // Calcul du temps en heures
            $distance = $data['distance'];
            $temps = $distance / $vitesse;

            // Convertir en heures et minutes
            $heures = floor($temps);
            $minutes = round(($temps - $heures) * 60);

            return $this->json([
                'temps' => [
                    'heures' => $heures,
                    'minutes' => $minutes,
                ]
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json([
                'message' => 'Erreur lors du calcul du temps',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
