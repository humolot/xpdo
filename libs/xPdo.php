<?php
/*
*
* @ Pacote: xPdo - Construtor de Consultas e Classe PDO
* @ Autor: Humolot - @Gianck <xPdo>
* @ Web: http://hacklock.org
* @ URL: https://github.com/humolot/xpdo
* @ Licença: The MIT License (MIT) - Copyright (c) - http://opensource.org/licenses/MIT
*
*/

class xPdo
{
  public $pdo = null;

  protected $select     = '*';
  protected $from       = null;
  protected $where      = null;
  protected $limit      = null;
  protected $offset     = null;
  protected $join       = null;
  protected $orderBy    = null;
  protected $groupBy    = null;
  protected $having     = null;
  protected $grouped    = false;
  protected $numRows    = 0;
  protected $insertId   = null;
  protected $query      = null;
  protected $error      = null;
  protected $result     = [];
  protected $prefix     = null;
  protected $op         = ['=', "!=", '<', '>', "<=", ">=", "<>"];
  protected $cache      = null;
  protected $cacheDir   = null;
  protected $queryCount = 0;
  protected $debug      = true;
  protected $transactionCount = 0;

  public function __construct(Array $config)
  {
    $config["driver"]    = (isset($config["driver"]) ? $config["driver"] : "mysql");
    $config["host"]      = (isset($config["host"]) ? $config["host"] : "localhost");
    $config["charset"]   = (isset($config["charset"]) ? $config["charset"] : "utf8");
    $config["collation"] = (isset($config["collation"]) ? $config["collation"] : "utf8_general_ci");
    $config["port"]      = (strstr($config["host"], ':') ? explode(':', $config["host"])[1] : '');
    $this->prefix        = (isset($config["prefix"]) ? $config["prefix"] : '');
    $this->cacheDir      = (isset($config["cachedir"]) ? $config["cachedir"] : __DIR__ . "/cache/");
    $this->debug         = (isset($config["debug"]) ? $config["debug"] : true);

    $dsn = '';

    if ($config["driver"] == "mysql" || $config["driver"] == '' || $config["driver"] == "pgsql")
      $dsn = $config["driver"] . ":host=" . $config["host"] . ';'
            . (($config["port"]) != '' ? "port=" . $config["port"] . ';' : '')
            . "dbname=" . $config["database"];

    elseif ($config["driver"] == "sqlite")
      $dsn = "sqlite:" . $config["database"];

    elseif($config["driver"] == "oracle")
      $dsn = "oci:dbname=" . $config["host"] . '/' . $config["database"];

    try {
      $this->pdo = new PDO($dsn, $config["username"], $config["password"]);
      $this->pdo->exec("SET NAMES '" . $config["charset"] . "' COLLATE '" . $config["collation"] . "'");
      $this->pdo->exec("SET CHARACTER SET '" . $config["charset"] . "'");
      $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    } catch (PDOException $e) {
      die("<h1>Não é possível conectar-se ao banco de dados com o PDO.</h3><br /><br />" . $e->getMessage());
    }

    return $this->pdo;
  }

  public function table($table)
  {
    if(is_array($table)) {
      $f = '';
      foreach($table as $key)
        $f .= $this->prefix . $key . ", ";

      $this->from = rtrim($f, ", ");
    }
    else
      $this->from = $this->prefix . $table;

    return $this;
  }

  public function select($fields)
  {
    $select = (is_array($fields) ? implode(", ", $fields) : $fields);
    $this->select = ($this->select == '*' ? $select : $this->select . ", " . $select);

    return $this;
  }

  public function max($field, $name = null)
  {
    $func = "MAX(" . $field . ')' . (!is_null($name) ? " AS " . $name : '');
    $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

    return $this;
  }

  public function min($field, $name = null)
  {
    $func = "MIN(" . $field . ')' . (!is_null($name) ? " AS " . $name : '');
    $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

    return $this;
  }

  public function sum($field, $name = null)
  {
    $func = "SUM(" . $field . ')' . (!is_null($name) ? " AS " . $name : '');
    $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

    return $this;
  }

  public function count($field, $name = null)
  {
    $func = "COUNT(" . $field . ')' . (!is_null($name) ? " AS " . $name : '');
    $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

    return $this;
  }

  public function avg($field, $name = null)
  {
    $func = "AVG(" . $field . ')' . (!is_null($name) ? " AS " . $name : '');
    $this->select = ($this->select == '*' ? $func : $this->select . ", " . $func);

    return $this;
  }

  public function join($table, $field1 = null, $op = null, $field2 = null, $type = '')
  {
    $on = $field1;
    $table = $this->prefix . $table;

    if(!is_null($op))
      $on = (!in_array($op, $this->op) ? 
            $this->prefix . $field1 . " = " . $this->prefix . $op : 
            $this->prefix . $field1 . ' ' . $op . ' ' . $this->prefix . $field2);

    if (is_null($this->join))
      $this->join = ' ' . $type . "JOIN" . ' ' . $table . " ON " . $on;
    else
      $this->join = $this->join . ' ' . $type . "JOIN" . ' ' . $table . " ON " . $on;

    return $this;
  }

  public function innerJoin($table, $field1, $op = '', $field2 = '')
  {
    $this->join($table, $field1, $op, $field2, "INNER ");

    return $this;
  }

  public function leftJoin($table, $field1, $op = '', $field2 = '')
  {
    $this->join($table, $field1, $op, $field2, "LEFT ");

    return $this;
  }

  public function rightJoin($table, $field1, $op = '', $field2 = '')
  {
    $this->join($table, $field1, $op, $field2, "RIGHT ");

    return $this;
  }

  public function fullOuterJoin($table, $field1, $op = '', $field2 = '')
  {
    $this->join($table, $field1, $op, $field2, "FULL OUTER ");

    return $this;
  }

  public function leftOuterJoin($table, $field1, $op = '', $field2 = '')
  {
    $this->join($table, $field1, $op, $field2, "LEFT OUTER ");

    return $this;
  }

  public function rightOuterJoin($table, $field1, $op = '', $field2 = '')
  {
    $this->join($table, $field1, $op, $field2, "RIGHT OUTER ");

    return $this;
  }

  public function where($where, $op = null, $val = null, $type = '', $andOr = "AND")
  {
    if (is_array($where)) {
      $_where = [];

      foreach ($where as $column => $data)
        $_where[] = $type . $column . '=' . $this->escape($data);

      $where = implode(' ' . $andOr . ' ', $_where);
    }
    else {
      if(is_array($op)) {
        $x = explode('?', $where);
        $w = '';

        foreach($x as $k => $v)
          if(!empty($v))
            $w .= $type . $v . (isset($op[$k]) ? $this->escape($op[$k]) : '');

        $where = $w;
      }
      elseif (!in_array($op, $this->op) || $op == false)
        $where = $type . $where . " = " . $this->escape($op);
      else
        $where = $type . $where . ' ' . $op . ' ' . $this->escape($val);
    }

    if($this->grouped) {
      $where = '(' . $where;
      $this->grouped = false;
    }

    if (is_null($this->where))
      $this->where = $where;
    else
      $this->where = $this->where . ' ' . $andOr . ' ' . $where;

    return $this;
  }

  public function orWhere($where, $op = null, $val = null)
  {
    $this->where($where, $op, $val, '', "OR");

    return $this;
  }

  public function notWhere($where, $op = null, $val = null)
  {
    $this->where($where, $op, $val, "NOT ", "AND");

    return $this;
  }

  public function orNotWhere($where, $op = null, $val = null)
  {
    $this->where($where, $op, $val, "NOT ", "OR");

    return $this;
  }

  public function grouped(Closure $obj)
  {
    $this->grouped = true;
    call_user_func_array($obj, [$this]);
    $this->where .= ')';

    return $this;
  }

  public function in($field, Array $keys, $type = '', $andOr = "AND")
  {
    if (is_array($keys)) {
      $_keys = [];

      foreach ($keys as $k => $v)
        $_keys[] = (is_numeric($v) ? $v : $this->escape($v));

      $keys = implode(", ", $_keys);


      $where = $field . ' ' . $type . "IN (" . $keys . ')';

      if($this->grouped) {
        $where = '(' . $where;
        $this->grouped = false;
      }

      if (is_null($this->where))
        $this->where = $where;
      else
        $this->where = $this->where . ' ' . $andOr . ' ' . $where;
    }

    return $this;
  }

  public function notIn($field, Array $keys)
  {
    $this->in($field, $keys, "NOT ", "AND");

    return $this;
  }

  public function orIn($field, Array $keys)
  {
    $this->in($field, $keys, '', "OR");

    return $this;
  }

  public function orNotIn($field, Array $keys)
  {
    $this->in($field, $keys, "NOT ", "OR");

    return $this;
  }

  public function between($field, $value1, $value2, $type = '', $andOr = "AND")
  {

    $where = $field . ' ' . $type . "BETWEEN " . $this->escape($value1) . " AND " . $this->escape($value2);

    if($this->grouped) {
      $where = '(' . $where;
      $this->grouped = false;
    }

    if (is_null($this->where))
      $this->where = $where;
    else
      $this->where = $this->where . ' ' . $andOr . ' ' . $where;

    return $this;
  }

  public function notBetween($field, $value1, $value2)
  {
    $this->between($field, $value1, $value2, "NOT ", "AND");

    return $this;
  }

  public function orBetween($field, $value1, $value2)
  {
    $this->between($field, $value1, $value2, '', "OR");

    return $this;
  }

  public function orNotBetween($field, $value1, $value2)
  {
    $this->between($field, $value1, $value2, "NOT ", "OR");

    return $this;
  }

  public function like($field, $data, $type = '', $andOr = "AND")
  {
    $like = $this->escape($data);

    $where = $field . ' ' . $type . "LIKE " . $like;

    if($this->grouped) {
      $where = '(' . $where;
      $this->grouped = false;
    }

    if (is_null($this->where))
      $this->where = $where;
    else
      $this->where = $this->where . ' ' . $andOr . ' ' . $where;

    return $this;
  }

  public function orLike($field, $data)
  {
    $this->like($field, $data, '', "OR");

    return $this;
  }

  public function notLike($field, $data)
  {
    $this->like($field, $data, "NOT ", "AND");

    return $this;
  }

  public function orNotLike($field, $data)
  {
    $this->like($field, $data, "NOT ", "OR");

    return $this;
  }

  public function limit($limit, $limitEnd = null)
  {
    if (!is_null($limitEnd))
      $this->limit = $limit . ", " . $limitEnd;
    else
      $this->limit = $limit;

    return $this;
  }

  public function offset($offset)
  {
    $this->offset = $offset;

    return $this;
  }

  public function pagination($perPage, $page)
  {
    $this->limit = $perPage;
    $this->offset = ($page - 1) * $perPage;

    return $this;
  }

  public function orderBy($orderBy, $orderDir = null)
  {
    if (!is_null($orderDir))
      $this->orderBy = $orderBy . ' ' . strtoupper($orderDir);
    else {
      if(stristr($orderBy, ' ') || $orderBy == "rand()")
        $this->orderBy = $orderBy;
      else
        $this->orderBy = $orderBy . " ASC";
    }

    return $this;
  }

  public function groupBy($groupBy)
  {
    if(is_array($groupBy))
      $this->groupBy = implode(", ", $groupBy);
    else
      $this->groupBy = $groupBy;

    return $this;
  }

  public function having($field, $op = null, $val = null)
  {
    if(is_array($op)) {
      $x = explode('?', $field);
      $w = '';

      foreach($x as $k => $v)
        if(!empty($v))
          $w .= $v . (isset($op[$k]) ? $this->escape($op[$k]) : '');

      $this->having = $w;
    }

    elseif (!in_array($op, $this->op))
      $this->having = $field . " > " . $this->escape($op);
    else
      $this->having = $field . ' ' . $op . ' ' . $this->escape($val);

    return $this;
  }

  public function numRows()
  {
    return $this->numRows;
  }

  public function insertId()
  {
    return $this->insertId;
  }
	 
  public function thor($action, $string, $secret_key) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = $secret_key;
    $secret_iv = 'thoR0855ACXb8';
    // hash
    $key = hash('sha256', $secret_key);
    
    // iv - método de criptografia AES-256-CBC espera 16 bytes - senão você receberá um aviso
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encode' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decode' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
  }
 
  public function get_hash($length) {

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ' . rand(0, 99999);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    }	
	
	
  public function getLatLong($address){
    if(!empty($address)){
        //Formatted address
        $formattedAddr = str_replace(' ','+',$address);
        //Send request and receive json data by address
        $geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=false'); 
        $output = json_decode($geocodeFromAddr);
        //Get latitude and longitute from json data
        $data['latitude']  = $output->results[0]->geometry->location->lat; 
        $data['longitude'] = $output->results[0]->geometry->location->lng;
        //Return latitude and longitude of the given address
        if(!empty($data)){
            return $data;
        }else{
            return false;
        }
    }else{
        return false;   
    }
	}	
	
	public function text_limit($texto, $limite){
	  $contador = strlen($texto);
	  if ( $contador >= $limite ) {      
	      $texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
	      return $texto;
	  }
	  else{
	    return $texto;
	  }
	} 
	
	
	public function date($date,$format) {
		
		if($format == 'en') {
			return date("Y-m-d", strtotime($date));
		}
		if($format == 'br') {
			return date("d/m/Y", strtotime($date));
		}
		
	}
	public function datetime($date,$format) {
		
		if($format == 'en') {
			return date("Y-m-d H:i:s", strtotime($date));
		}
		if($format == 'br') {
			return date("d/m/Y H:i:s", strtotime($date));
		}
		
	}

	
   public function error()
  	{
  	
	  function erro_sql($string)
	  {
	  $string = str_replace('You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near','Você tem um erro na sua sintaxe SQL; Verifique o manual que corresponde à sua versão do servidor MySQL',$string);
	  $string = str_replace('at line','na linha',$string);
	  $string = str_replace("doesn't exist",'não existe',$string);
	  $string = str_replace("Table",'Tabela',$string);
	  $string = str_replace("Unknown column",'Coluna desconhecida',$string);
	  $string = str_replace("field list",'listas de CAMPOS',$string);
	  $string = str_replace("where clause",'cláusula WHERE',$string);
	  $string = str_replace("in",'em',$string);

	  return $string;

	  } 	
  	
    $msg = '<h1>Erro do Banco de Dados</h1>';
    $msg .= '<h4>Query: <em style="font-weight:normal;">"'.$this->query.'"</em></h4>';
    $msg .= '<h4>Erro: <em style="font-weight:normal;">'.erro_sql($this->error).'</em></h4>';

    if($this->debug === true)
      die($msg);
    else
      throw new PDOException($this->error . ". ("  . $this->query . ")");
  }

  public function get($type = false)
  {
    $this->limit = 1;
    $query = $this->getAll(true);

    if($type === true)
      return $query;
    else
      return $this->query( $query, false, (($type == "array") ? true : false) );
  }

  public function getAll($type = false)
  {
    $query = "SELECT " . $this->select . " FROM " . $this->from;

    if (!is_null($this->join))
      $query .= $this->join;

    if (!is_null($this->where))
      $query .= " WHERE " . $this->where;

    if (!is_null($this->groupBy))
      $query .= " GROUP BY " . $this->groupBy;

    if (!is_null($this->having))
      $query .= " HAVING " . $this->having;

    if (!is_null($this->orderBy))
      $query .= " ORDER BY " . $this->orderBy;

    if (!is_null($this->limit))
      $query .= " LIMIT " . $this->limit;

    if (!is_null($this->offset))
      $query .= " OFFSET " . $this->offset;

    if($type === true)
      return $query;
    else
      return $this->query( $query, true, (($type == "array") ? true : false) );
  }

  public function insert($data, $type = false)
  {
    $query = "INSERT INTO " . $this->from;

    $values = array_values($data);
    if(isset($values[0]) && is_array($values[0])) {
      $column = implode(", ", array_keys($values[0]));
      $query .= " (" . $column . ") VALUES ";
      foreach($values as $value) {
        $val = implode(", ", array_map([$this, "escape"], $value));
        $query .= "(" . $val . "), ";
      }
      $query = trim($query, ", ");
    } 
    else {
      $column = implode(',', array_keys($data));
      $val = implode(", ", array_map([$this, "escape"], $data));
      $query .= " (" . $column . ") VALUES (" . $val . ")";
    }

    if($type === true)
      return $query;

    $query = $this->query($query);

    if ($query) {
      $this->insertId = $this->pdo->lastInsertId();
      return $this->insertId();
    }

    return false;
  }

  public function update($data, $type = false)
  {
    $query = "UPDATE " . $this->from . " SET ";
    $values = [];

    foreach ($data as $column => $val)
      $values[] = $column . '=' . $this->escape($val);

    $query .= (is_array($data) ? implode(',', $values) : $data);

    if (!is_null($this->where))
      $query .= " WHERE " . $this->where;

    if (!is_null($this->orderBy))
      $query .= " ORDER BY " . $this->orderBy;

    if (!is_null($this->limit))
      $query .= " LIMIT " . $this->limit;

    if($type === true)
      return $query;

    return $this->query($query);
  }

  public function delete($type = false)
  {
    $query = "DELETE FROM " . $this->from;

    if (!is_null($this->where))
      $query .= " WHERE " . $this->where;

    if (!is_null($this->orderBy))
      $query .= " ORDER BY " . $this->orderBy;

    if (!is_null($this->limit))
      $query .= " LIMIT " . $this->limit;

    if($query == "DELETE FROM " . $this->from)
      $query = "TRUNCATE TABLE " . $this->from;

    if($type === true)
      return $query;

    return $this->query($query);
  }

  public function analyze()
  {
    return $this->query("ANALYZE TABLE " . $this->from);
  }

  public function check()
  {
    return $this->query("CHECK TABLE " . $this->from);
  }

  public function checksum()
  {
    return $this->query("CHECKSUM TABLE " . $this->from);
  }

  public function optimize()
  {
    return $this->query("OPTIMIZE TABLE " . $this->from);
  }

  public function repair()
  {
    return $this->query("REPAIR TABLE " . $this->from);
  }

  public function transaction()
  {
    if (!$this->transactionCount++) 
      return $this->pdo->beginTransaction();

    $this->pdo->exec("SAVEPOINT trans" . $this->transactionCount);
    return $this->transactionCount >= 0;
  }

  public function commit()
  {
    if (!--$this->transactionCount)
      return $this->pdo->commit();
    
    return $this->transactionCount >= 0;
  }

  public function rollBack()
  {
    if (--$this->transactionCount) {
      $this->pdo->exec('ROLLBACK TO trans'.$this->transactionCount + 1);
      return true;
    }
    return $this->pdo->rollBack();
  }

  public function query($query, $all = true, $array = false)
  {
    $this->reset();

    if(is_array($all)) {
      $x = explode('?', $query);
      $q = '';

      foreach($x as $k => $v)
        if(!empty($v))
          $q .= $v . (isset($all[$k]) ? $this->escape($all[$k]) : '');

      $query = $q;
    }

    $this->query = preg_replace("/\s\s+|\t\t+/", ' ', trim($query));

    $str = false;
    foreach (["select", "optimize", "check", "repair", "checksum", "analyze"] as $value) {
      if(stripos($this->query, $value) === 0) {
        $str = true;
        break;
      }
    }

    $cache = false;
    if (!is_null($this->cache))
      $cache = $this->cache->getCache($this->query, $array);

    if (!$cache && $str) {
      $sql = $this->pdo->query($this->query);

      if ($sql) {
        $this->numRows = $sql->rowCount();

        if (($this->numRows > 0)) {
          if ($all) {
            $q = [];

            while ($result = ($array == false) ? $sql->fetchAll(PDO::FETCH_OBJ) : $sql->fetchAll(PDO::FETCH_ASSOC))
              $q[] = $result;

            $this->result = $q[0];
          }
          else {
            $q = ($array == false) ? $sql->fetch(PDO::FETCH_OBJ) : $sql->fetch(PDO::FETCH_ASSOC);
            $this->result = $q;
          }
        }

        if (!is_null($this->cache))
          $this->cache->setCache($this->query, $this->result);

        $this->cache = null;
      }
      else {
        $this->cache = null;
        $this->error = $this->pdo->errorInfo();
        $this->error = $this->error[2];

        return $this->error();
      }
    }
    elseif ((!$cache && !$str) || ($cache && !$str)) {
      $this->cache = null;
      $this->result = $this->pdo->exec($this->query);

      if ($this->result === false) {
        $this->error = $this->pdo->errorInfo();
        $this->error = $this->error[2];

        return $this->error();
      }
    }
    else {
      $this->cache = null;
      $this->result = $cache;
      $this->numRows = count($this->result);
    }

    $this->queryCount++;

    return $this->result;
  }

  public function escape($data)
  {
    if($data === NULL)
      return 'NULL';

    if(is_null($data))
      return null;

    return $this->pdo->quote(trim($data));
  }

  public function cache($time)
  {
    $this->cache = new Cache($this->cacheDir, $time);

    return $this;
  }

  public function queryCount()
  {
    return $this->queryCount;
  }

  public function getQuery()
  {
    return $this->query;
  }

  protected function reset()
  {
    $this->select   = '*';
    $this->from     = null;
    $this->where    = null;
    $this->limit    = null;
    $this->offset   = null;
    $this->orderBy  = null;
    $this->groupBy  = null;
    $this->having   = null;
    $this->join     = null;
    $this->grouped  = false;
    $this->numRows  = 0;
    $this->insertId = null;
    $this->query    = null;
    $this->error    = null;
    $this->result   = [];
    $this->transactionCount = 0;

    return;
  }

  function __destruct()
  {
    $this->pdo = null;
  }
}
