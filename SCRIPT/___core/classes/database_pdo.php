<?php

/**
 * This is the database interface class of the framework
 * @package MMExtranet
 */

class database_pdo {

    /**
     * SQL Server connection resource
     * @var object
     */
    private $link = null;

    /**
     * PDO prepared query reference
     * @var array
     */
    public $pdoReference = null;

    /**
     * array of queries run against this object
     * @var array
     */
    public $queries = array();

    /**
     * any errors resulting from queries
     * @var array
     */
    public $errors = array();

    /**
     * This method creates a connection to a database
     * @access public
     * @param string $dsn the connection DSN
     * @param string $username
     * @param string $password
     * @param bool $dieOnFailure to die or return bool on connection failure
     * @return mixed
     */
    public function connect(string $dsn, string $username, string $password, bool $dieOnFailure = true) {
        $return = true;

        try {
            $this->link = new PDO($dsn, $username, $password, array(PDO::ATTR_TIMEOUT => 3, PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT));
        }
        catch (PDOException $e) {
            $return = false;
            $this->errors[] = $e->getMessage();
        }

        if ($return === true) {
            return true;
        }
        else {
            if ($dieOnFailure === true) {
                die(end($this->errors));
            }
            else {
                return false;
            }
        }
    }

    /**
     * This method closes the PDO connection
     * @access public
     */
    public function closeConnection() {
        $this->link = null;
        unset($this->link);
    }

    /**
     * Start a PDO Transaction
     * @access public
     * @return bool
     */
    public function beginTransaction(): bool {
        return $this->link->beginTransaction();
    }

    /**
     * Commits a PDO transaction
     * @access public
     * @return bool
     */
    public function commitTransaction(): bool {
        return $this->link->commit();
    }

    /**
     * Rolls back the last transaction
     * @access public
     * @return bool
     */
    public function rollBackTransaction(): bool {
        return $this->link->rollBack();
    }

    /**
     * This method executes a query and returns the raw result resource
     * @access public
     * @param string $sql string query
     * @param array $parameters for PDO query
     * @param bool $closeCursor whether or not to close pdo cursor after query
     * @return object raw SQL result resource
     */
    public function justquery(string $sql, array $parameters = array(), bool $closeCursor = true): bool {
        $this->pdoReference = $this->link->prepare($sql);
        $this->queries[] = $this->pdoReference;

        foreach ($parameters as $key => $val) {
            switch (mb_substr($key, 0, 5)) {
                default:
                case ':str_':
                    $this->pdoReference->bindValue($key, $val, \PDO::PARAM_STR);
                    break;

                case ':int_':
                    $this->pdoReference->bindValue($key, $val, \PDO::PARAM_INT);
                    break;

                case ':bool_':
                    $this->pdoReference->bindValue($key, $val, \PDO::PARAM_BOOL);
                    break;

                case ':null_':
                    $this->pdoReference->bindValue($key, $val, \PDO::PARAM_NULL);
                    break;

                case ':lob_':
                    $this->pdoReference->bindValue($key, $val, \PDO::PARAM_LOB);
                    break;
            }
        }

        $return = $this->pdoReference->execute();

        if ($return === false) {
            $tmp = $this->pdoReference->errorInfo();
            $this->errors[] = $tmp[2];
            unset($tmp);
        } else {}

        if ($closeCursor === true) {
            $this->pdoReference->closeCursor();
            $this->pdoReference = null;
        } else {}
        return $return;
    }

    /**
     * This method loads the first value of the first column of the first row of results
     * @access public
     * @param string $sql string query
     * @param array $parameters for PDO query
     * @return mixed result from first column of first row of query results
     */
    public function loadResult(string $sql, array $parameters = array()) {

        if (!($cur = $this->justquery($sql, $parameters, false))) {
            $ret = false;
        }
        else {
            if ($row = $this->pdoReference->fetch(PDO::FETCH_NUM)) {
                $ret = $row[0];
            }
            else {
                $ret = false;
            }
        }


        $this->closeCursor();
        return $ret;
    }

    /**
     * This method returns the first row of results
     * @access public
     * @param string $sql string query
     * @param array $parameters for PDO query
     * @return mixed object first row of results
     */
    public function loadFirstRow(string $sql, array $parameters = array()) {

        if (!($cur = $this->justquery($sql, $parameters, false))) {
            $ret = false;
        }
        else {
            if ($row = $this->pdoReference->fetch(PDO::FETCH_OBJ)) {
                $ret = $row;
            }
            else {
                $ret = false;
            }
        }

        $this->closeCursor();
        return $ret;
    }

    /**
     * This method returns the auto-increment value from the last query run
     * @access public
     * @return int auto-incremeted (primary key) value of last query
     */
    public function insertid(): int {
        return $this->link->lastInsertId();
    }

    /**
     * This method returns the number of affected rows
     * @access public
     * @param bool $closeCursor whether or not to close pdo cursor after query
     * @return int number affected rows
     */
    public function numAffectedRows(bool $closeCursor = true): int {
        $result = $this->pdoReference->rowCount();
        if ($closeCursor === true) {
            $this->pdoReference->closeCursor();
            $this->pdoReference = null;
        } else {}

        return $result;
    }

    /**
     * This method returns the error information from last failure
     * @access public
     * @return string error info
     */
    public function getErrorInfo() {
        return $this->link->errorInfo();
    }

    /**
     * This method queries the database, logs data, and returns results
     * @access public
     * @param string $sql single string query to run
     * @param array $parameters for PDO query
     * @param string $key if supplied, each group of results will be indexed with its respective $key's column value as its object index/position
     * @return unset|object depending on $returns, could be nothing, or an object of query results
     */
    public function query(string $sql, array $parameters = array(), string $key = '') {
        $result = array();
        $answer = $this->justquery($sql, $parameters, false);

        if ($answer === false) {
            $this->errors[] = array($sql, $this->link->errorInfo());
            $result = false;
        }
        else {
            if ($this->pdoReference->columnCount() > 0){
                while ($row = $this->pdoReference->fetch(PDO::FETCH_OBJ)) {
                    if ($key != ''){
                        $result[$row->$key] = $row;
                    }
                    else {
                        $result[] = $row;
                    }
                }
            } else {}
        }

        $this->pdoReference->closeCursor();
        $this->pdoReference = null;

        return $result;
    }

    /**
     * This method generates a hash of the query and parameters, useful for caching
     * @access public
     * @param string $sql single string query to run
     * @param array $parameters for PDO query
     * @return string hash of query and parameters
     */
    public function queryID(string $sql, array $parameters = array()): string {
        $input = mb_eregi_replace("\n", ' ', $sql);
        foreach ($parameters as $k => $v) {
            $input .= $k . $v;
        }
        return sha1($input);
    }

    /**
     * This method attempts to close the pdo cursor
     * @access public
     */
    public function closeCursor() {
        $this->pdoReference->closeCursor();
        $this->pdoReference = null;
    }
}
?>