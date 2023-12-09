<?php

namespace Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Models\Brand;
use Models\Color;
use Models\Vehicle;
use Models\NumberPlace;
use Controllers\Controller;

class HomeController extends Controller
{

    private $brand;
    private $numberPlace;
    private $color;
    private $vehicle;

    function __construct($db)
    {
        parent::__construct($db);
        $this->brand = new Brand($this->db);
        $this->numberPlace = new NumberPlace($this->db);
        $this->color = new Color($this->db);
        $this->vehicle = new Vehicle($this->db);
    }

    function index()
    {
        $brands = $this->brand->getBrands();
        $nbPlaces = $this->numberPlace->getNumbersPlace();
        $colors = $this->color->getColors();
        $prices = $this->vehicle->getMaxAndMinPrice();
        $vehicles = $this->vehicle->getVehiculesWithAllInfo();

        $alertMessage = $_SESSION['alertMessage'] ?? null;

        $user = $_SESSION['user'] ?? null;
        $alertMessageLog = $_SESSION['alertMessageLog'] ?? null;

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/home/home.html.twig');
        echo $template->render(['brands' => $brands, 'nbPlaces' => $nbPlaces, 'colors' => $colors, 'prices' => $prices, 'alertMessage' => $alertMessage, 'alertMessageLog' => $alertMessageLog, 'user' => $user, 'vehicles' => $vehicles]);

        unset($_SESSION['alertMessage']);
        unset($_SESSION['alertMessageLog']);
    }

    function notFound()
    {
        $user = $_SESSION['user'] ?? null;

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/home/notFound.html.twig');
        echo $template->render(['user' => $user]);
    }

    function unauthorized()
    {
        $user = $_SESSION['user'] ?? null;

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/home/unauthorized.html.twig');
        echo $template->render(['user' => $user]);
    }
}
