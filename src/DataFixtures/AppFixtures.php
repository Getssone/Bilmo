<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Particulier;
use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadPhones($manager);

        $manager->flush();
    }


    // Fonction pour générer un appareil aléatoire
    function generateRandomDevice($thisPhone)
    {
        $brands = [
            'Samsung' => ['Galaxy S22', 'Galaxy Note 20'],
            'Leica' => ['Leica Q2', 'Leica M10'],
            'Apple' => ['iPhone 13', 'iPhone 13 Pro Max'],
            'Huawei' => ['P40 Pro', 'Mate 40 Pro'],
            'Oppo' => ['Find X3 Pro', 'Reno 5 Pro'],
            'One+' => ['OnePlus 9', 'OnePlus 9 Pro']
        ];

        // array_keys est utilisée pour obtenir toutes les clés du tableau $brands, ce qui donnera un tableau indexé des noms des marques ['Samsung', 'Leica', 'Apple', ...]
        $brandArrayKeys = array_keys($brands);

        // La fonction rand est utilisée pour obtenir un index aléatoire entre 0 et le nombre total de marques moins 1 (parce que les indices de tableau commencent à 0).DONC $selectedBrandKey sera donc le nom d'une marque choisie au hasard.
        $selectedBrandKey = $brandArrayKeys[rand(0, count($brandArrayKeys) - 1)];
        //Cette ligne récupère le tableau des modèles associé à la marque aléatoirement sélectionnée dans $selectedBrandKey.
        $selectedBrandModels = $brands[$selectedBrandKey];
        //Enfin, un modèle de téléphone est choisi au hasard à partir du tableau des modèles de la marque sélectionnée en utilisant à nouveau la fonction rand pour obtenir un index aléatoire entre 0 et le nombre de modèles disponibles pour cette marque
        $selectedModel = $selectedBrandModels[rand(0, count($selectedBrandModels) - 1)];

        // Sélection aléatoire d'un type
        $randomType = rand(0, 2);
        switch ($randomType) {
            case 0:
                $type = 'phone';
                break;
            case 1:
                $type = 'phablette';
                break;
            case 2:
                $type = 'tablette';
                break;
        }

        $thisPhone->setType($type);
        $thisPhone->setBrand($selectedBrandKey);
        $thisPhone->setModel($selectedModel);
    }

    public function loadPhones(ObjectManager $manager): void
    {
        /** exemple */
        // $product = new Product();
        // $manager->persist($product);
        /** exemple */

        for ($i = 0; $i < 20; $i++) {
            $faker = Factory::create("fr_FR");

            $phone = new Phone();
            $this->generateRandomDevice($phone);
            $phone->setPrice(random_int(600, 1000));
            $phone->setDescription($faker->paragraph());
            $phone->setStock(random_int(0, 1000));
            $manager->persist($phone);
        }
    }
    public function loadUsers(ObjectManager $manager): void
    {
        /** exemple */
        // $product = new Product();
        // $manager->persist($product);
        /** exemple */
        $statusCompany = [
            "EI",
            "EIRL",
            "SARL",
            "EURL",
            "SASU",
            "SAS",
        ];

        $particuliers = [];

        for ($i = 0; $i < 20; $i++) {
            $faker = Factory::create("fr_FR");

            $user = new User();
            $user->setEmail($faker->freeEmail());
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
            $user->setAddress($faker->address());
            $user->setPhone($faker->phoneNumber());
            $user->setAvatar($faker->imageUrl(360, 360, 'Avatar', true));
            $user->setIsVerified((boolval(rand())));
            $selectedRoleKey = ["ROLE_USER"];
            $user->setRoles($selectedRoleKey);
            $particulier = new Particulier();
            //Cette méthode retourne un tableau des choix possibles.
            $genderChoices =  Particulier::getGenderChoices();
            //On récupère une clé aléatoire à partir du tableau $genderChoices.
            $randomGenderKey = array_rand($genderChoices);
            //$randomGender sera la valeur associée à la clé $randomGenderKey dans le tableau $genderChoices.
            $randomGender = $genderChoices[$randomGenderKey];
            //Enfin on indique $randomGender qui représente le genre à affecter à la propriété gender de l'objet $particulier.
            $particulier->setGender($randomGender);
            if ($randomGender === 'Masculin') {
                $particulier->setFirstName($faker->firstName('male'));
            } elseif ($randomGender === 'Féminin') {
                $particulier->setFirstName($faker->firstName('female'));
            } else {
                $particulier->setFirstName($faker->firstName());
            }
            $particulier->setLastName($faker->lastName());
            $particulier->setBirthday($faker->date("Y-m-d"));
            $particulier->setJob($faker->jobTitle());

            // Ajoute l'objet $particulier au tableau $particuliers
            $particuliers[] = $particulier;

            $user->setParticulier($particulier);
            $manager->persist($user);
        }
        for ($i = 0; $i < 20; $i++) {
            $faker = Factory::create("fr_FR");

            $user = new User();
            $user->setEmail($faker->freeEmail());
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
            $user->setAddress($faker->address());
            $user->setPhone($faker->phoneNumber());
            $user->setAvatar($faker->imageUrl(360, 360, 'Avatar', true));
            $user->setIsVerified((boolval(rand())));
            $selectedRoleKey = ["ROLE_ADMIN"];
            $user->setRoles($selectedRoleKey);
            $client = new Client();
            $client->setName($faker->lastName());
            $siret = str_pad($faker->randomNumber(9), 14, '0', STR_PAD_RIGHT);
            $client->setSiret($siret);
            $companyName = $faker->company();
            $client->setBusiness($companyName);
            $domaine = $faker->tld();
            $client->setWebSite($companyName . '.' . $domaine);
            //On récupère une string aléatoire à partir du tableau $statusCompany.
            $randomStatusCompany = array_rand($statusCompany);
            $client->setLegalStatus($statusCompany[$randomStatusCompany]);
            // Vérifiez si le tableau $particuliers contient des éléments avant d'en sélectionner un.
            if (!empty($particuliers)) {
                // Sélectionnez un particulier aléatoire parmi ceux créés
                $randomParticulier = $particuliers[array_rand($particuliers)];
                $client->addClientsParticulier($randomParticulier);
            }
            $user->setClient($client);
            $manager->persist($user);
        }
    }
}
