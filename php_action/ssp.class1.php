<?php

class SSP_ONE
{
	static function data_output($columns, $data, $orderBy = null)
	{
		$out = array();

		for ($i = 0, $ien = count($data); $i < $ien; $i++) {


			$row = array();

			// echo json_encode($j) .'<br >';

			for ($j = 0, $jen = count($columns); $j < $jen; $j++) {

				$column = $columns[$j];

				if (isset($column['formatter'])) {

					if (empty($column['db'])) {

						$row[$column['dt']] = $column['formatter']($data);
					} else {

						$row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);

					}
				} else {

					if (!empty($column['db'])) {

						$row[$column['dt']] = $data[$i][$columns[$j]['dt']];
					} else {

						$row[$column['dt']] = "";
					}
				}
			}

			$out[] = $row;
		}
		if ($orderBy) {
			$arrSort = array();
			foreach ($orderBy as $value) {
				$columns = array_column($out, $value[0]);
				$sorting = $value[1];
				$arrSort[] = $columns;
				$arrSort[] = constant($sorting);
				$arrSort[] =  $value[0] == '4' ?  SORT_STRING : SORT_NUMERIC;
			}
			$arrParams = array_merge($arrSort, array(&$out));
			call_user_func_array('array_multisort', $arrParams);
		}
		return $out;
	}


	static function db($conn)
	{
		if (is_array($conn)) {
			return self::sql_connect($conn);
		}

		return $conn;
	}


	static function limit($request, $columns)
	{
		$limit = '';

		if (isset($request['start']) && $request['length'] != -1) {
			$limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
		}

		return $limit;
	}


	static function order($request, $columns)
	{
		$order = '';

		if (isset($request['order']) && count($request['order'])) {
			$orderBy = array();
			$dtColumns = self::pluck($columns, 'dt');

			for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];

				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];

				if ($requestColumn['orderable'] == 'true') {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' : 'DESC';

					$orderBy[] = '`' . $column['db'] . '` ' . $dir;
				}
			}

			if (count($orderBy)) {
				$order = 'ORDER BY ' . implode(', ', $orderBy);
			}
		}

		return $order;
	}


	static function filter($request, $columns, &$bindings)
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = self::pluck($columns, 'dt');

		if (isset($request['search']) && $request['search']['value'] != '') {
			$str = $request['search']['value'];

			for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];

				if ($requestColumn['searchable'] == 'true') {
					if (!empty($column['db'])) {
						$binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
						$globalSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
					}
				}
			}
		}

		// Individual column filtering
		if (isset($request['columns'])) {
			for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];

				$str = $requestColumn['search']['value'];

				if (
					$requestColumn['searchable'] == 'true' &&
					$str != ''
				) {
					if (!empty($column['db'])) {
						$binding = self::bind($bindings, '%' . $str . '%', PDO::PARAM_STR);
						$columnSearch[] = "`" . $column['db'] . "` LIKE " . $binding;
					}
				}
			}
		}

		// Combine the filters into a single string
		$where = '';

		if (count($globalSearch)) {
			$where = '(' . implode(' OR ', $globalSearch) . ')';
		}

		if (count($columnSearch)) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) : $where . ' AND ' . implode(' AND ', $columnSearch);
		}

		if ($where !== '') {
			$where = 'WHERE ' . $where;
		}

		return $where;
	}

	static function simple($request, $conn, $table, $primaryKey, $columns)
	{
		$bindings = array();
		$db = self::db($conn);

		// Build the SQL query string from the request
		$limit = self::limit($request, $columns);
		$order = self::order($request, $columns);
		$where = self::filter($request, $columns, $bindings);

		// Main query to actually get the data
		$data = self::sql_exec(
			$db,
			$bindings,
			"SELECT `" . implode("`, `", self::pluck($columns, 'db')) . "`
			 FROM {$table}
			 $where
			 $order
			 $limit"
		);

		// Data set length after filtering
		$resFilterLength = self::sql_exec(
			$db,
			$bindings,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   {$table}
			 $where"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Total data set length
		$resTotalLength = self::sql_exec(
			$db,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   {$table}"
		);
		$recordsTotal = $resTotalLength[0][0];

		/*
		 * Output
		 */
		return array(
			"draw"            => isset($request['draw']) ?
				intval($request['draw']) : 0,
			"recordsTotal"    => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data"            => self::data_output($columns, $data)
		);
	}


	static function complex($request, $conn, $table, $primaryKey, $columns, $whereResult = null, $whereAll = null)
	{
		$bindings = array();
		$db = self::db($conn);
		$localWhereResult = array();
		$localWhereAll = array();
		$whereAllSql = '';

		// Build the SQL query string from the request
		$limit = self::limit($request, $columns);
		$order = self::order($request, $columns);
		$where = self::filter($request, $columns, $bindings);

		$whereResult = self::_flatten($whereResult);
		$whereAll = self::_flatten($whereAll);

		if ($whereResult) {
			$where = $where ?
				$where . ' AND ' . $whereResult : 'WHERE ' . $whereResult;
		}

		if ($whereAll) {
			$where = $where ?
				$where . ' AND ' . $whereAll : 'WHERE ' . $whereAll;

			$whereAllSql = 'WHERE ' . $whereAll;
		}

		// Main query to actually get the data
		// $data = self::sql_exec(
		// 	$db,
		// 	$bindings,
		// 	"SELECT `" . implode("`, `", self::pluck($columns, 'db')) . "`
		// 	 FROM {$table}
		// 	 $where
		// 	 $order
		// 	 $limit"
		// );

		$data = self::sql_exec(
			$db,
			$bindings,
			"SELECT *
			 FROM {$table}
			 $where
			 $order
			 $limit"
		);


		// Data set length after filtering
		$resFilterLength = self::sql_exec(
			$db,
			$bindings,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   {$table}
			 $where"
		);
		$recordsFiltered = $resFilterLength[0][0];

		// Total data set length
		$resTotalLength = self::sql_exec(
			$db,
			$bindings,
			"SELECT COUNT(`{$primaryKey}`)
			 FROM   {$table} " .
				$whereAllSql
		);
		$recordsTotal = $resTotalLength[0][0];

		/*
		 * Output
		 */
		return array(
			"draw"            => isset($request['draw']) ?
				intval($request['draw']) : 0,
			"recordsTotal"    => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data"            => self::data_output($columns, $data, isset($request['orderBy']) ? $request['orderBy'] : null)
		);
	}



	static function sql_connect($sql_details)
	{
		try {
			$db = @new PDO(
				"mysql:host={$sql_details['host']};dbname={$sql_details['db']}",
				$sql_details['user'],
				$sql_details['pass'],
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
			);
		} catch (PDOException $e) {
			self::fatal(
				"An error occurred while connecting to the database. " .
					"The error reported by the server was: " . $e->getMessage()
			);
		}

		return $db;
	}


	static function sql_exec($db, $bindings, $sql = null)
	{
		// Argument shifting
		if ($sql === null) {
			$sql = $bindings;
		}

		// echo $sql . '<hr />';
		$stmt = $db->prepare($sql);
		// print_r($stmt) . '<hr />';
		// var_dump($stmt) . '<hr />';

		// Bind parameters
		if (is_array($bindings)) {
			for ($i = 0, $ien = count($bindings); $i < $ien; $i++) {
				$binding = $bindings[$i];
				$stmt->bindValue($binding['key'], $binding['val'], $binding['type']);
			}
		}

		// Execute
		try {
			$stmt->execute();
		} catch (PDOException $e) {
			self::fatal("An SQL error occurred: " . $e->getMessage());
		}

		// Return all
		// $data =  $stmt->fetchAll(PDO::FETCH_BOTH);
		// print_r($data) . "<hr />";
		// return $data;
		return $stmt->fetchAll(PDO::FETCH_BOTH);
	}


	static function fatal($msg)
	{
		echo json_encode(array(
			"error" => $msg
		));

		exit(0);
	}


	static function bind(&$a, $val, $type)
	{
		$key = ':binding_' . count($a);

		$a[] = array(
			'key' => $key,
			'val' => $val,
			'type' => $type
		);

		return $key;
	}


	static function pluck($a, $prop)
	{
		$out = array();

		for ($i = 0, $len = count($a); $i < $len; $i++) {
			if (empty($a[$i][$prop])) {
				continue;
			}
			//removing the $out array index confuses the filter method in doing proper binding,
			//adding it ensures that the array data are mapped correctly
			$out[$i] = $a[$i][$prop];
		}

		return $out;
	}


	static function _flatten($a, $join = ' AND ')
	{
		if (!$a) {
			return '';
		} else if ($a && is_array($a)) {
			return implode($join, $a);
		}
		return $a;
	}
}
