<?php

require_once("Query.abstract.php");


/**
 * A GenericSQLQuery provides a way to specify the SQL string manually. Use this
 * query type to execute queries not available through the other Query
 * objects (for example, data-definition queries).
 *
 * @version $Id: GenericSQLQuery.interface.php,v 1.1 2003/08/14 19:26:28 gabeschine Exp $
 * @package harmoni.interfaces.dbc
 * @copyright 2003 
 */

class GenericSQLQueryInterface extends Query {


	/**
	 * Adds one SQL string to this query.
	 * @method public addSQLString
	 * @param string sql One SQL string,
	 * @return void 
	 */
	function addSQLQuery($sql) {
		die ("Method <b>".__FUNCTION__."()</b> declared in interface<b> ".__CLASS__."</b> has not been overloaded in a child class.");
	}

	
}
?>