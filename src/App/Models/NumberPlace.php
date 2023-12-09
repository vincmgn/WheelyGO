<?php

namespace Models;

use Models\Model;

class NumberPlace extends Model
{
    public function createNumberPlace($numberPlace)
    {
        $query = $this->db->prepare('INSERT INTO number_place (numberPlace) VALUES (?)');
        $query->execute([$numberPlace]);
    }

    public function getNumberPlace($numberPlace)
    {
        $query = $this->db->prepare('SELECT * FROM number_place WHERE numberPlace = ?');
        $query->execute([$numberPlace]);
        return $query->fetch();
    }

    public function getNumberPlaceIdByName($numberPlace)
    {
        $query = $this->db->prepare('SELECT id FROM number_place WHERE numberPlace = ?');
        $query->execute([$numberPlace]);
        return $query->fetch();
    }

    public function getNumberPlaceById($id)
    {
        $query = $this->db->prepare('SELECT * FROM number_place WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function getNumbersPlace()
    {
        $query = $this->db->prepare('SELECT * FROM number_place ORDER BY id ASC');
        $query->execute();
        return $query->fetchAll();
    }

    public function updateNumberPlace($id, $numberPlace)
    {
        $query = $this->db->prepare('UPDATE number_place SET numberPlace = ? WHERE id = ?');
        $query->execute([$numberPlace, $id]);
    }

    public function deleteNumberPlace($id)
    {
        $query = $this->db->prepare('DELETE FROM number_place WHERE id = ?');
        $query->execute([$id]);
    }
}
