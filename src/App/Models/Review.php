<?php

namespace Models;

use Models\Model;

class Review extends Model
{
    public function createReview($user_id, $vehicle_id, $review, $stars)
    {
        $query = $this->db->prepare('INSERT INTO review (user_id, vehicle_id, review, stars) VALUES (?, ?, ?, ?)');
        $query->execute([$user_id, $vehicle_id, $review, $stars]);
    }

    public function getReviews()
    {
        $query = $this->db->prepare('SELECT * FROM review ORDER BY id ASC');
        $query->execute();
        return $query->fetchAll();
    }

    public function getReviewsWithAllInfo()
    {
        $query = $this->db->prepare('
        SELECT review.*, user.firstname AS firstname, user.lastname AS lastname, vehicle.name AS vehicle_name
        FROM review 
        JOIN user ON review.user_id = user.id
        JOIN vehicle ON review.vehicle_id = vehicle.id
        ORDER BY id ASC
        ');
        $query->execute();
        return $query->fetchAll();
    }

    public function getAllReviewWithALlInfoByUserId($user_id)
    {
        $query = $this->db->prepare('
            SELECT review.*, vehicle.name AS vehicle_name, vehicle.image_path AS vehicle_image_path, vehicle.price AS vehicle_price, vehicle.localisation AS vehicle_localisation, brand.brand AS vehicle_brand, color.color AS vehicle_color, number_place.numberPlace AS vehicle_number_place
            FROM review 
            JOIN vehicle ON review.vehicle_id = vehicle.id
            JOIN brand ON vehicle.brand_id = brand.id
            JOIN color ON vehicle.color_id = color.id
            JOIN number_place ON vehicle.number_place_id = number_place.id
            WHERE review.user_id = ?
        ');
        $query->execute([$user_id]);
        return $query->fetchAll();
    }

    public function getReviewByUser($user_id)
    {
        $query = $this->db->prepare('SELECT * FROM review WHERE user_id = ?');
        $query->execute([$user_id]);
        return $query->fetchAll();
    }

    public function getReviewByVehicle($vehicle_id)
    {
        $query = $this->db->prepare('SELECT * FROM review WHERE vehicle_id = ?');
        $query->execute([$vehicle_id]);
        return $query->fetchAll();
    }

    public function getReviewByVehicleAndUser($vehicle_id, $user_id)
    {
        $query = $this->db->prepare('SELECT * FROM review WHERE vehicle_id = ? AND user_id = ?');
        $query->execute([$vehicle_id, $user_id]);
        return $query->fetchAll();
    }

    public function updateReview($id, $review, $stars)
    {
        $query = $this->db->prepare('UPDATE review SET review = ?, stars = ? WHERE id = ?');
        $query->execute([$review, $stars, $id]);
    }

    public function deleteReview($id)
    {
        $query = $this->db->prepare('DELETE FROM review WHERE id = ?');
        $query->execute([$id]);
    }
}
