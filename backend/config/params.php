<?php

/**
 * @SWG\Definition(
 *     definition="ErrorValidate",
 *     required={"name", "message", "code", "status", "type", "errors"},
 *     type="object",
 *     @SWG\Schema(
 *       @SWG\Property(property="name", type="string", default="Error", description="Action result name"),
 *       @SWG\Property(property="message", type="string", default="Query success with errors", description="Errors message"),
 *       @SWG\Property(property="code", type="integer", default=0, description="Code value"),
 *       @SWG\Property(property="status", type="integer", default=204, description="Http Response Status"),
 *       @SWG\Property(property="type", type="string", description="Query type"),
 *       @SWG\Property(property="errors", type="object", description="Errors Detail")
 *     )
 * )
 *
 * @SWG\Definition(
 *     definition="Unauthorized",
 *     required={"name", "message", "code", "status", "type", "data"},
 *     type="object",
 *     @SWG\Schema(
 *       @SWG\Property(property="name", type="string", default="Unauthorized", description="Action result name"),
 *       @SWG\Property(property="message", type="string", default="You can not access this item", description="Forbidden message"),
 *       @SWG\Property(property="code", type="integer", default=0, description="Code value"),
 *       @SWG\Property(property="status", type="integer", default=401, description="Http Response Status"),
 *       @SWG\Property(property="type", type="string", description="Query type"),
 *       @SWG\Property(property="data", type="object", description="Errors Detail")
 *     )
 * )
 *
 * @SWG\Definition(
 *     definition="Not Found",
 *     required={"name", "message", "code", "status", "type", "data"},
 *     type="object",
 *     @SWG\Schema(
 *       @SWG\Property(property="name", type="string", default="Not Found", description="Action result name"),
 *       @SWG\Property(property="message", type="string", default="Item not found", description="Not found message"),
 *       @SWG\Property(property="code", type="integer", default=0, description="Code value"),
 *       @SWG\Property(property="status", type="integer", default=404, description="Http Response Status"),
 *       @SWG\Property(property="type", type="string", description="Query type"),
 *       @SWG\Property(property="data", type="object", description="Errors Detail")
 *     )
 * )
 *
 * @SWG\Definition(
 *     definition="Success",
 *     type="object",
 *     required={"name", "message", "code", "status", "type"},
 *       @SWG\Property(property="name", type="string", default="Success", description="Action result name"),
 *       @SWG\Property(property="message", type="string", default="Query success", description="Success message"),
 *       @SWG\Property(property="code", type="integer", default=0, description="Code value"),
 *       @SWG\Property(property="status", type="integer", default=200, description="Http Response Status (200, 201)"),
 *       @SWG\Property(property="type", type="string", description="Query type")
 * )
 *
 */

return [
    'adminEmail' => 'egutierrezr421@gmail.com',
	'languages' => [
		'en' => 'EN',
		'es' => 'ES',
	],
];
