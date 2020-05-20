<?php

class Domino
{
    private $head;
    private $tail;
    private $inDeck;
    private $weight;

    public function __construct($id, $head, $tail)
    {
        $this->setHeadValue($head);
        $this->setTailValue($tail);
        $this->setInDeck(true);
        $this->setWeight();
    }

    public function getHead()
    {
        return $this->head;
    }

    private function setHeadValue($value)
    {
        $this->head = $value;
    }

    public function getTail()
    {
        return $this->tail;
    }

    private function setTailValue($value)
    {
        $this->tail = $value;
    }

    public function setInDeck($status)
    {
        $this->inDeck = $status;
    }

    public function getInDeck()
    {
        return $this->inDeck;
    }

    private function setWeight()
    {
        $this->weight = $this->getHead() + $this->getTail();
    }

    public function getWeight()
    {
        return $this->weight;
    }

}

?>