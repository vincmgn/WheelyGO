<?php

namespace Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Models\User;
use Models\Review;
use Models\Favorite;
use Models\Reservation;
use Controllers\Controller;

class ProfileController extends Controller
{
    private $user;
    private $review;
    private $reservation;
    private $favorite;

    function __construct($db)
    {
        parent::__construct($db);
        $this->user = new User($this->db);
        $this->review = new Review($this->db);
        $this->reservation = new Reservation($this->db);
        $this->favorite = new Favorite($this->db);
    }

    function index()
    {
        /* session */
        $user = $_SESSION['user'] ?? null;
        $_SESSION['datasUser'] = $this->user->getUserById($_SESSION['user'][1]);
        $alertMessage = $_SESSION['alertMessage'] ?? null;

        /* reviews */
        $reviews = $this->review->getAllReviewWithALlInfoByUserId($_SESSION['user'][1]);

        /* reservations */
        $reservations = $this->reservation->getAllReservationWithAllInfoByUserId($_SESSION['user'][1]);
        foreach ($reservations as &$reservation) {
            $reservation['is_finished'] = strtotime($reservation['end_date']) < time();

            $hasReview = $this->review->getReviewByVehicleAndUser($reservation['vehicle_id'], $_SESSION['user'][1]);
            $reservation['has_review'] = !empty($hasReview);
        }

        /* favorite */
        $favorites = $this->favorite->getAllFavoriteWithAllInfoByUserId($_SESSION['user'][1]);

        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $template = $twig->load('/pages/user/profile.html.twig');
        echo $template->render(['user' => $user, 'datas' => $_SESSION['datasUser'], 'alertMessage' => $alertMessage, 'reviews' => $reviews, 'reservations' => $reservations, 'favorites' => $favorites]);

        unset($_SESSION['alertMessage']);
    }

    function updateUser()
    {
        $userId = $_SESSION['user'][1];
        $isAdmin = $_SESSION['user'][0];
        $firstname = $_POST['update_firstname'];
        $lastname = $_POST['update_lastname'];
        $email = $_POST['update_email'];
        $phone = $_POST['update_phone'];
        $address = $_POST['update_address'];
        $password = $_POST['update_password'];
        $confirmPassword = $_POST['update_confirmPassword'];

        if ($this->user->getUserByEmail($email) && $this->user->getUserByEmail($email)['id'] != $userId) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Un utilisateur existe déjà avec cette adresse mail !
            </div>
            ';

            header('Location: /profile');
            exit();
        } elseif ($firstname == '' || $lastname == '' || $email == '' || $phone == '' || $address == '' || $password == '' || $confirmPassword == '') {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Veuillez remplir tous les champs !
            </div>
            ';

            header('Location: /profile');
            exit();
        } elseif ($password != $confirmPassword) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Les mots de passe ne correspondent pas !
            </div>
            ';

            header('Location: /profile');
            exit();
        } else {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->user->updateUser($userId, $lastname, $firstname, $phone, $email, $address, $hashPassword, $isAdmin);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-success d-flex align-items-center" role="alert">
                Votre profil a bien été mis à jour !
            </div>
            ';

            $_SESSION['datasUser'] = $this->user->getUserById($_SESSION['user'][1]);

            header('Location: /profile');
            exit();
        }
    }

    function deleteFavorite($vehicle_id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /unauthorized');
            exit();
        }

        if (!$this->favorite->getFavoriteByUserAndVehicle($_SESSION['user'][1], $vehicle_id)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Ce véhicule n\'est pas dans vos favoris !
            </div>
            ';

            header('Location: /profile');
            exit();
        }

        $this->favorite->deleteFavorite($_SESSION['user'][1], $vehicle_id);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-success d-flex align-items-center" role="alert">
            Le véhicule a bien été supprimé de vos favoris !
        </div>
        ';

        header('Location: /profile');
        exit();
    }

    function addFavorite($vehicle_id)
    {
        if (!isset($_SESSION['user'])) {

            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Vous devez être connecté pour ajouter un véhicule à vos favoris !
            </div>
            ';

            header('Location: /login');
            exit();
        }

        if ($this->favorite->getFavoriteByUserAndVehicle($_SESSION['user'][1], $vehicle_id)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Ce véhicule est déjà dans vos favoris !
            </div>
            ';

            header('Location: /profile');
            exit();
        }

        $this->favorite->createFavorite($_SESSION['user'][1], $vehicle_id);

        $_SESSION['alertMessage'] = '
        <div class="alert alert-success d-flex align-items-center" role="alert">
            Le véhicule a bien été ajouté à vos favoris !
        </div>
        ';

        header('Location: /profile');
        exit();
    }

    function addReview()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Vous devez être connecté pour laisser un avis !
            </div>
            ';

            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user'][1];
        $vehicleId = $_POST['vehicle_id'];
        $review = $_POST['comment'];
        $stars = $_POST['rateChoice'];

        if ($this->review->getReviewByVehicleAndUser($vehicleId, $userId)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-warning d-flex align-items-center" role="alert">
                Vous avez déjà laissé un avis sur ce véhicule !
            </div>
            ';
        } else {
            $this->review->createReview($userId, $vehicleId, $review, $stars);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-success d-flex align-items-center" role="alert">
            Votre avis a bien été ajouté !
            </div>
            ';
        }
        header('Location: /profile');
        exit();
    }


    function logout()
    {
        session_destroy();
        header('Location: /');
    }
}
