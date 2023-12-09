<?php

namespace Models;

use Models\Model;

class Reservation extends Model
{
    public function createReservation($price, $user_id, $vehicle_id, $start_date, $end_date)
    {
        $query = $this->db->prepare('INSERT INTO reservation (price, user_id, vehicle_id, start_date, end_date) VALUES (?, ?, ?, ?, ?)');
        $query->execute([$price, $user_id, $vehicle_id, $start_date, $end_date]);
    }

    public function getReservationById($id)
    {
        $query = $this->db->prepare('SELECT * FROM reservation WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function getReservationByUserId($user_id)
    {
        $query = $this->db->prepare('SELECT * FROM reservation WHERE user_id = ?');
        $query->execute([$user_id]);
        return $query->fetchAll();
    }

    public function getReservationByVehicleId($vehicle_id)
    {
        $query = $this->db->prepare('SELECT * FROM reservation WHERE vehicle_id = ?');
        $query->execute([$vehicle_id]);
        return $query->fetchAll();
    }

    public function getAllReservationWithAllInfoByUserId($user_id)
    {
        $query = $this->db->prepare('
            SELECT reservation.*, vehicle.name AS vehicle_name, vehicle.image_path AS vehicle_image_path, vehicle.price AS vehicle_price, vehicle.localisation AS vehicle_localisation, brand.brand AS vehicle_brand, color.color AS vehicle_color, number_place.numberPlace AS vehicle_number_place
            FROM reservation 
            JOIN vehicle ON reservation.vehicle_id = vehicle.id
            JOIN brand ON vehicle.brand_id = brand.id
            JOIN color ON vehicle.color_id = color.id
            JOIN number_place ON vehicle.number_place_id = number_place.id
            WHERE reservation.user_id = ?
        ');
        $query->execute([$user_id]);
        return $query->fetchAll();
    }

    public function updateReservation($id, $price, $user_id, $vehicle_id, $start_date, $end_date)
    {
        $query = $this->db->prepare('UPDATE reservation SET price = ?, user_id = ?, vehicle_id = ?, start_date = ?, end_date = ? WHERE id = ?');
        $query->execute([$price, $user_id, $vehicle_id, $start_date, $end_date, $id]);
    }

    public function deleteReservation($id)
    {
        $query = $this->db->prepare('DELETE FROM reservation WHERE id = ?');
        $query->execute([$id]);
    }
}
