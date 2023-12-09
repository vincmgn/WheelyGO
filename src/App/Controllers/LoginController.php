<?php

namespace Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Models\User;
use Controllers\Controller;

class LoginController extends Controller
{

    private $user;

    function __construct($db)
    {
        parent::__construct($db);
        $this->user = new User($this->db);
    }

    function index()
    {
        $loader = new FilesystemLoader('../App/Views');
        $twig = new Environment($loader);

        $alertMessage = $_SESSION['alertMessage'] ?? null;

        $template = $twig->load('/pages/user/login.html.twig');
        echo $template->render(['alertMessage' => $alertMessage]);

        unset($_SESSION['alertMessage']);
    }

    function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = $this->user->getUser($email);

        if (!$user) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                Aucun compte n\'existe avec cette adresse email !
            </div>
            ';

            self::index();
            exit();
        } else if (!password_verify($password, $user['password'])) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                Le mot de passe est incorrect !
            </div>
            ';

            self::index();
            exit();
        } else {
            $_SESSION['user'][0] = $user['isAdmin'];
            $_SESSION['user'][1] = $user['id'];

            $_SESSION['alertMessageLog'] = '
            <div class="alert alert-primary d-flex align-items-center" role="alert">
                Vous êtes bien connecté !
            </div>
            ';

            header('Location: /');
            exit();
        }
    }

    function logout()
    {
        unset($_SESSION['user']);

        $_SESSION['alertMessageLog'] = '
        <div class="alert alert-primary d-flex align-items-center" role="alert">
            Vous êtes bien déconnecté !
        </div>
        ';

        header('Location: /');
        exit();
    }
}
