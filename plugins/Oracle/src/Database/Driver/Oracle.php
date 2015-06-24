<?php
/**
 * Oracle plug-in for CakePHP 3
 * Copyright (c) 2015 Government of Canada
 *
 * Unless otherwise noted, computer program source code of this plugin is 
 * covered under Crown Copyright, Government of Canada, and is distributed 
 * under the MIT License.
 * 
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2015 Government of Canada
 * @link          http://canada.ca
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Database\Driver;

use Cake\Database\Dialect\OracleDialectTrait;
use PDO;
//FIXME NEED TO PLUG PDO_OCI8 user driver in there

class Oracle extends \Cake\Database\Driver
{

    use PDODriverTrait;
    use OracleDialectTrait;

    /**
     * Base configuration settings for Postgres driver
     *
     * @var array
     */
    protected $_baseConfig = [
        /*'persistent' => true,
        'host' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'cake',
        'schema' => 'public',
        'port' => 5432,
        'encoding' => 'utf8',
        'timezone' => null,
        'flags' => [],
        'init' => [],*/
        
        'persistent' => false,
        'login' => 'oracleinst1',
        'password' => '',
        'sid' => '',
        'database' => '',
        'schema' => '',
        'hostname' => '127.0.0.1',
        'port' => '1521',
        'encoding' => true,
        'autocommit' => true,
        'driver_version' => 8
    ];

    /**
     * Establishes a connection to the database server
     *
     * @return bool true on success
     */
    public function connect()
    {
        if ($this->_connection) {
            return true;
        }
        $config = $this->_config;
        $config['flags'] += [
            PDO::ATTR_PERSISTENT => $config['persistent'],
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        if (empty($config['unix_socket'])) {
            $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
        } else {
            $dsn = "pgsql:dbname={$config['database']}";
        }

        $this->_connect($dsn, $config);
        $this->_connection = $connection = $this->connection();
        if (!empty($config['encoding'])) {
            $this->setEncoding($config['encoding']);
        }

        if (!empty($config['schema'])) {
            $this->setSchema($config['schema']);
        }

        if (!empty($config['timezone'])) {
            $config['init'][] = sprintf("SET timezone = %s", $connection->quote($config['timezone']));
        }

        foreach ($config['init'] as $command) {
            $connection->exec($command);
        }
        return true;
    }

    /**
     * Returns whether php is able to use this driver for connecting to database
     *
     * @return bool true if it is valid to use this driver
     */
    public function enabled()
    {
        return in_array('pgsql', PDO::getAvailableDrivers());
    }

    /**
     * Sets connection encoding
     *
     * @param string $encoding The encoding to use.
     * @return void
     */
    public function setEncoding($encoding)
    {
        $this->connect();
        $this->_connection->exec('SET NAMES ' . $this->_connection->quote($encoding));
    }

    /**
     * Sets connection default schema, if any relation defined in a query is not fully qualified
     * postgres will fallback to looking the relation into defined default schema
     *
     * @param string $schema The schema names to set `search_path` to.
     * @return void
     */
    public function setSchema($schema)
    {
        $this->connect();
        $this->_connection->exec('SET search_path TO ' . $this->_connection->quote($schema));
    }
}
