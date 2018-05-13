<?php

namespace CarApi\Entities;

use CarApi\Entities\RemoteApi;

class DataSource
{
    private static $carsTree;
    private static $makes = [];
    private static $models = [];
    private static $trims = [];
    private static $cars = [];
    private static $csvFile = null;

    private static function insertCarInTree($row) {

        list($make, $model, $trim, $year, $tco) = $row;

        if (!isset(self::$carsTree[$make])) {
            self::$carsTree[$make] = array();
            array_push(self::$makes, new Make(["name" => $make]));
        }
        if (!isset(self::$carsTree[$make][$model])) {
            self::$carsTree[$make][$model] = array();
            array_push(self::$models, new Model(["name" => $model]));
        }
        if (!isset(self::$carsTree[$make][$model][$trim])) {
            self::$carsTree[$make][$model][$trim] = array();
            array_push(self::$trims, new Trim(["name" => $trim]));
        }
        $car = new Car([
            "make" => $make,
            "model" => $model,
            "trim" => $trim,
            "year" => $year,
            "tco" => $tco
        ]);

        array_push(self::$cars, $car);

        self::$carsTree[$make][$model][$trim]["year"] = $year;
        self::$carsTree[$make][$model][$trim]["tco"] = $tco;
        self::$carsTree[$make][$model][$trim]["carReference"] = $car;

    }

    public static function init()
    {
        // Import csv data
        $cwd = getcwd();

        if (!self::$csvFile) {

            self::$csvFile = fopen('tco-data.csv', 'r');
            $rowIndex = 0;

            while (($row = fgetcsv(self::$csvFile)) !== FALSE) {

                // We skip title line
                if ($rowIndex++ === 0) continue;

                // We build a tree and populate it with csv data
                self::insertCarInTree($row);
            }
            fclose(self::$csvFile);
        }

        // Import some data from CarQueryApi
        $trims = RemoteApi::trimsForMake("peugeot");

        foreach ($trims as $trim) {
            $row = [
                $trim->make_display,
                $trim->model_name,
                $trim->model_trim,
                $trim->model_year,
                null
            ];
            self::insertCarInTree($row);
        }
    }

    public static function findMakes($args)
    {
        $manufacturers = self::$makes;

        $startWith = isset($args["startingWith"]) ? $args["startingWith"] : false;

        if ($startWith) {
            $manufacturers = array_filter(
                $manufacturers,
                function($value) use ($startWith) {
                   return (strpos(strtolower($value->name), strtolower($startWith)) === 0);
                });
        }
        return $manufacturers;
    }

    public static function findCars($args)
    {
        $cars = self::$cars;

        $tcoBelow = isset($args["tcoBelow"]) ? $args["tcoBelow"] : false;
        $tcoAbove = isset($args["tcoAbove"]) ? $args["tcoAbove"] : false;
        $make = isset($args["make"]) ? $args["make"] : false;

        if ($tcoBelow) {
            $cars = array_filter(
                $cars,
                function($value) use ($tcoBelow) {
                    return ($value->tco < $tcoBelow);
                });
        }

        if ($tcoAbove) {
            $cars = array_filter(
                $cars,
                function($value) use ($tcoAbove) {
                    return ($value->tco >= $tcoAbove);
                });
        }

        if ($make) {
            $cars = array_filter(
                $cars,
                function($value) use ($make) {
                    return ($value->make == $make);
                });
        }

        return $cars;

    }
}