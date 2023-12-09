<?php

namespace Core;

use Controllers\HomeController;
use Controllers\AdminController;
use Controllers\LoginController;
use Controllers\ProfileController;
use Controllers\RegisterController;
use Controllers\VehiclesController;

class Router
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function run($uri, $method): void
    {
        // --------------- Home --------------- //
        if ($method == 'GET' && $uri == '/') {
            $controller = new HomeController($this->db);
            $controller->index();

            // --------------- Vehicles --------------- //
        } elseif ($method == 'POST' && strpos($uri, '/searchVehicle') === 0) {
            $controller = new VehiclesController($this->db);
            $controller->search();
        } elseif ($method == 'GET' && strpos($uri, '/vehicles') === 0) {
            $controller = new VehiclesController($this->db);
            $controller->index();
        } elseif ($method == 'GET' && strpos($uri, '/vehicle/') === 0) {
            $vehicleId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new VehiclesController($this->db);
            $controller->show($vehicleId);
        } else if ($method == 'POST' && strpos($uri, '/check-availability/vehicle/') === 0) {
            $vehicleId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new VehiclesController($this->db);
            $controller->checkAvailability($vehicleId);

            // --------------- User --------------- //
        } elseif ($uri == '/register') {
            $controller = new RegisterController($this->db);
            if ($method == 'GET') {
                $controller->index();
            } elseif ($method == 'POST') {
                $controller->register();
            }
        } elseif ($uri == '/login') {
            if ($method == 'GET') {
                $controller = new LoginController($this->db);
                $controller->index();
            } elseif ($method == 'POST') {
                $controller = new LoginController($this->db);
                $controller->login();
            }
        } elseif ($method == 'GET' && $uri == '/logout') {
            $controller = new LoginController($this->db);
            $controller->logout();
        } elseif ($method == 'GET' && $uri == '/profile') {
            $controller = new ProfileController($this->db);
            $controller->index();
        } elseif ($method == 'POST' && $uri == '/profile/update') {
            $controller = new ProfileController($this->db);
            $controller->updateUser();
        } elseif ($method == 'GET' && strpos($uri, '/profile/fav-delete/') === 0) {
            $vehicleId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new ProfileController($this->db);
            $controller->deleteFavorite($vehicleId);
        } elseif ($method == 'GET' && strpos($uri, '/profile/fav-add/') === 0) {
            $vehicleId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new ProfileController($this->db);
            $controller->addFavorite($vehicleId);
        } elseif ($method == 'POST' && $uri == '/profile/add-review') {
            $controller = new ProfileController($this->db);
            $controller->addReview();

            // --------------- Admin --------------- //

        } elseif ($method == 'GET' && $uri == '/admin') {
            $controller = new AdminController($this->db);
            $controller->index();

            //-USER-//
        } elseif ($uri == '/admin/users') {
            if ($method == 'GET') {
                $controller = new AdminController($this->db);
                $controller->users();
            } elseif ($method == 'POST') {
                $controller = new AdminController($this->db);
                $controller->newUser();
            }
        } elseif ($method == 'GET' && strpos($uri, '/admin/users/delete/') === 0) {
            $userId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new AdminController($this->db);
            $controller->deleteUser($userId);
        } elseif ($method == 'POST' && $uri == '/admin/users/update') {
            $controller = new AdminController($this->db);
            $controller->updateUser();

            //-BRAND-//
        } elseif ($uri == '/admin/brands') {
            if ($method == 'GET') {
                $controller = new AdminController($this->db);
                $controller->brands();
            } elseif ($method == 'POST') {
                $controller = new AdminController($this->db);
                $controller->newBrand();
            }
        } elseif ($method == 'GET' && strpos($uri, '/admin/brands/delete/') === 0) {
            $brandId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new AdminController($this->db);
            $controller->deleteBrand($brandId);
        } elseif ($method == 'POST' && $uri == '/admin/brands/update') {
            $controller = new AdminController($this->db);
            $controller->updateBrand();

            //-NB PLACE-//
        } elseif ($uri == '/admin/number-places') {
            if ($method == 'GET') {
                $controller = new AdminController($this->db);
                $controller->numberPlaces();
            } elseif ($method == 'POST') {
                $controller = new AdminController($this->db);
                $controller->newNumberPlace();
            }
        } elseif ($method == 'GET' && strpos($uri, '/admin/number-places/delete/') === 0) {
            $numberPlaceId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new AdminController($this->db);
            $controller->deleteNumberPlace($numberPlaceId);
        } elseif ($method == 'POST' && $uri == '/admin/number-places/update') {
            $controller = new AdminController($this->db);
            $controller->updateNumberPlace();

            //-COLOR-//
        } elseif ($uri == '/admin/colors') {
            if ($method == 'GET') {
                $controller = new AdminController($this->db);
                $controller->colors();
            } elseif ($method == 'POST') {
                $controller = new AdminController($this->db);
                $controller->newColor();
            }
        } elseif ($method == 'GET' && strpos($uri, '/admin/colors/delete/') === 0) {
            $colorId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new AdminController($this->db);
            $controller->deleteColor($colorId);
        } elseif ($method == 'POST' && $uri == '/admin/colors/update') {
            $controller = new AdminController($this->db);
            $controller->updateColor();

            //-VEHICLE-//
        } elseif ($uri == '/admin/vehicles') {
            if ($method == 'GET') {
                $controller = new AdminController($this->db);
                $controller->vehicles();
            } elseif ($method == 'POST') {
                $controller = new AdminController($this->db);
                $controller->newVehicle();
            }
        } elseif ($method == 'GET' && strpos($uri, '/admin/research') === 0) {
            $research = substr($uri, strrpos($uri, '/') + 1);
            $controller = new AdminController($this->db);
            $controller->researchVehicles($research);
        } elseif ($method == 'GET' && strpos($uri, '/admin/vehicles/delete/') === 0) {
            $vehicleId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new AdminController($this->db);
            $controller->deleteVehicle($vehicleId);
        } elseif ($method == 'POST' && $uri == '/admin/vehicles/update') {
            $controller = new AdminController($this->db);
            $controller->updateVehicle();

            //-REVIEW-//
        } elseif ($uri == '/admin/reviews') {
            if ($method == 'GET') {
                $controller = new AdminController($this->db);
                $controller->reviews();
            } elseif ($method == 'POST') {
                $controller = new AdminController($this->db);
                $controller->newReview();
            }
        } elseif ($method == 'GET' && strpos($uri, '/admin/reviews/delete/') === 0) {
            $reviewId = substr($uri, strrpos($uri, '/') + 1);
            $controller = new AdminController($this->db);
            $controller->deleteReview($reviewId);
        } elseif ($method == 'POST' && $uri == '/admin/reviews/update') {
            $controller = new AdminController($this->db);
            $controller->updateReview();

            //-401-//
        } elseif ($method == 'GET' && $uri == '/unauthorized') {
            $controller = new HomeController($this->db);
            $controller->unauthorized();
            //-PAGE NOT FOUND-//
        } else {
            $controller = new HomeController($this->db);
            $controller->notFound();
        }
    }
}
