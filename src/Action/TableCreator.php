<?php # -*- coding: utf-8 -*-

namespace WpDbTools\Action;

use WpDbTools\Type\TableSchema;

/**
 * Interface TableCreator
 *
 * Describes an action to create a MySQL table along a TableSchema
 *
 * @package Action
 */
interface TableCreator {

	/**
	 * @param TableSchema $schema
	 *
	 * @return bool
	 */
	public function create_table( TableSchema $schema );
}