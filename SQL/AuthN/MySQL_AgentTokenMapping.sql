-- /**-- @package harmoni.osid_v2.agentmanagement-- -- -- @copyright Copyright &copy; 2005, Middlebury College-- @license http://www.gnu.org/copyleft/gpl.html GNU General Public License (GPL)-- -- @version $Id: MySQL_AgentTokenMapping.sql,v 1.5 2005/08/08 22:34:34 adamfranco Exp $-- */-- ---------------------------------------------------------- -- Table structure for table `agenttoken_mapping`-- CREATE TABLE agenttoken_mapping (  agent_id varchar(75) NOT NULL default '0',  token_identifier varchar(255) NOT NULL default '',  fk_type int(10) unsigned NOT NULL default '0',  PRIMARY KEY  (fk_type,token_identifier,agent_id),  KEY agent_id (agent_id),  KEY system_name (token_identifier(75))) CHARACTER SET utf8TYPE=InnoDB;-- ---------------------------------------------------------- -- Table structure for table `agenttoken_mapping_authntype`-- CREATE TABLE agenttoken_mapping_authntype (  id int(11) NOT NULL auto_increment,  domain varchar(100) NOT NULL default '',  authority varchar(100) NOT NULL default '',  keyword varchar(100) NOT NULL default '',  description text NOT NULL,  PRIMARY KEY  (id),  UNIQUE KEY uniq (domain,authority,keyword)) CHARACTER SET utf8TYPE=InnoDB;