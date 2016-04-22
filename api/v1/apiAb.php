<?php
abstract class apiAb{

	/**
	* Property: method
	* The HTTP method this request was made in, either GET, POST, PUT, DELETE
	**/
	protected $method = '';

	/**
	* Property: endpoint
	* The Model requested in the URI. eg: /files
	**/
	protected $endpoint = '';

	/**
	* Property: verb
	* An optional additional descriptor about the endpoint, used for things that can
	* not be handled by basic methods. eg: /files/process
	**/
	protected $verb = '';

	/**
	* Property: args
	* Any additional URI component after the endpoint and verb have been removed, in our
	* case, an integer ID for the resource. eg: /<endpoint>/<verb>/<arg0>/<arg1>
	* or /<endpoint>/<arg0>
	**/
	protected $args = Array();

	/**
	* Property: file
	* Stores the input of the PUT request
	**/
	protected $file = NULL;

	/**
	* Property: file
	* Stores the input of the PUT request
	**/
	protected $file = NULL;

}
?>