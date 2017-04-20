<?php # -*- coding: utf-8 -*-

namespace WpDbTools\Type;

use WpDbTools\Exception\Type\InvalidTableSchema;

/**
 * Class GenericTableSchema
 *
 * @package WpDbTools\Type
 */
final class GenericTableSchema implements TableSchema {

	const TABLE_NAME_INDEX = 'name';
	const TABLE_SCHEMA_INDEX = 'schema';
	const TABLE_PRIMARY_KEY_INDEX = 'primary_key';
	const TABLE_INDICES_INDEX = 'indices';

	/**
	 * @var array
	 */
	private $schema;

	/**
	 * @var array
	 */
	private $primary_key = [];

	/**
	 * @var array
	 */
	private $indices = [];

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param array $definition
	 *
	 * @throws InvalidTableSchema
	 */
	public function __construct( array $definition ) {

		$this
			->validate_name( $definition )
			->validate_schema( $definition )
			->validate_primary_key( $definition )
			->validate_indices( $definition );
	}

	/**
	 * @return string
	 */
	public function name() {

		return $this->name;
	}

	public function __toString() {

		return $this->name;
	}

	/**
	 * Returns a list of column_name => column description
	 *
	 * @return string[]
	 */
	public function schema() {

		return $this->schema;
	}

	/**
	 * Name of the primary key columns
	 *
	 * @return string[]
	 */
	public function primary_key() {

		return $this->primary_key;
	}

	/**
	 * Returns a list of index_name => index_description
	 *
	 * @return string[]
	 */
	public function indices() {

		return $this->indices;
	}

	private function validate_name( array $definition ) {

		if ( ! array_key_exists( self::TABLE_NAME_INDEX, $definition ) ) {
			throw new InvalidTableSchema( "Missing parameter: name" );
		}
		if ( ! is_string( $definition[ self::TABLE_NAME_INDEX ] ) ) {
			throw new InvalidTableSchema( "Parameter 'name' must be of type string" );
		}
		$this->name = $definition[ self::TABLE_NAME_INDEX ];

		return $this;
	}

	private function validate_schema( array $definition ) {

		if ( ! array_key_exists( self::TABLE_SCHEMA_INDEX, $definition ) ) {
			throw new InvalidTableSchema( "Missing parameter: schema" );
		}
		if ( ! is_array( $definition[ self::TABLE_SCHEMA_INDEX ] ) ) {
			throw new InvalidTableSchema( "Parameter 'schema' must be of type array" );
		}

		array_walk( $definition[ self::TABLE_SCHEMA_INDEX ], function( $definition, $name ) {

			if ( ! is_string( $name ) ) {
				throw new InvalidTableSchema( "Column name must be a string" );
			}
			if ( ! is_string( $definition ) ) {
				throw new InvalidTableSchema( "Column definition must be a string" );
			}
		} );

		$this->schema = $definition[ self::TABLE_SCHEMA_INDEX ];

		return $this;
	}

	private function validate_primary_key( array $definition ) {

		if ( ! array_key_exists( self::TABLE_PRIMARY_KEY_INDEX, $definition ) ) {
			return $this;
		}

		if ( ! is_array( $definition[ self::TABLE_PRIMARY_KEY_INDEX ] ) ) {
			throw new InvalidTableSchema( "Primary key definition must be of type array" );
		}

		array_walk( $definition[ self::TABLE_PRIMARY_KEY_INDEX ], function( $column ) {
			if ( ! is_string( $column ) ) {
				throw new InvalidTableSchema( "Primary key column must be of type string" );
			}
			if ( ! array_key_exists( $column, $this->schema ) ) {
				throw new InvalidTableSchema( "Primary key column does not exist in table schema" );
			}
		} );

		$this->primary_key = $definition[ self::TABLE_PRIMARY_KEY_INDEX ];

		return $this;
	}

	private function validate_indices( array $definition ) {

		if ( ! array_key_exists( self::TABLE_INDICES_INDEX, $definition ) ) {
			return $this;
		}

		if ( ! is_array( $definition[ self::TABLE_INDICES_INDEX ] ) ) {
			throw new InvalidTableSchema( "Indices definition must be of type array" );
		}

		array_walk( $definition[ self::TABLE_INDICES_INDEX ], function( $definition, $name ) {
			if ( ! is_string( $name ) ) {
				throw new InvalidTableSchema( "Index name must be a string" );
			}
			if ( ! is_string( $definition ) ) {
				throw new InvalidTableSchema( "Index definition must be a string" );
			}
		} );

		$this->indices = $definition[ self::TABLE_INDICES_INDEX ];

		return $this;
	}

}