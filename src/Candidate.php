<?php
namespace PivotLibre\Tideman;

use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Candidate implements LoggerAwareInterface, \JsonSerializable
{
    use LoggerAwareTrait;

    private $id;
    private $name;

    public function __construct(string $id, string $name = "")
    {
        if (strlen(trim($id)) == 0) {
            throw new InvalidArgumentException("A Candidate must have an id");
        }
        $this->id = $id;
        $this->name = $name;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function __toString() : string
    {
        return $this->name . "(" . $this->id . ")";
    }

    public function jsonSerialize()
    {
        $jsonFriendly = [
            'id' => $this->id,
        ];
        //omit the name if it is empty
        if ("" !== $this->name) {
            $jsonFriendly['name'] = $this->name;
        }
        return $jsonFriendly;
    }
}
