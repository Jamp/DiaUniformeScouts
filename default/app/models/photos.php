<?php

class photos extends ActiveRecord {
    protected $logger = True;

    public function InsertPhoto($name, $album_id)
    {
        $this->name = $name;
        $this->album_id = $album_id;
        $this->status = 0;
        return $this->create();
    }

    public function ListPhoto($page=1, $year)
    {
        /* $conditions = "photos.status = 1 AND year = $year";
        $columns = "columns: name, photos.created_at";
        $join = "join: INNER JOIN album ON album_id = album.id";
        $order = "order: photos.created_at DESC"; */

        $sql = "SELECT name, DATE_FORMAT(photos.created_at, '%d/%m/%Y %H:%i') AS created_at
        FROM photos
        INNER JOIN album ON album_id = album.id
        WHERE photos.status = 1 AND year = $year
        ORDER BY photos.created_at DESC";

        //return $this->paginate($conditions, $columns, $join, $order, "page: $page", "per_page: ".PER_PAGE);
        return $this->paginate_by_sql($sql, "page: $page", "per_page: ".PER_PAGE);
    }

    public function PendientPhoto()
    {
        /* $conditions = "photos.status = 1 AND year = $year";
        $columns = "columns: name, photos.created_at";
        $join = "join: INNER JOIN album ON album_id = album.id";
        $order = "order: photos.created_at DESC"; */

        $sql = "SELECT photos.id, name, year, album_id, DATE_FORMAT(photos.created_at, '%d/%m/%Y %H:%i') AS created_at
        FROM photos
        INNER JOIN album ON album_id = album.id
        WHERE photos.status = 0";

        //return $this->paginate($conditions, $columns, $join, $order, "page: $page", "per_page: ".PER_PAGE);
        return $this->find_all_by_sql($sql);
    }

    public function imageName($id){
        return $this->find_first($id)->name;
    }

    public function Valid($id){
        $rs = $this->find($id);
        $rs->status = 1;
        return $rs->save();
    }

    public function Error($id){
        $rs = $this->find($id);
        $rs->status = 2;
        return $rs->save();
    }
}

?>