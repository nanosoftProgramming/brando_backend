<?php

namespace Modules\Branch\DTO;

class BranchDto
{
    public $name;

    public $phone;

    public $restaurant_id;

    public $city_id;

    public $latitude;

    public $longitude;

    public $block;

    public $street;

    public $building_number;

    public $notes;

    public function __construct($request)
    {
        if ($request->get('name')) {
            $this->name = $request->get('name');
        }
        if ($request->get('phone')) {
            $this->phone = $request->get('phone');
        }

        if ($request->get('restaurant_id')) {
            $this->restaurant_id = $request->get('restaurant_id');
        }

        if ($request->get('city_id')) {
            $this->city_id = $request->get('city_id');
        }

        if ($request->get('latitude')) {
            $this->latitude = $request->get('latitude');
        }

        if ($request->get('longitude')) {
            $this->longitude = $request->get('longitude');
        }

        if ($request->get('block')) {
            $this->block = $request->get('block');
        }

        if ($request->get('street')) {
            $this->street = $request->get('street');
        }

        if ($request->get('building_number')) {
            $this->building_number = $request->get('building_number');
        }

        if ($request->get('notes')) {
            $this->notes = $request->get('notes');
        }
    }

    public function dataFromRequest()
    {
        $data = json_decode(json_encode($this), true);

        if ($this->name == null) {
            unset($data['name']);
        }

        if ($this->phone == null) {
            unset($data['phone']);
        }

        if ($this->restaurant_id == null) {
            unset($data['restaurant_id']);
        }

        if ($this->city_id == null) {
            unset($data['city_id']);
        }

        if ($this->latitude == null) {
            unset($data['latitude']);
        }

        if ($this->longitude == null) {
            unset($data['longitude']);
        }

        if ($this->block == null) {
            unset($data['block']);
        }

        if ($this->street == null) {
            unset($data['street']);
        }

        if ($this->building_number == null) {
            unset($data['building_number']);
        }

        if ($this->notes == null) {
            unset($data['notes']);
        }

        return $data;
    }
}
