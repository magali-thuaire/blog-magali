<?php

namespace Core\Database;

class QueryBuilder
{

	private $select = [];
	private $count = [];
	private $where = [];
	private $from;
	private $innerJoin = [];
	private $leftJoin = [];
	private $orderBy = [];
	private $groupBy = [];
	private $insert;
	private $columns = [];

	public function select() {
		$this->select = func_get_args();
		return $this;
	}

	public function addSelect() {
		$this->select = array_merge($this->select, func_get_args());
		return $this;
	}

	public function where() {
		$this->where = func_get_args();
		return $this;
	}

	public function andWhere() {
		$this->where = array_merge($this->where, func_get_args());
		return $this;
	}

	public function orderBy($prop, $direction) {
		$this->orderBy[] = $prop . ' ' . $direction;
		return $this;
	}

	public function from($table, $alias) {

		$this->from = $table . ' ' . $alias;

		return $this;
	}

	public function innerJoin($table, $alias, $join) {

		$this->innerJoin[] = $table . ' ' . $alias . ' ON ' . $join;

		return $this;
	}

	public function leftJoin($table, $alias, $join, $where = null) {
		if($where) {
			$where = str_replace($alias . '.', '', $where);
			$this->leftJoin[] = '(SELECT * FROM ' . $table .  ' WHERE ' . $where . ') ' . $alias . ' ON ' . $join;
		} else {
			$this->leftJoin[] = $table . ' ' . $alias . ' ON ' . $join;
		}
		return $this;
	}

	public function addCount($count, $alias, $groupBy)
	{
		$this->select[] = 'count(' . $count . ') as ' .$alias;
		$this->groupBy[] = $groupBy;
		return $this;
	}

	public function insert($table)
	{
		$this->insert = $table;
		return $this;
	}

	public function values($values)
	{
		$this->columns = func_get_args();
		return $this;
	}

	public function getQuery()
	{

		// select
		$statement =  'SELECT ' . implode(', ', $this->select) . ' FROM ' . $this->from;

		// inner join
		if(!empty($this->innerJoin)) {
			foreach ($this->innerJoin as $innerJoin) {
				$statement .= ' INNER JOIN ' . $innerJoin;
			}
		}

		// left join
		if(!empty($this->leftJoin)) {
			foreach ($this->leftJoin as $leftJoin) {
				$statement .= ' LEFT JOIN ' . $leftJoin;
			}
		}

		// insert
		if(!empty($this->insert)) {
			$statement = 'INSERT INTO ' . $this->insert;
		}

		// values
		if(!empty($this->columns)) {
			$statement .= ' (' . implode(', ', $this->columns) . ')' . ' VALUES (' . ':' . implode(', :', $this->columns) . ')';
		}

		// where
		if(!empty($this->where)) {
			$statement .= ' WHERE ' . implode(' AND ', $this->where);
		}

		// groupBy
		if(!empty($this->groupBy)) {
			$statement .= ' GROUP BY ' . implode(', ', $this->groupBy);
		}

		// orderBy
		if(!empty($this->orderBy)) {
			$statement .= ' ORDER BY ' . implode(', ', $this->orderBy);
		}

		return $statement;

	}

}