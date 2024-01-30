<?php declare(strict_types=1);

namespace DalPraS\OAuth2\Client\ResultSet;

use ArrayObject;
use Traversable;

class PageResultSet implements ResultSetInterface
{
    /**
     * Data stored in the page.
     */
    private ArrayObject $data;

    /**
     * Page information
     */
    private ArrayObject $page;

    public function __construct(?array $response, string $type)
    {
        $response = is_array($response) ? $response : [];
        $this->data = new ArrayObject($response['_embedded'][$type] ?? []);
        $this->page = new ArrayObject($response['page'] ?? []);
    }

    /**
     * {@inheritDoc}
     * @see \JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        return $this->data->getArrayCopy();
    }
    
    public function getData(): ArrayObject
    {
        return $this->data;
    }

    public function getPage(): ArrayObject
    {
        return $this->page;
    }

    /**
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count(): int
    {
        return $this->data->count();
    }

    /**
     * {@inheritDoc}
     * @see \ArrayAccess::offsetExists()
     */
    public function offsetExists($offset): bool
    {
        return $this->data->offsetExists($offset);
    }

    /**
     * {@inheritDoc}
     * @see \ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->data->offsetGet($offset);
    }

    /**
     * {@inheritDoc}
     * @see \ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value): void
    {
        $this->data->offsetSet($offset, $value);
    }

    /**
     * {@inheritDoc}
     * @see \ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset): void
    {
        $this->data->offsetUnset($offset);
    }

    /**
     * {@inheritDoc}
     * @see \Serializable::serialize()
     */
    public function serialize(): string
    {
        return (new ArrayObject([
            'data' => $this->data->getArrayCopy(),
            'page' => $this->page->getArrayCopy()
        ]))->serialize();
    }

    /**
     * {@inheritDoc}
     * @see \Serializable::unserialize()
     */
    public function unserialize(string $serialized)
    {
        $response = new ArrayObject();
        $response->unserialize($serialized);
        $this->data = new ArrayObject($response['data']);
        $this->page = new ArrayObject($response['page']);
    }

    /**
     * {@inheritDoc}
     * @see \IteratorAggregate::getIterator()
     */
    public function getIterator(): Traversable
    {
        return $this->data->getIterator();
    }
}
