<?php

require '../../vendor/autoload.php';

use Models\User;
use Models\Brand;
use Models\Color;
use Core\Database;
use Faker\Factory;
use Models\Review;
use Models\Vehicle;
use Models\Favorite;
use Core\InitDatabase;
use Models\NumberPlace;
use Models\Reservation;

class FakeDatas
{

    private $user;
    private $brand;
    private $color;
    private $review;
    private $vehicle;
    private $favorite;
    private $numberPlace;
    private $reservation;

    function __construct($db)
    {
        $this->user = new User($db);
        $this->brand = new Brand($db);
        $this->color = new Color($db);
        $this->review = new Review($db);
        $this->vehicle = new Vehicle($db);
        $this->favorite = new Favorite($db);
        $this->numberPlace = new NumberPlace($db);
        $this->reservation = new Reservation($db);
    }

    function createFakeDatas()
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Faker\Provider\Fakecar($faker));

        self::createFakeUsers($faker);
        self::createRootUser($faker);
        self::createFakeBrands();
        self::createFakeNbPlace();
        self::createFakeColors();
        self::createFakeVehicle($faker);
        self::createFakeReviews($faker);
        self::createFakeFavorites($faker);
        self::createFakeReservation($faker);
    }

    function createFakeUsers($faker)
    {
        for ($i = 0; $i < 20; $i++) {
            $user = [];
            $user[] =  $faker->lastName();
            $user[] =  $faker->firstName(null);
            $user[] =  $faker->serviceNumber();
            $user[] =  $faker->email();
            $user[] =  password_hash($faker->password(), PASSWORD_DEFAULT);
            $user[] =  $faker->address();
            $user[] =  $faker->numberBetween(0, 1);

            $this->user->createUser($user[0], $user[1], $user[2], $user[3], $user[4], $user[5], $user[6]);
        }
    }

    function createRootUser($faker)
    {
        $pwd = $faker->password();

        $root = [];
        $root[] =  $faker->lastName();
        $root[] =  $faker->firstName(null);
        $root[] =  $faker->serviceNumber();
        $root[] =  $faker->email();
        $root[] =  password_hash($pwd, PASSWORD_DEFAULT);
        $root[] =  $faker->address();
        $root[] =  1;

        $this->user->createUser($root[0], $root[1], $root[2], $root[3], $root[4], $root[5], $root[6]);
        echo "------------------------------------\n" .
            "➜ root email : " .  $root[3] . "\n" .
            "➜ root password : " . $pwd . "\n";
    }

    function createFakeBrands()
    {
        $brands = [
            'Nissan',
            'Renault',
            'Volvo',
            'Tesla',
            'Fiat',
            'Peugeot',
            'Volkswagen',
            'Ferrari',
            'Hyundai',
            'Kia'
        ];

        for ($i = 0; $i < count($brands); $i++) {
            $this->brand->createBrand($brands[$i]);
        }
    }

    function createFakeNbPlace()
    {
        $nbPlaces = [
            2,
            3,
            4,
            5,
            7,
            9
        ];

        for ($i = 0; $i < count($nbPlaces); $i++) {
            $this->numberPlace->createNumberPlace($nbPlaces[$i]);
        }
    }

    function createFakeColors()
    {
        $colors = [
            'Red',
            'White',
            'Gray',
            'Black',
            'Matte Black',
            'Turquoise Blue',
            'Navy Blue'
        ];

        for ($i = 0; $i < count($colors); $i++) {
            $this->color->createColor($colors[$i]);
        }
    }

    function createFakeVehicle($faker)
    {
        $rhoneAlpesCities = ['Lyon', 'Grenoble', 'Annecy', 'Saint-Étienne', 'Chambéry', 'Valence', 'Villeurbanne', 'Vénissieux', 'Roanne', 'Albertville'];

        $vehicles = [
            ['/assets/vehicles/NissanWhite.png', $faker->vehicleModel(), $rhoneAlpesCities[0], 1, $faker->numberBetween(1, 6), 2, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/RenaultNavyBlue.png', $faker->vehicleModel(), $rhoneAlpesCities[1], 2, $faker->numberBetween(1, 6), 7, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/VolvoRed.png', $faker->vehicleModel(), $rhoneAlpesCities[2], 3, $faker->numberBetween(1, 6), 1, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/TeslaWhite.png', $faker->vehicleModel(), $rhoneAlpesCities[3], 4, $faker->numberBetween(1, 6), 2, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/FiatTurquoiseBlue.png', $faker->vehicleModel(), $rhoneAlpesCities[4], 5, $faker->numberBetween(1, 6), 6, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/PeugeotBlack.png', $faker->vehicleModel(), $rhoneAlpesCities[5], 6, $faker->numberBetween(1, 6), 4, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/VolkswagenGray.png', $faker->vehicleModel(), $rhoneAlpesCities[6], 7, $faker->numberBetween(1, 6), 3, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/FerrariMatteBlack.png', $faker->vehicleModel(), $rhoneAlpesCities[7], 8, $faker->numberBetween(1, 6), 5, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/HyundaiBlack.png', $faker->vehicleModel(), $rhoneAlpesCities[8], 9, $faker->numberBetween(1, 6), 4, $faker->numberBetween(50, 500)],
            ['/assets/vehicles/KiaRed.png', $faker->vehicleModel(), $rhoneAlpesCities[9], 10, $faker->numberBetween(1, 6), 1, $faker->numberBetween(50, 500)]
        ];

        for ($i = 0; $i < count($vehicles); $i++) {
            $this->vehicle->createVehicle($vehicles[$i][0], $vehicles[$i][1], $vehicles[$i][2], $vehicles[$i][3], $vehicles[$i][4], $vehicles[$i][5], $vehicles[$i][6]);
        }
    }

    function createFakeReservation($faker)
    {
        // pasted
        for ($i = 0; $i < 5; $i++) {
            $startDate = $faker->dateTimeBetween('-2 years', '-1 month')->format('Y-m-d');
            $endDate = $faker->dateTimeBetween($startDate, $startDate . ' +7 days')->format('Y-m-d');

            $nbJours = (strtotime($endDate) - strtotime($startDate)) / 86400;

            $reservation = [];
            $reservation[] = $faker->numberBetween(50, 100) * $nbJours;
            $reservation[] = $faker->numberBetween(1, 20);
            $reservation[] = $faker->numberBetween(1, 10);
            $reservation[] = $startDate;
            $reservation[] = $endDate;

            $this->reservation->createReservation($reservation[0], $reservation[1], $reservation[2], $reservation[3], $reservation[4]);
        }

        // current
        for ($i = 0; $i < 5; $i++) {
            $startDate = $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d');
            $endDate = $faker->dateTimeBetween($startDate, $startDate . ' +7 days')->format('Y-m-d');

            $nbJours = (strtotime($endDate) - strtotime($startDate)) / 86400;

            $reservation = [];
            $reservation[] = $faker->numberBetween(50, 100) * $nbJours;
            $reservation[] = $faker->numberBetween(1, 20);
            $reservation[] = $faker->numberBetween(1, 10);
            $reservation[] = $startDate;
            $reservation[] = $endDate;

            $this->reservation->createReservation($reservation[0], $reservation[1], $reservation[2], $reservation[3], $reservation[4]);
        }

        // future
        for ($i = 0; $i < 5; $i++) {
            $startDate = $faker->dateTimeBetween('+1 month', '+2 month')->format('Y-m-d');
            $endDate = $faker->dateTimeBetween($startDate, $startDate . ' +7 days')->format('Y-m-d');

            $nbJours = (strtotime($endDate) - strtotime($startDate)) / 86400;

            $reservation = [];
            $reservation[] = $faker->numberBetween(50, 100) * $nbJours;
            $reservation[] = $faker->numberBetween(1, 20);
            $reservation[] = $faker->numberBetween(1, 10);
            $reservation[] = $startDate;
            $reservation[] = $endDate;

            $this->reservation->createReservation($reservation[0], $reservation[1], $reservation[2], $reservation[3], $reservation[4]);
        }

        // for root user 1 future, 1 current, 1 pasted
        $startDate = $faker->dateTimeBetween('-2 years', '-1 month')->format('Y-m-d');
        $endDate = $faker->dateTimeBetween($startDate, $startDate . ' +7 days')->format('Y-m-d');

        $nbJours = (strtotime($endDate) - strtotime($startDate)) / 86400;

        $reservation = [];
        $reservation[] = $faker->numberBetween(50, 100) * $nbJours;
        $reservation[] = 21;
        $reservation[] = $faker->numberBetween(1, 10);
        $reservation[] = $startDate;
        $reservation[] = $endDate;

        $this->reservation->createReservation($reservation[0], $reservation[1], $reservation[2], $reservation[3], $reservation[4]);

        $startDate = $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d');
        $endDate = $faker->dateTimeBetween($startDate, $startDate . ' +7 days')->format('Y-m-d');

        $nbJours = (strtotime($endDate) - strtotime($startDate)) / 86400;

        $reservation = [];
        $reservation[] = $faker->numberBetween(50, 100) * $nbJours;
        $reservation[] = 21;
        $reservation[] = $faker->numberBetween(1, 10);
        $reservation[] = $startDate;
        $reservation[] = $endDate;

        $this->reservation->createReservation($reservation[0], $reservation[1], $reservation[2], $reservation[3], $reservation[4]);

        $startDate = $faker->dateTimeBetween('+1 month', '+2 month')->format('Y-m-d');
        $endDate = $faker->dateTimeBetween($startDate, $startDate . ' +7 days')->format('Y-m-d');

        $nbJours = (strtotime($endDate) - strtotime($startDate)) / 86400;

        $reservation = [];
        $reservation[] = $faker->numberBetween(50, 100) * $nbJours;
        $reservation[] = 21;
        $reservation[] = $faker->numberBetween(1, 10);
        $reservation[] = $startDate;
        $reservation[] = $endDate;

        $this->reservation->createReservation($reservation[0], $reservation[1], $reservation[2], $reservation[3], $reservation[4]);
    }

    function createFakeReviews($faker)
    {
        for ($i = 0; $i < 10; $i++) {
            $review = [];
            $review[] =  $faker->numberBetween(1, 10);
            $review[] =  $faker->numberBetween(1, 10);
            $review[] =  $faker->sentence();
            $review[] =  $faker->numberBetween(1, 5);

            $this->review->createReview($review[0], $review[1], $review[2], $review[3]);
        }
    }

    function createFakeFavorites()
    {
        $possibleValues = range(1, 10);
        $pairs = [];

        foreach ($possibleValues as $value1) {
            foreach ($possibleValues as $value2) {
                if ($value1 != $value2) {
                    $pairs[] = [$value1, $value2];
                }
            }
        }

        shuffle($pairs);

        for ($i = 0; $i < 10; $i++) {
            $favorite = $pairs[$i];
            $this->favorite->createFavorite($favorite[0], $favorite[1]);
        }
    }
}

$db = Database::connect();
InitDatabase::Init($db);

$fakeDatas = new FakeDatas($db);
$fakeDatas->createFakeDatas();

echo "\n" . "➜ Fake datas created !" . "\n" .
    "------------------------------------" . "\n";
