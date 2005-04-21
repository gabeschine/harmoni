-- /**
-- @package harmoni.osid_v2.authentication
--
-- @copyright Copyright &copy; 2005, Middlebury College
-- @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)
--
-- @version $Id: MySQL_Example_Authentication.sql,v 1.3 2005/04/20 21:07:42 adamfranco Exp $
-- */
-- --------------------------------------------------------

-- 
-- Table structure for table `auth_db_user`
-- 

CREATE TABLE auth_db_user (
  username varchar(75) NOT NULL default '',
  password blob NOT NULL,
  PRIMARY KEY  (username)
) TYPE=MyISAM;