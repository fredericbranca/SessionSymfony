<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Session;
use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Stagiaire;
use Faker\Factory as Faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        // Création de 10 utilisateurs
        $users = [];

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email)
                ->setNom($faker->lastName)
                ->setPrenom($faker->firstName)
                ->setPassword($faker->password)
                ->setRoles(['ROLE_USER'])
                ->setIsVerified(1);
            $manager->persist($user);

            $users[] = $user;
        }

        // Création de 60 Stagiaires
        $stagiaires = [];

        for ($i = 0; $i < 60; $i++) {
            $stagiaire = new Stagiaire();
            $stagiaire->setNom($faker->name)
                ->setPrenom($faker->firstName)
                ->setTelephone($faker->phoneNumber);
            $manager->persist($stagiaire);

            $stagiaires[] = $stagiaire;
        }

        // Création de Catégorie
        $listeCategories = ['Bureautique', 'DEV WEB', 'Comptabilité', 'Infographie', 'Vente'];
        $categories = [];

        foreach ($listeCategories as $cat) {
            $categorie = new Categorie();
            $categorie->setNom($cat);
            $manager->persist($categorie);

            $categories[] = $categorie;
        }

        // Création de Formation
        $listeFormations = ['Initialisation bureautique et infographie', 'Initialisation comptabilité', 'Remise à niveau numérique', 'DWWB (développeur Web / Web Mobile', 'Web Designer', 'Assistant dentaire'];
        $formations = [];

        foreach ($listeFormations as $cat) {
            $formation = new Formation();
            $formation->setNom($cat);
            $manager->persist($formation);

            $formations[] = $formation;
        }

        // Création de Session
        $sessions = [];

        for ($i= 0; $i < 10; $i++) {
            $session = new Session();
            $session->setFormation($faker->randomElement($formations))
                ->setNbPlace($faker->numberBetween(7, 15))
                ->setDateDebut($faker->dateTimeBetween('-10 week', '+1 week'))
                ->setDateFin($faker->dateTimeBetween('+2 week', '+30 week'));
            $manager->persist($session);

            $sessions[] = $session;

        }

        // Ajout des stagiaires dans une session
        $stagiaireSessions = [];
        foreach ($stagiaires as $stagiaire) {
            $stagiaireSession = $stagiaire->addSessionStagiaire($faker->randomElement($sessions));
            $manager->persist($stagiaireSession);

            $stagiaireSessions[] = $stagiaireSession;
        }

        $manager->flush();
    }
}