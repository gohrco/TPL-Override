<?php

/*-- Security Protocols --*/
defined( 'WHMCS' ) or die( 'Restricted access' );
/*-- Security Protocols --*/

/**
 * Database handler to make connection to database server.
 * @version		1.0.0
 * 
 * @since		1.0.0
 * @author		Steven
 */
class TplDatabase extends TplObject
{
	/**
	 * Contains the resource object
	 * @access		private
	 * @version		1.0.0
	 * @var			object
	 */
	private $_resource	= null;
	
	/**
	 * Contains the sql query to execute
	 * @access		private
	 * @version		1.0.0
	 * @var			string
	 */
	private $_sql		= null;
	
	/**
	 * Contains the limiting value
	 * @access		private
	 * @version		1.0.0
	 * @var			integer
	 */
	private $_limit		= 0;
	
	/**
	 * Contains the offset value
	 * @access		private
	 * @version		1.0.0
	 * @var			integer
	 */
	private $_offset	= 0;
	
	/**
	 * Contains the resulting database cursor
	 * @access		private
	 * @version		1.0.0
	 * @var			object
	 */
	private $_cursor	= null;
	
	
	/**
	 * Constructor method
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @since		1.0.0
	 */
	public function __construct(  )
	{
		include( WHMCS_ROOT . 'configuration.php' );
		$this->_resource = @mysql_connect( $db_host, $db_username, $db_password, true );
		mysql_select_db( $db_name, $this->_resource );
	}
	
	
	/**
	 * Checks to see if the database is connected
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		boolean
	 * @since		1.0.0 
	 */
	public function connected()
	{
		if ( is_resource( $this->_resource ) ) {
			return mysql_ping( $this->_resource );
		}
		return false;
	}
	
	
	/**
	 * Ensures a value is quoted and escaped for querying
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $text: contains the value to quote
	 * @param		boolean		- $escaped: if true, escapes the value else uses as is
	 * 
	 * @return		string containing quoted and or escaped string
	 * @since		1.0.0
	 */
	public function Quote( $text, $escaped = true )
	{
		return '\''.($escaped ? $this->getEscaped( $text ) : $text).'\'';
	}
	
	
	/**
	 * Ensures a name is properly quoted for querying
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $s: contains the table field name to quote
	 * 
	 * @return		string containing quoted field (unless table.field provided)
	 * @since		1.0.0
	 */
	public function nameQuote( $s )
	{
		// Only quote if the name is not using dot-notation
		if ( strpos( $s, '.' ) === false ) {
			return "`{$s}`";
		}
		else {
			return $s;
		}
	}
	
	
	/**
	 * Escapes a field value
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $text: contains the string to escape
	 * @param		boolean		- $extra: if set will also slash '%' and'_' values
	 * 
	 * @return		escaped string for query
	 * @since		1.0.0
	 */
	public function getEscaped( $text, $extra = false )
	{
		$result = mysql_real_escape_string( $text, $this->_resource );
		if ( $extra ) {
			$result = addcslashes( $result, '%_' );
		}
		return $result;
	}
	
	
	/**
	 * Sets the query to the database object
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $sql: containing the query
	 * @param		integer		- $limit: if set, will set the maximum number of rows to return
	 * @param		integer		- $offset: if set, will set where to start from
	 * 
	 * @since  1.0.0
	 */
	public function setQuery( $sql, $limit = 0, $offset = 0 )
	{
		$this->_sql		= $sql;
		$this->_limit	= (int) $limit;
		$this->_offset	= (int) $offset;
	}
	
	
	/**
	 * Executes a query set in the database object
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		resource object or false on failure
	 * @since		1.0.0
	 */
	public function query()
	{
		// If not even a resource then end
		if (! is_resource( $this->_resource ) ) return false;
		
		$sql = $this->_sql; // Local copy
		
		if ($this->_limit > 0 || $this->_offset > 0) {
			$sql .= ' LIMIT ' . max($this->_offset, 0) . ', ' . max($this->_limit, 0);
		}
		
		$this->_cursor = mysql_query( $sql, $this->_resource );
		
		// If this failed
		if (!$this->_cursor)
		{
			$this->setError( mysql_errno( $this->_resource ) . ': ' . mysql_error( $this->_resource )." SQL=$sql" );
			return false;
		}
		
		return $this->_cursor;
	}
	
	
	/**
	 * Determines how many rows were affected by the previous query
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		integer of number of affected rows
	 * @since		1.0.0
	 */
	public function getAffectedRows()
	{
		return mysql_affected_rows( $this->_resource );
	}
	
	
	/**
	 * Returns the id of the last insert query
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		integer of last inserted row
	 * @since		1.0.0
	 */
	public function getInsertid()
	{
		return mysql_insert_id( $this->_resource );
	}
	
	
	/**
	 * Gets the number of rows queried
	 * @access		public
	 * @version		1.0.0
	 * @param		resource		- $cur: If provided contains a database resource
	 * 
	 * @return		integer of rows selected
	 * @since		1.0.0
	 */
	public function getNumRows( $cur = null )
	{
		return mysql_num_rows( $cur ? $cur : $this->_cursor );
	}
	
	
	/**
	 * Loads a single column of the first row of a result, regardless of number of columns
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		string containing the result value
	 * @since		1.0.0
	 */
	public function loadResult()
	{
		// Execute the query
		if (! ( $cur = $this->query() ) ) return null;
		
		$ret = null;
		if ( $row = mysql_fetch_row( $cur ) )
			$ret = $row[0];
		
		mysql_free_result( $cur );
		
		return $ret;
	}
	
	
	/**
	 * Loads a single column of values of a result, regardless of number of rows or columns returned
	 * @access		public
	 * @version		1.0.0
	 * @param		integer		- $numinarray: if provided will return the offset values from the row (defaults to first column)
	 * 
	 * @return		array containing values from the database
	 * @since		1.0.0
	 */
	public function loadResultArray( $numinarray = 0 )
	{
		if (! ( $cur = $this->query() ) ) return null;
		
		$array = array();
		while ( $row = mysql_fetch_row( $cur ) )
			$array[] = $row[$numinarray];
		
		mysql_free_result( $cur );
		
		return $array;
	}
	
	
	/**
	 * Wrapper for loadResultArray
	 * @access		public
	 * @version		1.0.0
	 * @param		integer		- $numinarray: if provided will return the offset values from the row (defaults to first column)
	 * 
	 * @return		result of loadResultArray
	 * @since		1.0.0
	 */
	public function loadResultList( $numinarray = 0 )
	{
		return $this->loadResultArray( $numinarray );
	}
	
	
	/**
	 * Loads a single row of values from a result
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		array of key => value pairs from the database
	 * @since		1.0.0
	 */
	public function loadAssoc()
	{
		if (! ( $cur = $this->query() ) ) return null;
		
		$ret = null;
		if ( $array = mysql_fetch_assoc( $cur ) )
			$ret = $array;
		
		mysql_free_result( $cur );
		
		return $ret;
	}
	
	
	/**
	 * Loads the rows of values from a result
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $key: if set will bind each row to the value of the key provided
	 * 
	 * @return		array of arrays containing key => value pairs from the database
	 * @since		1.0.0
	 */
	public function loadAssocList( $key = null )
	{
		if (!( $cur = $this->query() ) ) return null;
		
		$array = array();
		while ( $row = mysql_fetch_assoc( $cur ) ) {
			if ($key)
				$array[$row[$key]] = $row;
			else
				$array[] = $row;
		}
		
		mysql_free_result( $cur );
		
		return $array;
	}
	
	
	/**
	 * Loads a single object of values from the first row of a result
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		object containing key => value pairings from the database
	 * @since		1.0.0
	 */
	public function loadObject( )
	{
		if (! ( $cur = $this->query() ) ) return null;
		
		$ret = null;
		if ( $object = mysql_fetch_object( $cur ) )
			$ret = $object;
		
		mysql_free_result( $cur );
		
		return $ret;
	}
	
	
	/**
	 * Loads the rows of values into an array of objects from a result
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $key: if set will bind each row of objects to the value of the key provided
	 * 
	 * @return		array of objects containing key->value pairs from the database
	 * @since		1.0.0
	 */
	public function loadObjectList( $key='' )
	{
		if (!( $cur = $this->query() ) ) return null;
		
		$array = array();
		while ( $row = mysql_fetch_object( $cur ) ) {
			if ( $key )
				$array[$row->$key] = $row;
			else
				$array[] = $row;
		}
		
		mysql_free_result( $cur );
		
		return $array;
	}
	
	
	/**
	 * Loads a single row from the database result
	 * @access		public
	 * @version		1.0.0
	 * 
	 * @return		array of values from the database
	 * @since		1.0.0
	 */
	public function loadRow()
	{
		if (!( $cur = $this->query() ) ) return null;
		
		$ret = null;
		if ( $row = mysql_fetch_row( $cur ) )
			$ret = $row;
		
		mysql_free_result( $cur );
		
		return $ret;
	}
	
	
	/**
	 * Loads the rows from the database result
	 * @access		public
	 * @version		1.0.0
	 * @param		string		- $key: if set will bind each result to the value of the key provided
	 * 
	 * @return		array of values from the database
	 * @since		1.0.0
	 */
	public function loadRowList( $key = null )
	{
		if (!( $cur = $this->query() ) ) return null;
		
		$array = array();
		while ( $row = mysql_fetch_row( $cur ) ) {
			if ($key !== null)
				$array[$row[$key]] = $row;
			else
				$array[] = $row;
		}
		
		mysql_free_result( $cur );
		
		return $array;
	}
}
?>