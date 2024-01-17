<?php

class Product {
    private $id;
    private $manufacture;
    private $model;
    private $price;
    private $photo;
    private $quantity;

    public function __construct($id, $manufacture, $model, $price, $photo, $quantity = 0) {
        $this->id = $id;
        $this->manufacture = $manufacture;
        $this->model = $model;
        $this->price = $price;
        $this->photo = $photo;
        $this->quantity = $quantity;
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCategoryId(){
        return $this->category_id;
    }

    public function setCategoryId($category_id){
        $this->category_id = $category_id;
    }

    public function getManufacture() {
        return $this->manufacture;
    }

    public function setManufacture($manufacture) {
        $this->manufacture = $manufacture;
    }

    public function getModel() {
        return $this->model;
    }

    public function setModel($model) {
        $this->model = $model;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function getPhoto() {
        return $this->photo;
    }

    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}
