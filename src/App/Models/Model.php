<?php

namespace Models;

class Model
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function __destruct()
    {
        $this->db = null;
    }
}
