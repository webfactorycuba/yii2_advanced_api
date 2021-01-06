<?php

/**
 * @SWG\Parameter(
 *      description="ID of data",
 *      format="int64",
 *      in="path",
 *      name="id",
 *      required=true,
 *      type="integer"
 * )
 */

$apiV1 = "../routes/v1";

$routes = [];
foreach (glob("{$apiV1}/*.php") as $filename) {
    $route = require($filename);
    $routes = array_merge($routes, $route);
}

return $routes;