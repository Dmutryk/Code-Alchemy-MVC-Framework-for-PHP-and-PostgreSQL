<?php

/*! The ObjectFactory provides a few convenient static methods for retrieving large arrays
 * of objects, with as few parameters as possible.
 *  
 * Project:			Platform
 * Module:			classes/ObjectFactory
 * 
 * Purpose:			Factory methods to create objects of different types, especially
 * 					those bound by data
 * 
 * Created by:		David Owen Greenberg <david.o.greenberg@gmail.com>
 * On:				20 Oct 2010
 */
 class ObjectFactory {
	
	//! a flag for turning on debugging for the factory.
	private static $Debug = false;
	
	//! a pointer to the database pool singleton
	private static $DBPool;

	//! a pointer to the database connection
	private static $SQL;
	
	//! vcache pointer status
	public static $vcache_pointer_status = 'fresh';
	
	//! using vcache pointer
	public static $using_vcache_pointer = false;
	
	
	//! sort a group of objects with usort and a given function
	public static function sort( $objects , $function = null // degrades gracefully when no function provided
	) {
		
		
		// only sort if a callback was provided
		if ( $function ) {
			// set up usort parameters
			$keys = array_keys( $objects );
			$class = get_class ( $objects[$keys[0]] );
			usort( $objects , array( $class, $function ) );
		}
		
		return $objects;
	}
	
	//! synonym for createAll()
	public static function create_all( $classname ) { return self::createAll( $classname ); }
	
	/*! createAll( $Classname ): create new objects from 
	 * all rows in given data source
	 * \param Classname: (type: String): data source, as name of data object subclass
	 * \returns: (array of subclass of DataObject) array of newly created objects
	 */
	public static function createAll( $ClassName ) {

		return self::create( $ClassName, null,10000,null,null,null,null);
	}

     public static function create_assoc(
             $classname, // the name of the class for objects to be created
             $offset = null, // where to begin the array from the total set
             $limit = null, 	// limit how many to call
             $sortBy = null, // what field to sort by
             $direction = 'ASC', // what direction to sort
             $conditions = null
         ){
         return self::create(
             $classname, // the name of the class for objects to be created
             $offset, // where to begin the array from the total set
             $limit, 	// limit how many to call
             $sortBy, // what field to sort by
             $direction, // what direction to sort
             $conditions,
             true
         );
     }
	//! obtain an array of the unique database Ids for a set of DataObjects
	public static function getUniqueIdsOf ( $objArray , $idField = 'Id') {

		if ( ! is_array( $objArray ) )
			throw new IllegalArgumentException( 'ObjectFactory::getUniqueIdsOf(): argument must be an array of DataObjects');
	
		$ids = array();
		foreach ( $objArray as $object )
			$ids[$object->get( $idField )] = $object->get( $idField );
			
		return $ids;
	}

	//! search for objects
	public static function search( $what, $how , $query , $filters) {

		$debug = (Debugger::enabled()) ? true : false;
		
		if ( $debug ) 
			echo $_SERVER["PHP_SELF"] . " " . __LINE__ . " " . get_class() . " " . __FUNCTION__ . 
				": what $what, how $how, query $query filters $filters<br>";
				
		switch ( strtolower( $how ) ) {
		
			// use an inline operator
			case 'inline-operator':
			
				$conditions = SQLCreator::convert_inline_ops( $query);
				if ( Debugger::enabled() )
					echo __LINE__ . " " . get_class() . "::search(): conditions=$conditions<br>";
			
			break;
		
			case 'rlike': 
			
				$obj = new $what();
				$search_cols = $obj->search_columns;
						
				
						
				if ( ! $search_cols  )
					throw new ObjectNotInitializedException( get_class() . "::search: $what doesn't have any search columns defined...");
				
				$conditions = Search::rlike_clause( $query, $search_cols ); 
				
				// if we have filters
				if ( $filters )
					$conditions .="$filters";
					
				if ( $debug ) 
					echo $_SERVER["PHP_SELF"] . " " . __LINE__ . " " . get_class() . " " . __FUNCTION__ . 
					": search_cols $search_cols, conditions $conditions<br>";
			break;
			
		}
		return self::create( ucfirst( $what ) , null, null, null, null, $conditions );
	}
	
	//! walk an array of objects, and call a specific method on each
	public static function walk( $objs, $method) {
		foreach( $objs as $obj ) $obj->$method();
	}
	
	//! delete a list of objects from an array of IDS
	public static function delete_from_ids($key,$ids) {
		// get container
		global $container;
		// taggig
		$tag = new xo_codetag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);
		// set debugging
		$debug = $container->debug;

		$count = 0;
		foreach( $ids as $id){
			$obj = new $key("id='$id'");
			if ( $obj->exists){ 
				$obj->delete();
				$count++;
			}
		}
		//$container->log( xevent::notice, "$tag->event_format : deleted $count objects from ids");
	}
	
	//! create a list of objects from an array of IDS
	public static function create_from_ids($key,$ids) {
		// get container
		global $container;
		// taggig
		$tag = new xo_codetag( xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);
		// set debugging
		$debug = $container->debug;
		$objs = array();
		$ref = new $key();
		$id_col = $ref->source()->keycol();
		$count = count( $ids);
		if ($debug)
			echo "$tag->event_format : id_col = $id_col creating $key objects from $count ids<br>\r\n";
		foreach ( $ids as $id) {
			$search = "`$id_col`='$id'";
			//echo $search;
			$obj = new $key($search);
			if ( $obj->exists)
				array_push($objs,$obj);
		}
			
		return $objs;	
	}
	

	// display a collection of objects using the same view
	public static function display( $objs, $view){
		$html = "";
		foreach ( $objs as $obj)
			$html .= $obj->xhtml( $view);
		echo $html;		
	}
	
	// uniquify by a specific field
	public static function uniquify_by( $member, $objs){
		$arr = array();
		foreach ( $objs as $obj)
			if ( ! in_array( $obj->$member, array_keys($arr)))
				$arr[ $obj->$member] = $obj;
		sort( $arr);
		return $arr;
	}
}
