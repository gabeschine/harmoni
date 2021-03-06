<?php
/**
 * @since 4/30/08
 * @package harmoni.Harmoni_Db
 * 
 * @copyright Copyright &copy; 2007, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id$
 */ 

require_once(HARMONI."/DBHandler/QueryResult.interface.php");
require_once(HARMONI."/DBHandler/InsertQueryResult.interface.php");
require_once(HARMONI."/DBHandler/UpdateQueryResult.interface.php");
require_once(HARMONI."/DBHandler/DeleteQueryResult.interface.php");

/**
 * This result can be used to find information from the results of DELETE, INSERT, or UPDATE queries.
 * 
 * @since 4/30/08
 * @package harmoni.Harmoni_Db
 * 
 * @copyright Copyright &copy; 2007, Middlebury College
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
 *
 * @version $Id$
 */
class Harmoni_Db_InUpDeResult
	implements InsertQueryResultInterface, UpdateQueryResultInterface, DeleteQueryResultInterface
{
		
	/**
	 * Constructor
	 * 
	 * @param <##>
	 * @return <##>
	 * @access public
	 * @since 4/30/08
	 */
	public function __construct (Zend_Db_Statement $stmt, $adapter) {
		$this->stmt = $stmt;
		$this->adapter = $adapter;
	}
	
	/**
	 * Returns the number of rows that the query processed. For a SELECT query,
	 * this would be the total number of rows selected. For a DELETE, UPDATE, or
	 * INSERT query, this would be the number of rows that were affected.
	 * 
	 * @return integer
	 * @access public
	 * @since 4/30/08
	 */
	public function getNumberOfRows () {
		return $this->stmt->rowCount();
	}
	
	/**
	 * Gets the last auto increment value that was generated by the INSERT query.
	 * 
	 * @return integer
	 * @access public
	 * @since 4/30/08
	 */
	public function getLastAutoIncrementValue () {
		return $this->adapter->lastInsertId(
						$this->stmt->autoIncrementTable, 
						$this->stmt->autoIncrementKey);
	}
	
}

?>