<?php

namespace Controllers;

use Exception;
use Models\User;
use Models\Brand;
use Models\Color;
use Dotenv\Dotenv;
use Models\Vehicle;
use Models\Favorite;
use Twig\Environment;
use Models\NumberPlace;
use Models\Reservation;
use Controllers\Controller;
use Twig\Loader\FilesystemLoader;

class VehiclesController extends Controller
{
    private $user;
    private $brand;
    private $numberPlace;
    private $color;
    private $vehicle;
    private $favorite;
    private $reservation;

    function __construct($db)
    {
        parent::__construct($db);
        $this->user = new User($this->db);
        $this->brand = new Brand($this->db);
        $this->numberPlace = new NumberPlace($this->db);
        $this->color = new Color($this->db);
        $this->vehicle = new Vehicle($this->db);
        $this->favorite = new Favorite($this->db);
        $this->reservation = new Reservation($this->db);
    }

    function index()
    {
        $vehicles = $this->vehicle->getVehiculesWithAllInfo();
        $user = $_SESSION['user'] ?? null;


        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/vehicles/vehicles.html.twig');
        echo $template->render(['vehicles' => $vehicles, 'activeListOfVehicles' => 'active', 'user' => $user]);
    }

    function search()
    {
        $user = $_SESSION['user'] ?? null;
        $researchCity = $_POST['searchCity'];

        // verif filters
        if (empty($_POST['brands'])) {
            $brands = $this->brand->getBrands();
            foreach ($brands as $brand) {
                $selectedBrands[] = $brand['brand'];
            }
        } else {
            $selectedBrands = $_POST['brands'];
        }

        if (empty($_POST['numberPlaces'])) {
            $numberPlaces = $this->numberPlace->getNumbersPlace();
            foreach ($numberPlaces as $numberPlace) {
                $selectedNumberPlaces[] = $numberPlace['numberPlace'];
            }
        } else {
            $selectedNumberPlaces = $_POST['numberPlaces'];
        }

        if (empty($_POST['colors'])) {
            $colors = $this->color->getColors();
            foreach ($colors as $color) {
                $selectedColors[] = $color['color'];
            }
        } else {
            $selectedColors =  $_POST['colors'];
        }

        $maxPrice = $_POST['maxPrice'] ?? $this->vehicle->getMaxAndMinPrice()['maxPrice'];

        // get vehicles
        $vehicles = $this->vehicle->getVehiclesByResearch($researchCity, $selectedBrands, $selectedNumberPlaces, $selectedColors, $maxPrice);

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/vehicles/vehicles.html.twig');
        echo $template->render(['vehicles' => $vehicles, 'activeListOfVehicles' => 'active', 'user' => $user]);
    }

    function show($id)
    {
        // si l'id n'existe pas
        if (!$this->vehicle->getVehiculeWithAllInfo($id)) {
            header('Location: /notfound');
            exit();
        }

        $vehicleInfos = $this->vehicle->getVehiculeWithAllInfo($id);
        $reviews = $this->vehicle->getReviews($id);

        $user = $_SESSION['user'] ?? null;
        $alertMessage = $_SESSION['alertMessage'] ?? null;

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        if ($user == null) {

            $template = $twig->load('/pages/vehicles/details.html.twig');
            echo $template->render(['vehicle' => $vehicleInfos, 'reviews' => $reviews, 'activeListOfVehicles' => 'active', 'user' => $user, 'alertMessage' => $alertMessage]);
            unset($_SESSION['alertMessage']);
        } else {

            $isFavorite = $this->favorite->getFavoriteByUserAndVehicle($_SESSION['user'][1], $id);
            $template = $twig->load('/pages/vehicles/details.html.twig');
            echo $template->render(['vehicle' => $vehicleInfos, 'reviews' => $reviews, 'activeListOfVehicles' => 'active', 'user' => $user, 'isFavorite' => $isFavorite, 'alertMessage' => $alertMessage]);
            unset($_SESSION['alertMessage']);
        }
    }

    function checkAvailability($vehicle_id)
    {
        if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning align-items-center mb-0" role="alert">
                Vous devez être connecté pour réserver un véhicule !
            </div>
            ';

            header('Location: /login');
            exit;
        }
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $reservations = $this->reservation->getReservationByVehicleId($vehicle_id);
        $user = $_SESSION['user'];

        self::checkDates($startDate, $endDate, $vehicle_id);

        $reservationAvailable = true;
        // verif si reservation possible
        foreach ($reservations as $reservation) {
            $startReservation = $reservation['start_date'];
            $endReservation = $reservation['end_date'];
            $startRequested = $startDate;
            $endRequested = $endDate;

            if (
                ($startRequested >= $startReservation && $startRequested <= $endReservation) ||
                ($endRequested >= $startReservation && $endRequested <= $endReservation) ||
                ($startRequested <= $startReservation && $endRequested >= $endReservation)
            ) {
                $reservationAvailable = false;
                break;
            }
        }
        self::makeReservation($vehicle_id, $user, $startDate, $endDate, $reservationAvailable);
    }

    function checkDates($startDate, $endDate, $vehicle_id)
    {
        if (empty($startDate) || empty($endDate)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning align-items-center mb-0" role="alert">
                Veuillez saisir une date de début et de fin !
            </div>
            ';
            header('Location: /vehicle/' . $vehicle_id);
            exit();
        } else if ($startDate > $endDate) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning align-items-center mb-0" role="alert">
                La date de début doit être inférieur à la date de fin !
            </div>
            ';
            header('Location: /vehicle/' . $vehicle_id);
            exit();
        } else if ($startDate < date('Y-m-d')) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning align-items-center mb-0" role="alert">
                La date de début doit être supérieur à la date du jour !
            </div>
            ';
            header('Location: /vehicle/' . $vehicle_id);
            exit();
        }
    }

    function makeReservation($vehicle_id, $user, $startDate, $endDate, $reservationAvailable)
    {
        if ($reservationAvailable) {
            $oneDayPrice = $this->vehicle->getVehiculeWithAllInfo($vehicle_id)['price'];
            $price = $oneDayPrice * (strtotime($endDate) - strtotime($startDate)) / 86400 + $oneDayPrice; // prend en compte le jour de fin
            $userEmail = $this->user->getUserById($user[1])['email'];

            $this->reservation->createReservation($price, $user[1], $vehicle_id, $startDate, $endDate);

            self::sendMail($userEmail, $startDate, $endDate, $price);

            $_SESSION['alertMessage'] = '
                    <div class="alert alert-success align-items-center mb-0" role="alert">
                        Ce véhicule a été réservé avec succès ! Vous allez recevoir un e-mail de confirmation. <br>
                        Date de début : ' . $startDate . ' <br>
                        Date de fin : ' . $endDate . ' inclus <br>
                        Prix : ' . $price . '€
                    </div>
                ';
        } else {
            $_SESSION['alertMessage'] = '
                    <div class="alert alert-warning align-items-center mb-0" role="alert">
                        Ce véhicule n\'est pas disponible à ces dates !
                    </div>
                ';
        }

        self::show($vehicle_id);
    }

    function sendMail($userEmail, $startDate, $endDate, $price)
    {
        $phpmailer = new \PHPMailer\PHPMailer\PHPMailer();
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
        $dotenv->load();

        try {
            $phpmailer->isSMTP();
            $phpmailer->Host = 'smtp.mailtrap.io';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Port = 2525;
            $phpmailer->Username = $_ENV['MT_USERNAME'];
            $phpmailer->Password = $_ENV['MT_PASSWORD'];

            $phpmailer->setFrom('welcome.wheelygo@gmail.com', 'WheelyGo');
            $phpmailer->addAddress($userEmail);

            $phpmailer->CharSet = 'UTF-8';
            $phpmailer->Subject = 'Confirmation de réservation de véhicule';
            $phpmailer->Body = '
                Bonjour,

                Votre véhicule a été réservé avec succès !

                Date de début : ' . $startDate . '
                Date de fin : ' . $endDate . ' inclus
                Prix : ' . $price . '€

                Merci pour votre confiance.

                Cordialement,
                WheelyGo, Inc
            ';

            $phpmailer->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
        }
    }
}
