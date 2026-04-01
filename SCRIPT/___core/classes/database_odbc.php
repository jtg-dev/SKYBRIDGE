<?php

/**
 * This is an ODBC database class
 * @package MMExtranet
 */

class database_odbc {

    /**
     * SQL Server connection resource
     * @var object
     */
    private $link = null;

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
     * @return mixed
     */
    public function connect(string $dsn, string $username, string $password) {
        $this->link = odbc_connect($dsn, $username, $password);
        return $this->link;
    }

    /**
     * This method kills the connection
     * @access public
     * @author Matt Ford
     */
    public function closeConnection() {
        odbc_close($this->link);
    }

    /**
     * This method executes a query and returns the raw result resource
     * @access public
     * @author Matt Ford
     * @param string $sql string query
     * @return object raw SQL result resource
     */
    public function justquery($sql) {
        $this->queries[] = $sql;
        return odbc_exec($this->link, $sql);
    }

    /**
     * This method loads the first value of the first column of the first row of results
     * @access public
     * @author Matt Ford
     * @param string $sql string query
     * @return string result from first column of first row of query results
     */
    public function loadResult($sql) {
        if (!($cur = $this->justquery($sql))) {
            return false;
        }
        $ret = false;
        if ($row = odbc_fetch_row($cur)) {
            $ret = odbc_result($cur, 1);
        }
        odbc_free_result($cur);
        return $ret;
    }

    /**
     * This method returns the first row of results
     * @access public
     * @author Matt Ford
     * @param string $sql string query
     * @return object first row of results
     */
    public function loadFirstRow($sql) {
        if (!($cur = $this->justquery($sql))) {
            return false;
        }
        $ret = false;
        if ($row = odbc_fetch_array($cur)) {
            $ret = $row;
        }
        odbc_free_result($cur);
        return $ret;
    }

    /**
     * This method queries the database, logs data, and returns results
     * @access public
     * @author Matt Ford
     * @param string|array $sql depending on $batch flag, could be a single string query or an array of queries to run
     * @param string $key if supplied, each group of results will be indexed with its respective $key's column value as its object index/position
     * @param bool $returns determins if any results will be returned or not, merely for I/O
     * @param bool $batch flag denoting whether $sql is a string query or an array of queries to loop over
     * @return unset|object depending on $returns, could be nothing, or an object of query results
     */
    public function query($sql, $key = "", $returns = true, $batch = false) {
        $sqls = $result = array ();

        switch ($batch) {
            default:
            case true:
                foreach ($sql as $index => $query) {
                    $answer = $this->justquery($query);

                    if (!$answer) {
                        $this->errors[] = odbc_errormsg($this->link);
                    }
                    else {
                        if ($returns != false) {
                            //if (odbc_num_rows($answer) > 0){
                                while ($row = odbc_fetch_array($answer)) {
                                    if ($key != ""){
                                        $result[$index][$row[$key]] = $row;
                                    }
                                    else {
                                        $result[$index][] = $row;
                                    }
                                }
                                odbc_free_result($answer);
                            //} else {}
                        } else {}
                    }
                }
                break;

            case false:
                $answer = $this->justquery($sql);

                if (!$answer) {
                    $this->errors[] = odbc_errormsg($this->link);
                    $result = false;
                }
                else {
                    if ($returns != false) {
                        //var_dump(odbc_num_rows($answer));
                        //if (odbc_num_rows($answer) > 0){
                            while ($row = odbc_fetch_array($answer)) {
                                if ($key != ""){
                                    $result[$row[$key]] = $row;
                                }
                                else {
                                    $result[] = $row;
                                }
                            }
                            odbc_free_result($answer);
                        //} else {}
                    }
                    else {
                        $result = true;
                    }
                }
                break;
        }

        return $result;
    }
}
?>