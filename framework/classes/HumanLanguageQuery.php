<?php

/**
 * Class HumanLanguageQuery facilitates performing database queries using
 * a more natural language expression that humans can relate to
 */
class HumanLanguageQuery extends magic_object{
	
	//! parsed sql conditions
	private $conditions = array();
	
	//! magic get
	public function __get( $what ) {
		switch( $what ) {
			case 'conditions':
				return $this->conditions();
			break;
			default: return parent::__get( $what ); break;
		}
	}

    /**
     * @param $query
     * @param string $id
     * @param string $comma_substitute
     */
    public function __construct( $query, $id = "id", $comma_substitute = '' ) {

        global $container;

		$matched = false;

        $query = preg_replace('/,/','$$',$query);

        $conds = explode( '$$' , $query );

        /**
         * Loop through each of the conditions provided by the user, and try to decipher
         * each one, into something that can be used as SQL
         */
        foreach ( $conds as $cond ) {

			$cond = trim($cond);

			switch ( $cond) {
				
			
				// archived items
				case 'archived':
				
					array_push( $this->conditions, "is_archived='1'");
				
				break;
			
				// all items
				case 'all':
				
					array_push( $this->conditions, "1");
					
				break;
			
				// item is not deleted
				case 'not deleted':
				case 'not archived':
				
					preg_match( '/not ([a-z]+)/' , $cond , $matches);
				
					array_push( $this->conditions , "is_$matches[1]='0'");
					
				break;
			
				// first scenario: a boolean match
				case 'approved':
				
					array_push( $this->conditions, "$cond='1'" );
					
				break;
			
				case 'mine':
				
					$myId = x_objects::instance()->my_id();
					array_push( $this->conditions, "user_id='$myId'" );
				
				break;
				
				case 'this week':
					array_push( $this->conditions, "week >= '" . Utility::prior_monday() . "'");
					array_push( $this->conditions, "week <= '" . Utility::coming_sunday() . "'");
					
				break;
				
				case 'last week':
					array_push( $this->conditions, "week >= '" . Utility::prior_monday(-1) . "'");
					array_push( $this->conditions, "week <= '" . Utility::coming_sunday(-1) . "'");
					
				break;
				
				case 'next week':
					array_push( $this->conditions, "week >= '" . Utility::prior_monday(1) . "'");
					array_push( $this->conditions, "week <= '" . Utility::coming_sunday(1) . "'");
					
				break;
				
				default:

                    //FB::info(get_called_class().": Will use Regex to match condition");

                    $matched = false;

                    /**
                     * COlumn has an IN clause
                     */
                    $regex = "/([a-z|_|0-9]+)\s+IN\s+\((\;?[0-9]+)+\)/";

                    if ( preg_match($regex,$cond,$matches)){

                        //FB::info(get_called_class().": Condition $cond matches $regex");

                        array_push( $this->conditions, $cond);

                        $matched = true;
                    }



                    /**
                     *column is null
                     */
                    $regex = "/([a-z|_|0-9]+)\s+IS\s+NULL/";
                    if ( preg_match($regex,$cond,$matches)){

                        //FB::info(get_called_class().": Condition $cond matches $regex");
                        array_push( $this->conditions, $cond);
                        $matched = true;
                    }

                    /**
                     * most recent for a specifc page
                     */
                    $regex = "/([0-9]+)\s+most\s+recent\s+from\s+page\s+([0-9]+)/";
                    if ( preg_match( $regex, $cond, $matches )) {

                        //FB::info(get_called_class().": Condition $cond matches $regex");

                        $matched = true;
                        array_push( $this->conditions, "order by id DESC");
                        $size = $matches[1];
                        $page = $matches[2];
                        $offset = ($page -1) * $size;
                        array_push( $this->conditions, "LIMIT $size");
                        array_push( $this->conditions, "OFFSET $offset");
                        break;
                    }


                    // handle time based checks in human language
                    if ( preg_match('/human_language_([a-z|_]+)/',$cond,$hits)){
                        $matched = true;
                        if ( $container->debug) echo "$tag->event_format : $cond has been matched<br>\r\n";
                        array_push($this->conditions, $this->hl_time_decode($cond));
                        break;
                    }
				
                    /**
                     * 'contains' which is used to match for rlike
                     */
                    $regex = "/([a-z|A-Z|0-9|\_|\-]+) contains ([a-z|A-Z|0-9|\-|\_]+)/";
                    if ( preg_match( $regex, $cond, $matches )) {
                        $matched = true;
                        array_push( $this->conditions, "$matches[1] LIKE '%$matches[2]%'");
                    }

                    /**
                     * most recent by a specific field
                     */
                    $regex = "/([0-9]+)\s+most\s+recent\s+by\s+([a-z|A-Z|0-9|\_|\-]+)/";
                    if ( preg_match( $regex, $cond, $matches )) {
                        $matched = true;

                        array_push( $this->conditions, "LIMIT $matches[0]");

                        array_push( $this->conditions, "order by $matches[2] DESC");
                    }

                    // support for pagination queries
                    $regex = "/([0-9]+) from page ([0-9]+)/";
					if ( preg_match( $regex, $cond, $matches )) {
						$matched = true;
						$size = $matches[1];
						$page = $matches[2];
						$offset = ($page -1) * $size;
						array_push( $this->conditions, "LIMIT $size");
						array_push( $this->conditions, "OFFSET $offset");
					}
					
					// case 1: N most recent ( implies order by id, desc, limit N
					$regex = array( 
					'/([0-9]+)\s+most\s+recent\s+ascending/' => " ORDER BY `$id` ASC,LIMIT match",
					'/([0-9]+) most recent/' => " ORDER BY `$id` DESC,LIMIT match",
					'/first ([0-9]+)/' => " LIMIT match "
				
					);
			
					foreach ( $regex as $reg => $term )
						if ( ! $matched && preg_match( $reg, $cond, $matches ) ) {
							$matched = true;
							array_push( $this->conditions, preg_replace( '/match/' , $matches[1] , $term) );
						}
						
					// case 2: "related to <key> <value>
					/*$regex = '/related\s+to\s+([a-zA-Z0-9_]+)\s+([a-zA-Z0-9]+)/';
					
						if ( preg_match( $regex, $cond, $matches ) ) {
							$matched = true;
							//echo $matches[0];
							array_push( $this->conditions, "$matches[1] = $matches[2]");
						}
					*/
							// case 3: order by a column
					$regex = '/order by ([a-zA-Z0-9_]+)\.([a-zA-Z0-9_]+)\s+ASC$/';
						if ( ! $matched && preg_match( $regex, $cond, $matches ) ) {

							$matched = true;
                            if ( $container->debug ) echo "$tag->event_format: matched on $regex<br>\r\n";
							array_push( $this->conditions, "ORDER BY $matches[1].$matches[2] ASC");
						}
					// case 3: order by a column
					$regex = '/order by ([a-zA-Z0-9_]+)\s+(ASC|DESC)/';
						if (! $matched && preg_match( $regex, $cond, $matches ) ) {
							$matched = true;
							array_push( $this->conditions, "ORDER BY $matches[1] $matches[2]");
						}
						
				
						

					// case 4: group by a column
					$regex = '/group by ([a-zA-Z0-9_]+)/';
						if ( preg_match( $regex, $cond, $matches ) ) {
							$matched = true;
							array_push( $this->conditions, "GROUP BY $matches[1]");
						}
						
						
					// case offset for recordset
					$regex = '/offset:([0-9]+)/';
						if ( preg_match( $regex, $cond, $matches )) {
							$matched = true;
							array_push( $this->conditions, "LIMIT 10");
							array_push( $this->conditions, "OFFSET $matches[1]");
						}
						
					// case "related to" another record
					$regex = '/related to ([a-z]+) ([0-9]+)/';
					if ( preg_match( $regex, $cond, $matches )) {
					
						$matched = true;
						array_push( $this->conditions, "$matches[1]_id='$matches[2]'" );
						
					}
					// id above a certain value
					$regex = "/([a-z]+) above ([0-9]+)/";
					if ( preg_match( $regex,$cond,$matches)){
						$matched = true;
						array_push($this->conditions,"$matches[1] >= $matches[2]");
					}
					
					if ( ! $matched ) {

                       //FB::info(get_called_class().": $cond has NOT been matched");

                        array_push( $this->conditions, $cond );
					}
				
				break;
			
			}
		}
	}
	
	//! are we using a vcache offset?
	public function using_vcache_offset( $str ) {
	
		return preg_match( '/^offset from vcache/' , $str );
	
	}
	
	//! return translated conditions, ready for sql use
	public function conditions() {


        $implode = implode(',', $this->conditions);

        //FB::info(get_called_class().": Conditions are $implode");

        return $implode;
		
	}

    /**
     * @param $query
     * @param string $idcol
     * @param null $caller
     * @param string $comma_substitute
     * @return HumanLanguageQuery
     */
    public static function create(
        $query,
        $idcol="id",
        $caller = null,
        $comma_substitute = '^'
) {
        return new HumanLanguageQuery( $query, $idcol,$comma_substitute );

	}

    /**
     * @param $str the raw condition before being formatted
     * @return string translated condition
     */
    public function hl_time_decode($str){
        global $container;
        $tag = new xo_codetag(xo_basename(__FILE__),__LINE__,get_class(),__FUNCTION__);
        $c = null;
        if ( preg_match('/([a-z|_]+)\=\'human_language_([a-z|0-9|_]+)/',$str,$hits)){
            switch( $hits[2]){
                case 'today':
                    $s = date('Y-m-d 00:00:00');
                    $e = date('Y-m-d 23:59:59');
                break;
                case 'last_24':
                    $s = date('Y-m-d H:i:s',(time()-86400 )) ;
                    $e = date('Y-m-d H:i:s');
                break;
                case 'last_7d':
                    $s = date('Y-m-d H:i:s',(time()-(86400*7) )) ;
                    $e = date('Y-m-d H:i:s');
                break;
                case 'yesterday':
                    $y = strtotime ( '-1 day' , time() ) ;
                    $s = date('Y-m-d 00:00:01',$y);
                    $e = date('Y-m-d 23:59:59',$y);
                break;
                case 'tomorrow':
                    $t = strtotime ( '+1 day' , time() ) ;
                    $s = date('Y-m-d 00:00:01',$t);
                    $e = date('Y-m-d 23:59:59',$t);
                break;
                case 'last_week':
                    // go back one week
                    $l = strtotime("-7 day",time());
                    // where are we in relation to sunday?
                    $n = date('N',$l)-1;
                    $d = ($n>0)?strtotime (  "-$n days" , time() ):$l;
                    $d2 = strtotime("+7 days",$d);
                    $s = date('Y-m-d 00:00:01',$d);
                    $e = date('Y-m-d 23:59:59',$d2);
                break;
                case 'this_week':
                    // where are we in relation to sunday?
                    $n = date('N')-1;
                    $d = ($n>0)?strtotime (  "-$n days" , time() ):time();
                    $d2 = strtotime("+7 days",$d);
                    $s = date('Y-m-d 00:00:01',$d);
                    $e = date('Y-m-d 23:59:59',$d2);
                break;
                case 'next_week':
                    // go forward one week
                    $l = strtotime("+7 days",time());
                    // where are we in relation to sunday?
                    $n = date('N',$l)-1;
                    $d = ($n>0)?strtotime (  "-$n days" , time() ):$l;
                    $d2 = strtotime("+7 days",$d);
                    $s = date('Y-m-d 00:00:00',$d);
                    $e = date('Y-m-d 23:59:59',$d2);
                 break;
                case 'this_month':
                    // where are we in relation to the month start?
                    $n = date('j')-1;
                    $d = ($n>0)?strtotime (  "-$n days" , time() ):time();
                    $d2 = strtotime("+1 month",$d);
                    $s = date('Y-m-d 00:00:00',$d);
                    $e = date('Y-m-d 23:59:59',$d2);
                    break;
                case 'last_month':
                    // go back one month
                    $m = strtotime("-1 month",time());
                    // where are we in relation to the month start?
                    $n = date('j',$m)-1;
                    $d = ($n>0)?strtotime (  "-$n days" , time() ):$m;
                    $d2 = strtotime("+1 month",$d);
                    $s = date('Y-m-d 00:00:00',$d);
                    $e = date('Y-m-d 23:59:59',$d2);
                    break;
                case 'next_month':
                    // go forward one month
                    $m = strtotime("+1 month",time());
                    // where are we in relation to the month start?
                    $n = date('j',$m)-1;
                    $d = ($n>0)?strtotime (  "-$n days" , time() ):$m;
                    $d2 = strtotime("+1 month",$d);
                    $s = date('Y-m-d 00:00:00',$d);
                    $e = date('Y-m-d 23:59:59',$d2);
                    break;
                case 'this_year':
                    // whats the year?
                    $y = date('Y');
                    $s = "$y-01-01 00:00:00";
                    $e = "$y-12-31 23:59:59";
                    break;
                case 'last_year':
                    // whats the year?
                    $y = date('Y')-1;
                    $s = "$y-01-01 00:00:00";
                    $e = "$y-12-31 23:59:59";
                    break;
                case 'next_year':
                    // whats the year?
                    $y = date('Y')+1;
                    $s = "$y-01-01 00:00:00";
                    $e = "$y-12-31 23:59:59";
                    break;
                 case 'date_future':
                    $c = "$hits[1]>='".date('Y-m-d')."'";
                  break;
                case 'date_past':
                    $c = "$hits[1]<'".date('Y-m-d')."'";
                    break;
                default:
                    $s = "1900-01-01 00:00:00";
                    $e = "2050-12-31 23:59:59";
                    break;



            }
            $r = $c? $c:"$hits[1]>='$s',$hits[1]<='$e'";
            return $r;
        }
        else return "nope";
    }

}
?>