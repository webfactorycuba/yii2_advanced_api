<?php

/**
 * @SWG\Definition(
 *     definition="ErrorValidate",
 *     required={"statusCode", "message", "name", "errors"},
 *     type="object",
 *     @SWG\Schema(
 *       @SWG\Property(property="statusCode", type="string", description="Name App"),
 *       @SWG\Property(property="message", type="string", description="Errors message"),
 *       @SWG\Property(property="name", type="string", description="Errors name"),
 *       @SWG\Property(property="errors", type="object", description="Errors Detail")
 *     )
 * )
 *
 * @SWG\Definition(
 *     definition="Unauthorized",
 *     required={"statusCode", "message", "name"},
 *     type="object",
 *     @SWG\Schema(
 *       @SWG\Property(property="statusCode", type="string", description="Name App"),
 *       @SWG\Property(property="message", type="string", description="Errors message"),
 *       @SWG\Property(property="name", type="string", description="Errors name"),
 *     )
 * )
 *
 * @SWG\Definition(
 *     definition="Not Found",
 *     required={"statusCode", "message", "name"},
 *     type="object",
 *     @SWG\Schema(
 *       @SWG\Property(property="statusCode", type="string", description="Name App"),
 *       @SWG\Property(property="message", type="string", description="Errors message"),
 *       @SWG\Property(property="name", type="string", description="Errors name"),
 *     )
 * )
 */

return [
    'adminEmail' => 'egutierrezr421@gmail.com',
	'languages' => [
		'en' => 'EN',
		'es' => 'ES',
	],
];
