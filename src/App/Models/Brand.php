<?php

namespace Models;

use Models\Model;

class Brand extends Model
{
    public function createBrand($brand)
    {
        $query = $this->db->prepare('INSERT INTO brand (brand) VALUES (?)');
        $query->execute([$brand]);
    }

    public function getBrands()
    {
        $query = $this->db->prepare('SELECT * FROM brand ORDER BY id ASC');
        $query->execute();
        return $query->fetchAll();
    }

    public function getBrand($brand)
    {
        $query = $this->db->prepare('SELECT * FROM brand WHERE brand = ?');
        $query->execute([$brand]);
        return $query->fetch();
    }

    public function getBrandIdByName($brand)
    {
        $query = $this->db->prepare('SELECT id FROM brand WHERE brand = ?');
        $query->execute([$brand]);
        return $query->fetch();
    }

    public function getBrandById($id)
    {
        $query = $this->db->prepare('SELECT * FROM brand WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function updateBrand($id, $brand)
    {
        $query = $this->db->prepare('UPDATE brand SET brand = ? WHERE id = ?');
        $query->execute([$brand, $id]);
    }

    public function deleteBrand($id)
    {
        $query = $this->db->prepare('DELETE FROM brand WHERE id = ?');
        $query->execute([$id]);
    }
}
