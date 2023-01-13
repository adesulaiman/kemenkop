<?php

class pgdb {
 
	protected $host;
	protected $port;
	protected $dbuser;
	protected $dbpass;
	protected $dbname;
	public $conn;
	protected $result;
 
	function __construct( $dbuser, $dbpass, $dbname, $host, $port ) {
		$this->host = $host;
		$this->port = $port;
		$this->dbuser = $dbuser;
		$this->dbpass = $dbpass;
		$this->dbname = $dbname;
		
	}
 
	function db_connect() {
		
		$this->conn = pg_connect("host=$this->host port=$this->port dbname=$this->dbname user=$this->dbuser password=$this->dbpass");
		
		if($this->conn === FALSE)
		{
			$this->conn = null;
		}
		return false;
	}
	
	
	
	public function query($query = null)
	{
		if(! $this->check_ascii($query))
		{
			return false;
		}
		
		$this->_do_query( $query );
		
		
		return $this->result;
	}
	
	
	public function select($query = null , $output = 'default')
	{
		if(! $this->check_ascii($query))
		{
			return false;
		}
		
		$this->_do_query( $query );
		
		
		$new_array = array();
		if ( $output == 'default' ) {
			// Return an array of row objects with keys from column 1
			// (Duplicates are discarded)
			while ( $data = pg_fetch_assoc($this->result) ) {
				array_push($new_array, $data);
			}
			return $new_array;
		} elseif ( $output == 'k' ) {
			// Return an integer-keyed array of...
			while ( $data = pg_fetch_array($this->result) ) {
				array_push($new_array, $data);
			}
			return $new_array;
		}
		return null;
	}
	
	
	protected function check_ascii( $string ) {
		if ( function_exists( 'mb_check_encoding' ) ) {
			if ( mb_check_encoding( $string, 'ASCII' ) ) {
				return true;
			}
		} elseif ( ! preg_match( '/[^\x00-\x7F]/', $string ) ) {
			return true;
		}
		return false;
	}
	
	public function timer_start() {
		$this->time_start = microtime( true );
		return true;
	}
	
	public function timer_stop() {
		return ( microtime( true ) - $this->time_start );
	}
	
	private function _do_query( $query ) {
		$this->db_connect();
		$this->timer_start();
		$this->result = pg_query( $this->conn, $query );
		$this->close();
		$this->timer_stop();
	}
	
	
	public function close() {
		if ( ! $this->conn ) {
			return false;
		}
		
		$closed = pg_close($this->conn);
		
		if ( $closed ) {
			$this->conn = null;
		}

		return $closed;
	}
	
	
	
	public function prepare( $query, $args ) {
		if ( is_null( $query ) ) {
			return;
		}

		// This is not meant to be foolproof -- but it will catch obviously incorrect usage.
		if ( strpos( $query, '%' ) === false ) {
		//	_doing_it_wrong( 'wpdb::prepare', sprintf( __( 'The query argument of %s must have a placeholder.' ), 'wpdb::prepare()' ), '3.9.0' );
		}

		$args = func_get_args();
		array_shift( $args );

		// If args were passed as an array (as in vsprintf), move them up.
		$passed_as_array = false;
		if ( is_array( $args[0] ) && count( $args ) == 1 ) {
			$passed_as_array = true;
			$args = $args[0];
		}

		foreach ( $args as $arg ) {
			if ( ! is_scalar( $arg ) && ! is_null( $arg ) ) {
			//	_doing_it_wrong( 'wpdb::prepare', sprintf( __( 'Unsupported value type (%s).' ), gettype( $arg ) ), '4.8.2' );
			}
		}

		/*
		 * Specify the formatting allowed in a placeholder. The following are allowed:
		 *
		 * - Sign specifier. eg, $+d
		 * - Numbered placeholders. eg, %1$s
		 * - Padding specifier, including custom padding characters. eg, %05s, %'#5s
		 * - Alignment specifier. eg, %05-s
		 * - Precision specifier. eg, %.2f
		 */
		$allowed_format = '(?:[1-9][0-9]*[$])?[-+0-9]*(?: |0|\'.)?[-+0-9]*(?:\.[0-9]+)?';

		/*
		 * If a %s placeholder already has quotes around it, removing the existing quotes and re-inserting them
		 * ensures the quotes are consistent.
		 *
		 * For backwards compatibility, this is only applied to %s, and not to placeholders like %1$s, which are frequently
		 * used in the middle of longer strings, or as table name placeholders.
		 */
		$query = str_replace( "'%s'", '%s', $query ); // Strip any existing single quotes.
		$query = str_replace( '"%s"', '%s', $query ); // Strip any existing double quotes.
		$query = preg_replace( '/(?<!%)%s/', "'%s'", $query ); // Quote the strings, avoiding escaped strings like %%s.

		$query = preg_replace( "/(?<!%)(%($allowed_format)?f)/" , '%\\2F', $query ); // Force floats to be locale unaware.

		$query = preg_replace( "/%(?:%|$|(?!($allowed_format)?[sdF]))/", '%%\\1', $query ); // Escape any unescaped percents.

		// Count the number of valid placeholders in the query.
		$placeholders = preg_match_all( "/(^|[^%]|(%%)+)%($allowed_format)?[sdF]/", $query, $matches );

		if ( count( $args ) !== $placeholders ) {
			if ( 1 === $placeholders && $passed_as_array ) {
				// If the passed query only expected one argument, but the wrong number of arguments were sent as an array, bail.
				//_doing_it_wrong( 'wpdb::prepare', __( 'The query only expected one placeholder, but an array of multiple placeholders was sent.' ), '4.9.0' );

				return;
			} else {
				/*
				 * If we don't have the right number of placeholders, but they were passed as individual arguments,
				 * or we were expecting multiple arguments in an array, throw a warning.
				 
				_doing_it_wrong( 'wpdb::prepare',
					 translators: 1: number of placeholders, 2: number of arguments passed
					sprintf( __( 'The query does not contain the correct number of placeholders (%1$d) for the number of arguments passed (%2$d).' ),
						$placeholders,
						count( $args ) ),
					'4.8.3'
				);*/
			}
		}

		array_walk( $args, array( $this, 'escape_by_ref' ) );
		$query = @vsprintf( $query, $args );

		return $this->add_placeholder_escape( $query );
	}
	
	public function escape_by_ref( &$string ) {
		if ( ! is_float( $string ) )
			$string = $this->_real_escape( $string );
	}
	
	
	function _real_escape( $string ) {
		if ( $this->conn ) {
			$escaped = pg_escape_string( $this->conn, $string );
		} else {
			$class = get_class( $this );
			if ( function_exists( '__' ) ) {
				/* translators: %s: database access abstraction class, usually wpdb or a class extending wpdb */
				//_doing_it_wrong( $class, sprintf( __( '%s must set a database connection for use with escaping.' ), $class ), '3.6.0' );
			} else {
				//_doing_it_wrong( $class, sprintf( '%s must set a database connection for use with escaping.', $class ), '3.6.0' );
			}
			$escaped = addslashes( $string );
		}

		return $this->add_placeholder_escape( $escaped );
	}
	
	
	public function add_placeholder_escape( $query ) {
		/*
		 * To prevent returning anything that even vaguely resembles a placeholder,
		 * we clobber every % we can find.
		 */
		return str_replace( '%', $this->placeholder_escape(), $query );
	}
	
	public function placeholder_escape() {
		static $placeholder;

		if ( ! $placeholder ) {
			// If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
			$algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
			// Old WP installs may not have AUTH_SALT defined.
			$salt = defined( 'AUTH_SALT' ) && AUTH_SALT ? AUTH_SALT : (string) rand();

			$placeholder = '{' . hash_hmac( $algo, uniqid( $salt, true ), $salt ) . '}';
		}

		/*
		 * Add the filter to remove the placeholder escaper. Uses priority 0, so that anything
		 * else attached to this filter will recieve the query with the placeholder string removed.
		 
		if ( ! has_filter( 'query', array( $this, 'remove_placeholder_escape' ) ) ) {
			add_filter( 'query', array( $this, 'remove_placeholder_escape' ), 0 );
		}*/

		return $placeholder;
	}
	
	public function remove_placeholder_escape( $query ) {
		return str_replace( $this->placeholder_escape(), '%', $query );
	}
	
 
}



?>