<?php

namespace Controllers;

class Controller
{
    protected $db; // pour les nenfants

    public function __construct($db)
    {
        $this->db = $db;
    }
}
