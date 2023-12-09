<?php

namespace Models;

use Models\Model;

class User extends Model
{
    public function createUser($lastname, $firstname, $phone, $email, $password, $address = NULL, $isAdmin  = 0)
    {
        $query = $this->db->prepare('INSERT INTO user (lastname, firstname, phone, email, password, address, isAdmin) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $query->execute([$lastname, $firstname, $phone, $email, $password, $address, $isAdmin]);
    }

    public function getUsers()
    {
        $query = $this->db->query('SELECT * FROM user ORDER BY id ASC');
        return $query->fetchAll();
    }

    public function getUser($email)
    {
        $query = $this->db->prepare('SELECT * FROM user WHERE email = ?');
        $query->execute([$email]);
        return $query->fetch();
    }

    public function getUserById($id)
    {
        $query = $this->db->prepare('SELECT * FROM user WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function getUserByEmail($email)
    {
        $query = $this->db->prepare('SELECT * FROM user WHERE email = ?');
        $query->execute([$email]);
        return $query->fetch();
    }

    public function getUserEmailById($id)
    {
        $query = $this->db->prepare('SELECT email FROM user WHERE id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function updateUser($id, $lastname, $firstname, $phone, $email, $address, $password, $isAdmin = 0)
    {
        if ($password == '') {
            $query = $this->db->prepare('UPDATE user SET lastname = ?, firstname = ?, phone = ?, email = ?, address = ? , isAdmin = ? WHERE id = ?');
            $query->execute([$lastname, $firstname, $phone, $email, $address, $isAdmin, $id]);
            return;
        } else {
            $query = $this->db->prepare('UPDATE user SET lastname = ?, firstname = ?, phone = ?, email = ?, address = ?,  password = ?, isAdmin = ? WHERE id = ?');
            $query->execute([$lastname, $firstname, $phone, $email, $address, $password, $isAdmin, $id]);
        }
    }

    public function deleteUser($id)
    {
        $query = $this->db->prepare('DELETE FROM user WHERE id = ?');
        $query->execute([$id]);
    }
}
