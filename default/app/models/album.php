<?php

/**
*
*/
class album extends ActiveRecord
{

    public function Listar()
    {
        return $this->find('status = 1', 'columns: id, year');
    }
}

?>