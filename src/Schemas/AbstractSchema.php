<?php

namespace MichaelDrennen\SchwabAPI\Schemas;

abstract class AbstractSchema implements \JsonSerializable {

    /**
     * Create an instance from an associative array of data.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray( array $data ): static {
        $instance = new static();
        foreach ( $data as $key => $value ) {
            $setter = 'set' . ucfirst( $key );
            if ( method_exists( $instance, $setter ) ) {
                $instance->$setter( $value );
            } elseif ( property_exists( $instance, $key ) ) {
                $instance->$key = $value;
            }
        }
        return $instance;
    }

    /**
     * Create a collection/list of instances from an array of items.
     *
     * @param array $items
     * @return static[]
     */
    public static function fromCollection( array $items ): array {
        return array_map( fn( $item ) => is_array( $item ) ? static::fromArray( $item ) : $item, $items );
    }

    /**
     * Convert the schema object to an associative array.
     *
     * @return array
     */
    public function toArray(): array {
        $result = [];
        $reflection = new \ReflectionClass( $this );
        foreach ( $reflection->getProperties() as $property ) {
            $property->setAccessible( true );
            if ( $property->isInitialized( $this ) ) {
                $value = $property->getValue( $this );
                if ( $value instanceof AbstractSchema ) {
                    $result[ $property->getName() ] = $value->toArray();
                } elseif ( is_array( $value ) ) {
                    $result[ $property->getName() ] = array_map(
                        fn( $item ) => $item instanceof AbstractSchema ? $item->toArray() : $item,
                        $value
                    );
                } else {
                    $result[ $property->getName() ] = $value;
                }
            }
        }
        return $result;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): mixed {
        return $this->toArray();
    }
}
