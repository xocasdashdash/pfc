<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace UAH\GestorActividadesBundle\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class DoctrineSessionHandler implements \SessionHandlerInterface
{
    /**
     *
     * @var array Parametros de conexión de Doctrine a la base de datos
     */
    private $dbalConnection;

    /**
     *
     * @var Entity Entidad que uso para guardar los datos en la BD
     */
    private $table;

    /**
     * @var string Column for session id
     */
    private $idCol;

    /**
     * @var string Column for session data
     */
    private $dataCol;

    /**
     * @var string Column for timestamp
     */
    private $timeCol;

    /**
     * Constructor.
     *
     * List of available options:
     *  * db_table: The name of the table [required]
     *  * db_id_col: The column where to store the session id [default: sess_id]
     *  * db_data_col: The column where to store the session data [default: sess_data]
     *  * db_time_col: The column where to store the timestamp [default: sess_time]
     *
     * @param array Doctrine connection parameters to the database
     * @param array $dbOptions An associative array of DB options
     *
     * @throws \InvalidArgumentException When "doctrine_entity" option is not provided
     */
    public function __construct(Connection $dbalConnection, array $dbOptions = array())
    {
        if (!array_key_exists('db_table', $dbOptions)) {
            throw new \InvalidArgumentException('You must provide the "db_table" option for a DoctrineSessionStorage.');
        }
        $this->dbalConnection = $dbalConnection; //->getParams();

        $dbOptions = array_merge(array(
            'db_id_col' => 'sess_id',
            'db_data_col' => 'sess_data',
            'db_time_col' => 'sess_time',
                ), $dbOptions);

        $this->table = $dbOptions['db_table'];
        $this->idCol = $dbOptions['db_id_col'];
        $this->dataCol = $dbOptions['db_data_col'];
        $this->timeCol = $dbOptions['db_time_col'];
    }

    /**
     * {@inheritdoc}
     */
    public function open($savePath, $sessionName)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function destroy($sessionId)
    {
        // delete the record associated with this id
        $sql = "DELETE FROM $this->table WHERE $this->idCol = :id";

        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':id', $sessionId, "string");
            $stmt->execute();
        } catch (Exception $e) {
            throw new \RuntimeException(sprintf('Exception was thrown when trying to delete a session: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function gc($maxlifetime)
    {
        // delete the session records that have expired
        $sql = "DELETE FROM $this->table WHERE $this->timeCol < :time";

        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':time', time() - $maxlifetime, "integer");
            $stmt->execute();
        } catch (Exception $e) {
            throw new \RuntimeException(sprintf('Exception was thrown when trying to delete expired sessions: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function read($sessionId)
    {
        $sql = "SELECT $this->dataCol FROM $this->table WHERE $this->idCol = :id";
        try {
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
            $stmt->execute();
            $sessionRows = $stmt->fetchAll(\PDO::FETCH_NUM);

            if ($sessionRows) {
                return base64_decode($sessionRows[0][0]);
            }

            return '';
        } catch (Exception $e) {
            throw new \RuntimeException(sprintf('Exception was thrown when trying to read the session data: %s', $e->getMessage()), 0, $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write($sessionId, $data)
    {
        // Session data can contain non binary safe characters so we need to encode it.
        $encoded = base64_encode($data);

        // We use a MERGE SQL query when supported by the database.
        // Otherwise we have to use a transactional DELETE followed by INSERT to prevent duplicate entries under high concurrency.
        try {
            $mergeSql = $this->getMergeSql();
            if (null !== $mergeSql) {
                $mergeStmt = $this->getConnection()->prepare($mergeSql);
                $mergeStmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
                $mergeStmt->bindParam(':data', $encoded, \PDO::PARAM_STR);
                $mergeStmt->bindValue(':time', time(), \PDO::PARAM_INT);
                try {
                    $mergeStmt->execute();
                } catch (Exception $e) {
                    throw $e;
                }

                return true;
            }
            $this->getConnection()->beginTransaction();

            try {
                $deleteStmt = $this->getConnection()->prepare(
                        "DELETE FROM $this->table WHERE $this->idCol = :id"
                );
                $deleteStmt->bindParam(':id', $sessionId, "string");
                $deleteStmt->execute();

                $insertStmt = $this->getConnection()->prepare(
                        "INSERT INTO $this->table ($this->idCol, $this->dataCol, $this->timeCol) VALUES (:id, :data, :time)"
                );
                $insertStmt->bindParam(':id', $sessionId, \PDO::PARAM_STR);
                $insertStmt->bindParam(':data', $encoded, \PDO::PARAM_STR);
                $insertStmt->bindValue(':time', time(), \PDO::PARAM_INT);
                $insertStmt->execute();

                $this->getConnection()->commit();
            } catch (Exception $e) {
                $this->getConnection()->rollback();
                throw $e;
            }
        } catch (Exception $e) {
            throw new \RuntimeException(sprintf('Exception was thrown when trying to write the session data: %s', $e->getMessage()), 0, $e);
        }

        return true;
    }

    /**
     * Returns a merge/upsert (i.e. insert or update) SQL query when supported by the database.
     *
     * @return string|null The SQL string or null when not supported
     */
    private function getMergeSql()
    {
        $driver = $this->getConnection()->getDriver()->getName();
        switch ($driver) {
            case 'pdo_mysql':
                return "INSERT INTO $this->table ($this->idCol, $this->dataCol, $this->timeCol) VALUES (:id, :data, :time) " .
                        "ON DUPLICATE KEY UPDATE $this->dataCol = VALUES($this->dataCol), $this->timeCol = VALUES($this->timeCol)";
            case 'oci8':
                // DUAL is Oracle specific dummy table
                //Bug de Oracle al hacer merge con clob
                return;

                return "MERGE INTO $this->table USING DUAL ON ($this->idCol = :id) " .
                        "WHEN NOT MATCHED THEN INSERT ($this->idCol, $this->dataCol, $this->timeCol) VALUES (:id, :data, :time) " .
                        "WHEN MATCHED THEN UPDATE SET $this->dataCol = :data";
            case 'sqlsrv':
                // MS SQL Server requires MERGE be terminated by semicolon
                return "MERGE INTO $this->table USING (SELECT 'x' AS dummy) AS src ON ($this->idCol = :id) " .
                        "WHEN NOT MATCHED THEN INSERT ($this->idCol, $this->dataCol, $this->timeCol) VALUES (:id, :data, :time) " .
                        "WHEN MATCHED THEN UPDATE SET $this->dataCol = :data;";
            case 'pdo_sqlite':
                return "INSERT OR REPLACE INTO $this->table ($this->idCol, $this->dataCol, $this->timeCol) VALUES (:id, :data, :time)";
        }
    }

    /**
     * Return a PDO instance
     *
     * @return \PDO
     */
    protected function getConnection()
    {
        //$connection = DriverManager::getConnection($this->dbalConnectionParameters);
        return $this->dbalConnection;
    }
}
