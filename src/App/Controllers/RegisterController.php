<?php

namespace Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Models\User;
use Controllers\Controller;

class RegisterController extends Controller
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
        unset($_SESSION['alertMessage']);

        $template = $twig->load('/pages/user/register.html.twig');
        echo $template->render(['alertMessage' => $alertMessage]);
    }

    function register()
    {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password != $confirmPassword) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                Les mots de passe ne correspondent pas !
            </div>
            ';

            self::index();
            exit();
        } else if ($this->user->getUser($email)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                Un compte existe déjà avec cette adresse email !
            </div>
            ';

            self::index();
            exit();
        } else if (strlen($password) < 8) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                Le mot de passe doit contenir au moins 8 caractères !
            </div>
            ';

            self::index();
            exit();
        } else if (!preg_match('/[A-Z]/', $password)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                Le mot de passe doit contenir au moins une majuscule !
            </div>
            ';

            self::index();
            exit();
        } else if (!preg_match('/[0-9]/', $password)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                Le mot de passe doit contenir au moins un chiffre !
            </div>
            ';

            self::index();
            exit();
        } else if (!preg_match('/[a-z]/', $password)) {
            $_SESSION['alertMessage'] = '
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                Le mot de passe doit contenir au moins une minuscule !
            </div>
            ';

            self::index();
            exit();
        } else {
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);

            $this->user->createUser($lastname, $firstname, $phone, $email, $hashPassword, $address, $isAdmin = 0);

            $_SESSION['alertMessage'] = '
            <div class="alert alert-success d-flex align-items-center" role="alert">
                Votre compte a été créé avec succès, connectez vous dès maintenant !
            </div>
            ';

            header('Location: /login');
            exit();
        }
    }
}
