<?php

namespace Models;

use Models\Model;

class Favorite extends Model
{
    public function createFavorite($user_id, $vehicle_id)
    {
        $query = $this->db->prepare('INSERT INTO favorite (user_id, vehicle_id) VALUES (?, ?)');
        $query->execute([$user_id, $vehicle_id]);
    }

    public function getFavoriteByUser($user_id)
    {
        $query = $this->db->prepare('SELECT * FROM favorite WHERE user_id = ?');
        $query->execute([$user_id]);
        return $query->fetchAll();
    }

    public function getAllFavoriteWithAllInfoByUserId($user_id)
    {
        $query = $this->db->prepare('
            SELECT favorite.*, vehicle.name AS vehicle_name, vehicle.image_path AS vehicle_image_path, vehicle.price AS vehicle_price, vehicle.localisation AS vehicle_localisation, brand.brand AS vehicle_brand, color.color AS vehicle_color, number_place.numberPlace AS vehicle_number_place
            FROM favorite 
            LEFT JOIN vehicle ON favorite.vehicle_id = vehicle.id
            LEFT JOIN brand ON vehicle.brand_id = brand.id
            LEFT JOIN color ON vehicle.color_id = color.id
            LEFT JOIN number_place ON vehicle.number_place_id = number_place.id
            WHERE favorite.user_id = ?
        ');
        $query->execute([$user_id]);
        return $query->fetchAll();
    }

    public function getFavoriteByUserAndVehicle($user_id, $vehicle_id)
    {
        $query = $this->db->prepare('SELECT * FROM favorite WHERE user_id = ? AND vehicle_id = ?');
        $query->execute([$user_id, $vehicle_id]);
        return $query->fetch();
    }

    public function deleteFavorite($user_id, $vehicle_id)
    {
        $query = $this->db->prepare('DELETE FROM favorite WHERE user_id = ? AND vehicle_id = ?');
        $query->execute([$user_id, $vehicle_id]);
    }
}
