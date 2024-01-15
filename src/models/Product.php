<?php

class Product {
    private $id;
    private $manufacture;
    private $model;
    private $price;
    private $photo;  // New private property for photo
    private $quantity;
    private $category_id;

    // Konstruktor
    public function __construct($id, $manufacture, $model, $price, $photo, $quantity = 0) {
        $this->id = $id;
        $this->manufacture = $manufacture;
        $this->model = $model;
        $this->price = $price;
        $this->photo = $photo;
        $this->quantity = $quantity;
    }

    // Getter dla id
    public function getId() {
        return $this->id;
    }

    // Setter dla id
    public function setId($id) {
        $this->id = $id;
    }

    public function getCategoryId(){
        return $this->category_id;
    }

    public function setCategoryId($category_id){
        $this->category_id = $category_id;
    }

    // Getter dla manufacture
    public function getManufacture() {
        return $this->manufacture;
    }

    // Setter dla manufacture
    public function setManufacture($manufacture) {
        $this->manufacture = $manufacture;
    }

    // Getter dla model
    public function getModel() {
        return $this->model;
    }

    // Setter dla model
    public function setModel($model) {
        $this->model = $model;
    }

    // Getter dla price
    public function getPrice() {
        return $this->price;
    }

    // Setter dla price
    public function setPrice($price) {
        $this->price = $price;
    }

    // Getter dla photo
    public function getPhoto() {
        return $this->photo;
    }

    // Setter dla photo
    public function setPhoto($photo) {
        $this->photo = $photo;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    // Setter dla quantity
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
}
