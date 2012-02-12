<?php

/*-- Security Protocols --*/
if (!defined("WHMCS")) die("This file cannot be accessed directly");
/*-- Security Protocols --*/


/**
 * IntObject class
 * @version		1.0.0
 * 
 * @since		1.0.0
 * @author		Steven
 */
class TplObject
{
	/**
	 * Stores any errors generated here
	 * @access		protected
	 * @var			array
	 * @since		1.0.0
	 */
	protected $_errors = array();
	
	
	/**
	 * Stores any undeclared properties
	 * @access		protected
	 * @var			array
	 * @since		1.0.0
	 */
	protected $_properties	= array();
	
	
	/**
	 * Constructor method
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @since		1.0.0
	 */
	public function __construct()
	{
		
	}
	
	
	/**
	 * Method to convert object to string
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	public function __toString()
	{
		return get_class($this);
	}
	
	
	/**
	 * Define a property of the object if not already set
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $property: Contains the property to define
	 * @param		string		- $default: Contains the default value or null if not provided
	 * 
	 * @return		returns the value of the property
	 * @since		1.0.0
	 */
	public function def( $property, $default = null )
	{
		$value = $this->get( $property, $default );
		return $this->set( $property, $value );
	}
	
	
	/**
	 * Get a property from the object
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $property: Contains the property to retrieve
	 * @param		varies		- $default: Contains a default value or null if not provided
	 * 
	 * @return		Returns value of property or value of default if not set
	 * @since		1.0.0
	 */
	public function get($property, $default=null)
	{
		if ( isset( $this->$property ) ) {
			return $this->$property;
		}
		return $default;
	}
	
	
	/**
	 * Get all the properties from the object
	 * @access		public
	 * @version		1.0.0
	 * @param		boolean		- $public: If true returns only public properties (those not preceeded with "_")
	 * 
	 * @return		An array of the objects properties
	 * @since		1.0.0
	 */
	public function getProperties( $public = true )
	{
		$vars  = get_object_vars($this);

        if ( $public ) {
			foreach ( $vars as $key => $value ) {
				if ( '_' == substr( $key, 0, 1 ) ) {
					unset( $vars[$key] );
				}
			}
		}

        return $vars;
	}
	
	
	/**
	 * Get a specific error from the error stack
	 * @access		public
	 * @version		1.0.0
	 * @param		integer		- $i: If provided contains the selected error from the error stack to retrieve
	 * 
	 * @return		string containing the error message
	 * @since		1.0.0
	 */
	public function getError($i = null )
	{
		// Find the error
		if ( $i === null) {
			// Default, return the last message
			$error = end($this->_errors);
		}
		else
		if ( ! array_key_exists( $i, $this->_errors ) ) {
			// If $i has been specified but does not exist, return false
			return false;
		}
		else {
			$error	= $this->_errors[$i];
		}
		
		return $error;
	}
	
	
	/**
	 * Gets the entire error stack
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		array containing any errors
	 * @since		1.0.0
	 */
	public function getErrors()
	{
		return $this->_errors;
	}
	
	
	/**
	 * Checks to see if the object has thrown an error
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		boolean true if object has errors
	 * @since		1.0.0
	 */
	public function hasErrors()
	{
		$data	= false;
		$errors = $this->getErrors();
		
		foreach ( $errors as $error ) {
			$data = true;
			break;
		}
		return $data;
	}
	
	
	/**
	 * Checks to see if we are logged in as administrator
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		boolean
	 * @since		1.0.0
	 */
	public function is_admin()
	{
		return ( defined( "CLIENTAREA" ) == true ? false : true );
	}
	
	
	public function is_frontend()
	{
		return ( defined( "CLIENTAREA" ) == true ? true : false );
	}
	
	
	/**
	 * Sets a property for the object to the given value
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $property: Contains the name of the property to set
	 * @param		varies		- $value: Contains the value of the property to set
	 * 
	 * @return		mixed sends back the previous value or null if this is new
	 * @since		1.0.0
	 */
	public function set( $property, $value = null )
	{
		$previous = isset( $this->$property ) ? $this->$property : null;
		$this->$property = $value;
		return $previous;
	}
	
	
	/**
	 * Set a group of properties
	 * @access		public
	 * @version		1.0.0
	 * @param		array		- $properties: array of properties to set to this object
	 * 
	 * @return		boolean true if successful, false otherwise
	 * @since		1.0.0
	 */
	public function setProperties( $properties )
	{
		$properties = (array) $properties; //cast to an array

		if ( is_array( $properties ) ) {
			foreach ( $properties as $k => $v ) {
				$this->$k = $v;
			}
			return true;
		}
		return false;
	}
	
	
	/**
	 * Adds an error to the error stack
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $error: mesage
	 * 
	 * @since		1.0.0
	 */
	public function setError($error)
	{
		array_push( $this->_errors, $error );
	}
	
	
	/**
	 * Adds an array of errors to the stack
	 * @access		public
	 * @version		1.0.0
	 * @param		array		- $errors: Contains errors being passed to this object
	 * @param		string		- $prefix: Contains a prefix to add onto the front of the error to id it
	 * 
	 * @since		1.0.0
	 */
	public function setErrors( $errors = array(), $prefix = null )
	{
		foreach ( $errors as $error ) {
			$this->setError( $prefix . $error );
		}
	}
	
	
	/**
	 * Converts this object to a string
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		string
	 * @since		1.0.0
	 */
	public function toString()
	{
		return $this->__toString();
	}
	
	
	/**
	 * Getter method
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $name: the name of the property to get
	 * 
	 * @return		mixed the value of the property or null if not set
	 * @since		1.0.0
	 */
	public function __get( $name )
	{
		return ( isset( $this->_properties[$name] ) ? $this->_properties[$name] : null );
	}
	
	
	/**
	 * Setter method
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $name: the name of the property to set
	 * @param		mixed		- $value: the value to set the property to
	 * 
	 * @since		1.0.0
	 */
	public function __set( $name, $value )
	{
		$this->_properties[$name] = $value;
	}
}
?>