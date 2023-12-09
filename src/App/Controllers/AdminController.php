<?php

namespace Controllers;

use Faker\Factory;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Models\User;
use Models\Brand;
use Models\Color;
use Models\Review;
use Models\Vehicle;
use Models\NumberPlace;
use Controllers\Controller;

class AdminController extends Controller
{

    private $user;
    private $brand;
    private $numberPlace;
    private $color;
    private $vehicle;
    private $review;

    function __construct($db)
    {
        parent::__construct($db);
        $this->user = new User($this->db);
        $this->brand = new Brand($this->db);
        $this->numberPlace = new NumberPlace($this->db);
        $this->color = new Color($this->db);
        $this->vehicle = new Vehicle($this->db);
        $this->review = new Review($this->db);
    }

    // ---------- ADMIN HOME ---------- //

    function index()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $user = $_SESSION['user'];

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/admin/admin.html.twig');
        echo $template->render(['user' => $user, 'activeAdmin' => 'active']);
    }

    // ---------- USERS ---------- //

    function users()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $user = $_SESSION['user'];

        $datas = $this->user->getUsers();

        $alertMessage = $_SESSION['alertMessage'] ?? null;
        unset($_SESSION['alertMessage']);

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/admin/users.html.twig');
        echo $template->render(['datas' => $datas, 'alertMessage' => $alertMessage, 'user' => $user, 'activeAdmin' => 'active']);
    }

    function newUser()
    {

        $faker = Factory::create('fr_FR');

        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $password = password_hash($faker->password(), PASSWORD_DEFAULT);
        $is_admin = $_POST['is_admin'];

        if ($this->user->getUser($email)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Un utilisateur existe déjà avec cette adresse mail !
            </div>
            ';

            header('Location: /admin/users');
            exit;
        } else {
            $this->user->createUser($lastname, $firstname, $phone, $email, $password, $address, $is_admin);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-success d-flex align-items-center" role="alert">
                L\'utilisateur a bien été créé !
            </div>
            ';

            header('Location: /admin/users');
            exit;
        }
    }

    function updateUser()
    {
        $userId = $_POST['update_user_id'];
        $lastname = $_POST['update_lastname'];
        $firstname = $_POST['update_firstname'];
        $phone = $_POST['update_phone'];
        $email = $_POST['update_email'];
        $address = $_POST['update_address'];
        $password = "";
        $is_admin = $_POST['update_is_admin'];

        if ($this->user->getUser($email)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Un utilisateur existe déjà avec cette adresse mail !
            </div>
            ';

            header('Location: /admin/users');
            exit;
        } else {
            $this->user->updateUser($userId, $lastname, $firstname, $phone, $email, $address, $password, $is_admin);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                L\'utilisateur a bien été modifié !
            </div>
            ';

            header('Location: /admin/users');
            exit;
        }
    }

    function deleteUser($id)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }

        $this->user->deleteUser($id);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            L\'utilisateur a bien été supprimé !
        </div>
        ';
        header('Location: /admin/users');
        exit;
    }


    // ---------- BRANDS ---------- //
    function brands()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $user = $_SESSION['user'];

        $datas = $this->brand->getBrands();

        $alertMessage = $_SESSION['alertMessage'] ?? null;
        unset($_SESSION['alertMessage']);

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/admin/brands.html.twig');
        echo $template->render(['datas' => $datas, 'alertMessage' => $alertMessage, 'user' => $user, 'activeAdmin' => 'active']);
    }

    function newBrand()
    {
        $brand = $_POST['brand'];

        if ($this->brand->getBrand($brand)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Une marque existe déjà avec ce nom !
            </div>
            ';

            header('Location: /admin/brands');
            exit;
        } else {
            $this->brand->createBrand($brand);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-success d-flex align-items-center" role="alert">
                La marque a bien été créée !
            </div>
            ';

            header('Location: /admin/brands');
            exit;
        }
    }

    function updateBrand()
    {
        $brandId = $_POST['update_brand_id'];
        $brand = $_POST['update_brand'];

        if ($this->brand->getBrand($brand)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Une marque existe déjà avec ce nom !
            </div>
            ';

            header('Location: /admin/brands');
            exit;
        } else {
            $this->brand->updateBrand($brandId, $brand);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                La marque a bien été modifiée !
            </div>
            ';

            header('Location: /admin/brands');
            exit;
        }
    }

    function deleteBrand($id)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }

        $this->brand->deleteBrand($id);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            La marque a bien été supprimée !
        </div>
        ';

        header('Location: /admin/brands');
        exit;
    }

    // ---------- NUMBER PLACE ---------- //
    function numberPlaces()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $user = $_SESSION['user'];

        $datas = $this->numberPlace->getNumbersPlace();

        $alertMessage = $_SESSION['alertMessage'] ?? null;
        unset($_SESSION['alertMessage']);

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/admin/number-places.html.twig');
        echo $template->render(['datas' => $datas, 'alertMessage' => $alertMessage, 'user' => $user, 'activeAdmin' => 'active']);
    }

    function newNumberPlace()
    {
        $numberPlace = $_POST['number_place'];

        if ($this->numberPlace->getNumberPlace($numberPlace)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Ce nombre de place existe déjà !
            </div>
            ';

            header('Location: /admin/number-places');
            exit;
        } else {
            $this->numberPlace->createNumberPlace($numberPlace);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-success d-flex align-items-center" role="alert">
                Le nombre de place a bien été créé !
            </div>
            ';

            header('Location: /admin/number-places');
            exit;
        }
    }

    function updateNumberPlace()
    {
        $numberPlaceId = $_POST['update_number-places_id'];
        $numberPlace = $_POST['update_number-places'];

        if ($this->numberPlace->getNumberPlace($numberPlace)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Ce nombre de place existe déjà !
            </div>
            ';

            header('Location: /admin/number-places');
            exit;
        } else {
            $this->numberPlace->updateNumberPlace($numberPlaceId, $numberPlace);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                Le nombre de place a bien été modifié !
            </div>
            ';

            header('Location: /admin/number-places');
            exit;
        }
    }

    function deleteNumberPlace($id)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }

        $this->numberPlace->deleteNumberPlace($id);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            Le nombre de place a bien été supprimé !
        </div>
        ';

        header('Location: /admin/number-places');
        exit;
    }


    // ---------- COLORS ---------- //
    function colors()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $user = $_SESSION['user'];

        $datas = $this->color->getColors();

        $alertMessage = $_SESSION['alertMessage'] ?? null;
        unset($_SESSION['alertMessage']);

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/admin/colors.html.twig');
        echo $template->render(['datas' => $datas, 'alertMessage' => $alertMessage, 'user' => $user, 'activeAdmin' => 'active']);
    }

    function newColor()
    {
        $color = $_POST['color'];

        if ($this->color->getColor($color)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Cette couleur existe déjà !
            </div>
            ';

            header('Location: /admin/colors');
            exit;
        } else {
            $this->color->createColor($color);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-success d-flex align-items-center" role="alert">
                La couleur a bien été créée !
            </div>
            ';

            header('Location: /admin/colors');
            exit;
        }
    }

    function updateColor()
    {
        $colorId = $_POST['update_color_id'];
        $color = $_POST['update_color'];


        if ($this->color->getColor($color)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Cette couleur existe déjà !
            </div>
            ';

            header('Location: /admin/colors');
            exit;
        } else {
            $this->color->updateColor($colorId, $color);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                La couleur a bien été modifiée !
            </div>
            ';

            header('Location: /admin/colors');
            exit;
        }
    }

    function deleteColor($id)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }

        $this->color->deleteColor($id);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            La couleur a bien été supprimée !
        </div>
        ';

        header('Location: /admin/colors');
        exit;
    }


    // ---------- VEHICLES ---------- //
    function vehicles()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $user = $_SESSION['user'];

        $datas = $this->vehicle->getVehiculesWithAllInfo();

        $alertMessage = $_SESSION['alertMessage'] ?? null;
        unset($_SESSION['alertMessage']);

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/admin/vehicles.html.twig');
        echo $template->render(['datas' => $datas,  'alertMessage' => $alertMessage, 'user' => $user, 'activeAdmin' => 'active']);
    }

    function newVehicle()
    {
        $image = '';

        if (isset($_FILES['image'])) {
            $target = __DIR__ . "/../../public/assets/vehicles/";

            $image = basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $target . $image);
        } else {
            $_SESSION['alertMessage'] = '
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    Veuillez ajouter une image !
                </div>
                ';

            header('Location: /admin/vehicles');
            exit;
        }
        $image = '/assets/vehicles/' . $image;
        $name = $_POST['name'];
        $localisation = $_POST['localisation'];
        $brand = $_POST['brand_id'];
        $numberPlace = $_POST['number_place_id'];
        $color = $_POST['color_id'];
        $price = $_POST['price'];

        $brandId = $this->brand->getBrandIdByName($brand)['id'] ?? null;
        $numberPlaceId = $this->numberPlace->getNumberPlaceIdByName($numberPlace)['id'] ?? null;
        $colorId = $this->color->getColorIdByName($color)['id'] ?? null;

        if ($this->brand->getBrandById($brandId) == false) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                La marque n\'existe pas !
            </div>
            ';

            header('Location: /admin/vehicles');
            exit;
        } elseif ($this->numberPlace->getNumberPlaceById($numberPlaceId) == false) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Le nombre de place n\'existe pas !
            </div>
            ';

            header('Location: /admin/vehicles');
            exit;
        } elseif ($this->color->getColorById($colorId) == false) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                La couleur n\'existe pas !
            </div>
            ';

            header('Location: /admin/vehicles');
            exit;
        }

        // If validation passes, then insert into the database
        $this->vehicle->createVehicle($image, $name, $localisation, $brandId, $numberPlaceId, $colorId, $price);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-success d-flex align-items-center" role="alert">
            Le véhicule a bien été créé !
        </div>
        ';

        header('Location: /admin/vehicles');
        exit;
    }


    function researchVehicles()
    {
        $research = $_GET['research'] ?? null;
        if ($research == null) {
            self::vehicles();
            exit;
        }

        $datas = $this->vehicle->searchVehicles($research);

        $alertMessage = $_SESSION['alertMessage'] ?? null;
        unset($_SESSION['alertMessage']);

        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $user = $_SESSION['user'];

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/admin/vehicles.html.twig');
        echo $template->render(['datas' => $datas,  'alertMessage' => $alertMessage, 'user' => $user, 'activeAdmin' => 'active']);
    }

    function updateVehicle()
    {
        $vehicleId = $_POST['update_vehicle_id'];
        $name = $_POST['update_name'];
        $image = $_POST['update_image'];
        $localisation = $_POST['update_localisation'];
        $brand = $_POST['update_brand'];
        $numberPlace = $_POST['update_number_place'];
        $color = $_POST['update_color'];
        $price = $_POST['update_price'];

        $brandId = $this->brand->getBrandIdByName($brand)['id'] ?? null;
        $numberPlaceId = $this->numberPlace->getNumberPlaceIdByName($numberPlace)['id'] ?? null;
        $colorId = $this->color->getColorIdByName($color)['id'] ?? null;

        if ($brandId == null) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                La marque n\'existe pas !
            </div>
            ';

            header('Location: /admin/vehicles');
            exit;
        } elseif ($numberPlaceId == null) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Le nombre de place n\'existe pas !
            </div>
            ';

            header('Location: /admin/vehicles');
            exit;
        } elseif ($colorId == null) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                La couleur n\'existe pas !
            </div>
            ';

            header('Location: /admin/vehicles');
            exit;
        } else {
            $this->vehicle->updateVehicle($vehicleId, $name, $image, $localisation, $brandId, $numberPlaceId, $colorId, $price);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                Le véhicule a bien été modifié !
            </div>
            ';

            header('Location: /admin/vehicles');
            exit;
        }
    }

    function deleteVehicle($id)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }

        $this->vehicle->deleteVehicle($id);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            Le véhicule a bien été supprimé !
        </div>
        ';

        header('Location: /admin/vehicles');
        exit;
    }


    // ---------- REVIEWS ---------- //
    function reviews()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $user = $_SESSION['user'];

        $datas = $this->review->getReviewsWithAllInfo();

        $alertMessage = $_SESSION['alertMessage'] ?? null;
        unset($_SESSION['alertMessage']);

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/admin/reviews.html.twig');
        echo $template->render(['datas' => $datas, 'alertMessage' => $alertMessage, 'user' => $user, 'activeAdmin' => 'active']);
    }

    function newReview()
    {
        $userId = $_POST['user_id'];
        $vehicleId = $_POST['vehicle_id'];
        $comment = $_POST['review'];
        $rating = $_POST['stars'];

        if ($rating < 0 || $rating > 5) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                La note doit être comprise entre 0 et 5 !
            </div>
            ';

            header('Location: /admin/reviews');
            exit;
        } elseif ($this->user->getUserById($userId) == false) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                L\'utilisateur n\'existe pas !
            </div>
            ';

            header('Location: /admin/reviews');
            exit;
        } elseif ($this->vehicle->getVehicleById($vehicleId) == false) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Le véhicule n\'existe pas !
            </div>
            ';

            header('Location: /admin/reviews');
            exit;
        } else {
            $this->review->createReview($userId, $vehicleId, $comment, $rating);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-success d-flex align-items-center" role="alert">
                L\'avis a bien été créé !
            </div>
            ';

            header('Location: /admin/reviews');
            exit;
        }
    }

    function updateReview()
    {
        $reviewId = $_POST['update_review_id'];
        $comment = $_POST['update_review'];
        $rating = $_POST['update_stars'];

        if ($rating < 0 || $rating > 5) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                La note doit être comprise entre 0 et 5 !
            </div>
            ';

            header('Location: /admin/reviews');
            exit;
        } else {
            $this->review->updateReview($reviewId, $comment, $rating);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                L\'avis a bien été modifié !
            </div>
            ';

            header('Location: /admin/reviews');
            exit;
        }
    }

    function deleteReview($id)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user'][0] != 1) {
            header('Location: /unauthorized');
            exit;
        }
        $this->review->deleteReview($id);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            L\'avis a bien été supprimé !
        </div>
        ';

        header('Location: /admin/reviews');
        exit;
    }
}
