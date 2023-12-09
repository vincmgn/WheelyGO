<?php

namespace Models;

use Models\Model;

class Vehicle extends Model
{

    public function createVehicle($image_path, $name, $localisation, $brand_id, $number_place_id, $color_id, $price)
    {
        $query = $this->db->prepare('INSERT INTO vehicle (image_path, name, localisation, brand_id, number_place_id, color_id, price) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $query->execute([$image_path, $name, $localisation, $brand_id, $number_place_id, $color_id, $price]);
    }

    public function getVehicles()
    {
        $query = $this->db->prepare('SELECT * FROM vehicle ORDER BY id ASC');
        $query->execute();
        return $query->fetchAll();
    }

    public function getVehicleById($id)
    {
        $query = $this->db->prepare('SELECT * FROM vehicle WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function getVehiculeWithAllInfo($id)
    {
        $query = $this->db->prepare('
            SELECT vehicle.*, brand.brand AS brand, color.color AS color, number_place.numberPlace AS number_place
            FROM vehicle 
            LEFT JOIN brand ON vehicle.brand_id = brand.id
            LEFT JOIN color ON vehicle.color_id = color.id
            LEFT JOIN number_place ON vehicle.number_place_id = number_place.id
            WHERE vehicle.id = ?
        ');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function getVehiculesWithAllInfo()
    {
        $query = $this->db->prepare('
            SELECT vehicle.*, brand.brand AS brand, color.color AS color, number_place.numberPlace AS number_place
            FROM vehicle 
            LEFT JOIN brand ON vehicle.brand_id = brand.id
            LEFT JOIN color ON vehicle.color_id = color.id
            LEFT JOIN number_place ON vehicle.number_place_id = number_place.id
            ORDER BY id ASC
        ');
        $query->execute();
        return $query->fetchAll();
    }

    public function searchVehicles($research)
    {
        $query = $this->db->prepare('
            SELECT vehicle.*, brand.brand AS brand, color.color AS color, number_place.numberPlace AS number_place
            FROM vehicle 
            LEFT JOIN brand ON vehicle.brand_id = brand.id
            LEFT JOIN color ON vehicle.color_id = color.id
            LEFT JOIN number_place ON vehicle.number_place_id = number_place.id
            WHERE vehicle.name LIKE ? OR color.color LIKE ? OR brand.brand LIKE ? OR number_place.numberPlace LIKE ?
            ORDER BY id ASC
        ');

        $query->execute(["%$research%", "%$research%", "%$research%", "%$research%"]);
        return $query->fetchAll();
    }

    public function getMaxAndMinPrice()
    {
        $query = $this->db->prepare('
            SELECT MAX(v.price) AS MaxPrice, MIN(v.price) AS MinPrice
            FROM vehicle v
        ');

        $query->execute();
        return $query->fetchAll();
    }

    public function getReviews($id)
    {
        $query = $this->db->prepare('
            SELECT review.*, user.firstname AS firstname, user.lastname AS lastname
            FROM review
            JOIN user ON review.user_id = user.id
            WHERE review.vehicle_id = ?
        ');

        $query->execute([$id]);
        return $query->fetchAll();
    }

    public function isCityExist($city)
    {
        $query = $this->db->prepare('SELECT * FROM vehicle WHERE localisation = ?');
        $query->execute([$city]);
        if ($query->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getVehiclesByResearch($researchCity, $selectedBrands, $selectedNumberPlaces, $selectedColors, $maxPrice)
    {
        // research city
        $researchCity = $researchCity . '%';

        // selected in string (array to string)
        $brandString = implode("', '", $selectedBrands);
        $numberPlaceString = implode("', '", $selectedNumberPlaces);
        $colorString = implode("', '", $selectedColors);

        // add quotes
        $brandString = "'" . $brandString . "'";
        $numberPlaceString = "'" . $numberPlaceString . "'";
        $colorString = "'" . $colorString . "'";


        $query = $this->db->prepare('
            SELECT vehicle.*, brand.brand AS brand, color.color AS color, number_place.numberPlace AS number_place
            FROM vehicle

            JOIN brand ON vehicle.brand_id = brand.id
            JOIN color ON vehicle.color_id = color.id
            JOIN number_place ON vehicle.number_place_id = number_place.id

            WHERE vehicle.localisation LIKE ?
            AND brand.brand IN (' . $brandString . ')
            AND number_place.numberPlace IN (' . $numberPlaceString . ')
            AND color.color IN (' . $colorString . ')
            AND vehicle.price <= ?
            ORDER BY id ASC            
        ');

        $query->execute([$researchCity, $maxPrice]);
        return $query->fetchAll();
    }



    public function updateVehicle($id, $name, $image_path, $localisation, $brand_id, $number_place_id, $color_id, $price)
    {
        $query = $this->db->prepare('UPDATE vehicle SET name = ?, image_path = ?, localisation = ?, brand_id = ?, number_place_id = ?, color_id = ?, price = ? WHERE id = ?');
        $query->execute([$name, $image_path, $localisation, $brand_id, $number_place_id, $color_id, $price, $id]);
    }

    public function deleteVehicle($id)
    {
        $query = $this->db->prepare('DELETE FROM vehicle WHERE id = ?');
        $query->execute([$id]);
    }
}
