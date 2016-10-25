<?php

namespace Code_Alchemy\Database\SQL;
use Code_Alchemy\Core\Alchemist;
use Code_Alchemy\Database\SQL\Helpers\Real_SQL_Value;
use Code_Alchemy\Database\SQL\Helpers\SQL_Conditions_Splitter;

/**
 * Class SQLCreator
 * @package Code_Alchemy\Database\SQL
 *
 * May Create SQL statements from shorthand conditions passed from a higher level of abstraction
 */
class SQLCreator extends Alchemist{
	
	private static $Debug = false;
	
	const NAME  = 0;
	const VALUE  = 1;
	
	//! construct a where clause, synonym for below method
	public static function WHERE( $conditions ) { return self::getWHEREclause( $conditions ); }

    /**
     * @param null $Conditions
     * @param string $caller
     * @param string $comma_substitute
     * @return string
     * @throws Exception
     */
    public static function getWHEREclause(

         $Conditions = null,

         $caller = "unknown",

        $comma_substitute = '',

	$model_name = ''

     ) {

		// if conditions are null, just return '1'
		if ( ! $Conditions )
			return ' WHERE 	TRUE ';


         // save original for any last-minute processing
        $original_conditions = $Conditions;

		$Conditions = explode( ',' ,$Conditions);
		
		$Clause = ' WHERE (';
		
		$FirstOne = true;
		
		// was the last one an or?
		$last_was_or = false;
		
		foreach ( $Conditions as $Condition) {

            $Condition = trim($Condition);

			//\FB::info(get_called_class().": Condition is $Condition");

            /**
             * new!  Ignore empty conditions
             */
            if ( ! $Condition || strlen($Condition)==0 ){

                continue;

            }

            $matched = false;

			$splitter = (string) new SQL_Conditions_Splitter($Condition);

            // if just a 1, continue
			if ( preg_match ('/^[1]{1}$/', $Condition ) )
				continue;
			

			// group by 
			if ( preg_match( '/GROUP BY/', strtoupper( $Condition ) ) )
				continue;

			// group by 
			if ( preg_match( '/(SORT|ORDER) BY/', strtoupper( $Condition ) ) ){
                continue;
            }

			// special case!
			if ( preg_match ( '/LIMIT/' , ( $Condition ) ) )
				continue;
				
			// special case!
			if ( preg_match ( '/OFFSET\s+/' , strtoupper( $Condition ) ) ){
                continue;

            }

			elseif ( preg_match( '/\s+LIKE\s+/' , $Condition  ) ) $splitter = 'LIKE';

			// split the condition into components
			$NVPair = explode( $splitter, $Condition );

			if ( count($NVPair)< 2){

				\FB::error("$Condition: cannot be split properly to create a name/value pair. This is likely because the caller ( $caller ) did not pre-process using the Human Language Module. splitter = $splitter ($Condition)");


			} else {

				//\FB::info([$NVPair,$splitter,$Condition],"NVPair splitter Condition");
			}

			// if not the first component, add AND or OR based on what is needed
			if ( ! $FirstOne ) {

			
				if ( $last_was_or && ! preg_match ('/OR:/' , $NVPair[self::NAME] ))

					$Clause .= " ) ";

				$connector = preg_match('/OR:/', $NVPair[self::NAME]) ? ' OR ' : ' AND ';

				$Clause .= $connector;

				//\FB::info(get_called_class().": Not the first component.  Annexing last condition using $connector");

				if ( $last_was_or && ! preg_match ('/OR:/' , $NVPair[self::NAME] ) )

						$Clause .= " ( ";
				
			}
			
			// now its definitely not the fist one
			$FirstOne = false;

			$name = preg_match ('/OR:/' , $NVPair[self::NAME] ) ?  substr( trim($NVPair[self::NAME]), 3 ) : trim($NVPair[self::NAME]);

			$value = (new Real_SQL_Value($NVPair[self::VALUE],$model_name))->value();

			$val = $splitter == 'LIKE' ? "'".(trim($value))."'": trim($value) ;

            // New! Apply comma substitution
            if ( $comma_substitute )

                $val = preg_replace('/\\'.$comma_substitute.'/',',',$val);



            if ($splitter == 'IN')
                $val = preg_replace('/\./',',',$val);

            if ( preg_match('/LOWER\(([a-z|0-9|_]+)\)/',$name,$hits)){
                $Clause .= "LOWER(`$hits[1]`) $splitter $val";
            } else {
                // handler function such as LENGTH(column)
                if (preg_match("/([A-Z]+)\(([a-z|_]+)\)/",$name,$hits)){
                    $Clause .= $hits[1]."(".'`'.$hits[2].'`'.") $splitter $val";
                } else
                {
                    // general case, no special handling
                    $Clause .=  (preg_match('/`/',$name)) ?" $name   $splitter $val": " `$name`   $splitter $val";
                }

            }

			// if the last one was an or
			if ( preg_match ('/OR:/' , $NVPair[self::NAME] ) )
				$last_was_or = true;
			else $last_was_or = false;
		}
		
		// fix
		if ( trim ( $Clause ) == 'WHERE' || trim( $Clause) == 'WHERE (')
			$Clause = ' WHERE TRUE ';
		else $Clause .= " )";

        // last thing, if an or comes after an and, we need more parentheses
         if ( preg_match('/(.+)\s+AND\s+(.+)\s+(.+)\s+(.+)\s+OR\s+(.+)\s+(.+)\s+(.+)/',$Clause,$hits)){
            $Clause = "$hits[1] AND ( $hits[2] $hits[3] $hits[4] OR $hits[5] $hits[6] $hits[7] )";
         }

        if ( preg_match( '/order\s+by\s+([a-z|_]+)\s+(asc|desc)/', strtolower( $original_conditions ),$hits ) ){

             $Clause .= " ORDER BY $hits[1] $hits[2]";
         }

         /**
          * If a Limit was provided in the clause, then we should tack it on here
          */
         if ( preg_match( '/LIMIT ([0-9]+)/',strtoupper($original_conditions),$hits))
             $Clause .= " LIMIT $hits[1] ";

         // if we had an offset include it

         if ( preg_match('/OFFSET ([0-9]+)/',strtoupper($original_conditions),$hits))
                $Clause .= preg_match('/LIMIT/',$Clause)?  "OFFSET $hits[1]": " LIMIT 10000 OFFSET $hits[1]";

		return "$Clause";
	 	
	 }
	 
	 //! convert inline operators
	 public static function convert_inline_ops( $str ) {
	 
		$regex = array( '/\[eq\]/' => '=' ,  '/\[ne\]/' => '!=' , 
			'/\[LIKE\]/' => 'LIKE' );
		
		foreach ( $regex as $reg => $replace )
			$str = preg_replace( $reg, $replace, $str);
			
		return $str;
	 
	 }
	
}

?>
