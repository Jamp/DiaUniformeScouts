<?php

class photos extends ActiveRecord {
    protected $logger = True;

    public function InsertPhoto($name, $album_id)
    {
        $this->name = $name;
        $this->album_id = $album_id;
        $this->status = 1;
        return $this->create();
    }

    public function ListPhoto($page=1, $year)
    {
        $conditions = "photos.status = 1 AND year = $year";
        $columns = "columns: name, photos.created_at";
        $join = "join: INNER JOIN album ON album_id = album.id";
        $order = "order: photos.created_at DESC";
        return $this->paginate($conditions, $columns, $join, $order, "page: $page", "per_page: ".PER_PAGE);
    }
}

?>