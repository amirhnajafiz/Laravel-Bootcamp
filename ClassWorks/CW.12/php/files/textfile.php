<?php

require_once "executeable.php";

class TextFile extends Executeable
{
    public function __debugInfo()
    {
        return ["Executeable_TextFile:" => $this->getName(),];
    }
}

?>