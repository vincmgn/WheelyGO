<?php

namespace Core;

class InitDatabase
{

    // --- User table ---//
    static function createUserTable($db)
    {
        $db->exec("
        CREATE TABLE IF NOT EXISTS user (
            id INT PRIMARY KEY AUTO_INCREMENT,
            lastname VARCHAR(255) NOT NULL,
            firstname VARCHAR(255) NOT NULL,
            phone VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            address VARCHAR(255),
            isAdmin BOOLEAN NOT NULL DEFAULT FALSE
        )
    ");
    }

    static function createBrandTable($db)
    {
        $db->exec("
        CREATE TABLE IF NOT EXISTS brand (
            id INT PRIMARY KEY AUTO_INCREMENT,
            brand VARCHAR(255) UNIQUE NOT NULL
        )
    ");
    }

    static function createNumberPlaceTable($db)
    {
        $db->exec("
        CREATE TABLE IF NOT EXISTS number_place (
            id INT PRIMARY KEY AUTO_INCREMENT,
            numberPlace INT UNIQUE NOT NULL
        )
    ");
    }

    static function createColorTable($db)
    {
        $db->exec("
        CREATE TABLE IF NOT EXISTS color (
            id INT PRIMARY KEY AUTO_INCREMENT,
            color VARCHAR(255) UNIQUE NOT NULL
        )
    ");
    }

    // --- Vehicle table ---//
    static function createVehicleTable($db)
    {
        $db->exec("
        CREATE TABLE IF NOT EXISTS vehicle (
            id INT PRIMARY KEY AUTO_INCREMENT,
            image_path VARCHAR(255),
            name VARCHAR(255) NOT NULL,
            localisation VARCHAR(255) NOT NULL,
            brand_id INT,
            number_place_id INT,
            color_id INT,
            price INT,
            FOREIGN KEY (brand_id) REFERENCES brand(id) ON DELETE SET NULL,
            FOREIGN KEY (number_place_id) REFERENCES number_place(id) ON DELETE SET NULL,
            FOREIGN KEY (color_id) REFERENCES color(id) ON DELETE SET NULL
        )
    ");
    }

    // --- Reservation table ---//
    static function createReservationTable($db)
    {
        $db->exec("
        CREATE TABLE IF NOT EXISTS reservation (
            id INT PRIMARY KEY AUTO_INCREMENT,
            price INT NOT NULL,
            user_id INT NOT NULL,
            vehicle_id INT NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
            FOREIGN KEY (vehicle_id) REFERENCES vehicle(id) ON DELETE CASCADE
        )
    ");
    }

    // --- Favorite --- //
    static function createFavoriteTable($db)
    {
        $db->exec("
        CREATE TABLE IF NOT EXISTS favorite (
            user_id INT NOT NULL,
            vehicle_id INT NOT NULL,
            PRIMARY KEY (user_id, vehicle_id)
        )
    ");
    }

    // --- Review --- //
    static function createReviewTable($db)
    {
        $db->exec("
        CREATE TABLE IF NOT EXISTS review (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            vehicle_id INT NOT NULL,
            review VARCHAR(255) NOT NULL,
            stars INT NOT NULL,
            FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE,
            FOREIGN KEY (vehicle_id) REFERENCES vehicle(id) ON DELETE CASCADE
        )
    ");
    }

    public static function Init($db)
    {
        self::createUserTable($db);
        self::createBrandTable($db);
        self::createNumberPlaceTable($db);
        self::createColorTable($db);
        self::createVehicleTable($db);
        self::createReservationTable($db);
        self::createFavoriteTable($db);
        self::createReviewTable($db);
    }
}
