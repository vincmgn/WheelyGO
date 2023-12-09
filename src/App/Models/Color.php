<?php

namespace Models;

use Models\Model;

class Color extends Model
{
    public function createColor($color)
    {
        $query = $this->db->prepare('INSERT INTO color (color) VALUES (?)');
        $query->execute([$color]);
    }

    public function getColor($color)
    {
        $query = $this->db->prepare('SELECT * FROM color WHERE color = ?');
        $query->execute([$color]);
        return $query->fetch();
    }

    public function getColors()
    {
        $query = $this->db->prepare('SELECT * FROM color ORDER BY id ASC');
        $query->execute();
        return $query->fetchAll();
    }

    public function getColorIdByName($color)
    {
        $query = $this->db->prepare('SELECT id FROM color WHERE color = ?');
        $query->execute([$color]);
        return $query->fetch();
    }

    public function getColorById($id)
    {
        $query = $this->db->prepare('SELECT * FROM color WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function updateColor($id, $color)
    {
        $query = $this->db->prepare('UPDATE color SET color = ? WHERE id = ?');
        $query->execute([$color, $id]);
    }

    public function deleteColor($id)
    {
        $query = $this->db->prepare('DELETE FROM color WHERE id = ?');
        $query->execute([$id]);
    }
}
