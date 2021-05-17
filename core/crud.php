<?php

class CRUD
{
    var $db = "";

    public function __construct()
    {
        $this->db = new mysqli('localhost', 'root', '', 'buku_tamu');
        if ($this->db->connect_errno) {
            echo "Gagal";
        }
    }

    public function get($table, $where = [])
    {
        $sql = "SELECT * FROM $table";
        $count = count($where);
        if ($count > 0) {
            $sql .= ' WHERE ';
            $i = 1;
            foreach ($where as $key => $value) {
                if ($i == $count) {
                    $sql .= " $key = '$value'";
                } else {
                    $sql .= " $key = '$value' AND ";
                }
                $i++;
            }
        }
        $query = $this->db->query($sql);
        return $query;
    }

    public function count_page($table, $limit = 5)
    {
        $sql = "SELECT COUNT(*) as jml FROM $table";

        $query = $this->db->query($sql);

        $jml = $query->fetch_array()['jml'];
        return ceil($jml / $limit);
    }

    public function page($table, $where = [], $page = 1, $limit = 5)
    {
        $sql = "SELECT * FROM $table";
        $count = count($where);
        if ($count > 0) {
            $sql .= ' WHERE ';
            $i = 1;
            foreach ($where as $key => $value) {
                if ($i == $count) {
                    $sql .= " $key = '$value'";
                } else {
                    $sql .= " $key = '$value' AND ";
                }
                $i++;
            }
        }

        $page   = $page <= 1 ? 0 : (($page - 1) * $limit);
        $sql    .= " LIMIT $page, $limit";

        $query = $this->db->query($sql);
        return $query;
    }

    public function search($table, $where = [])
    {
        $sql = "SELECT * FROM $table";
        $count = count($where);
        if ($count > 0) {
            $sql .= ' WHERE ';
            $i = 1;
            foreach ($where as $key => $value) {
                if ($i == $count) {
                    $sql .= " $key LIKE '%$value%'";
                } else {
                    $sql .= " $key LIKE '%$value%' OR ";
                }
                $i++;
            }
        }
        $query = $this->db->query($sql);
        return $query;
    }

    public function insert($table, $data)
    {
        $values = '';
        $fields  = '';

        $count = count($data);
        $i = 1;
        foreach ($data as $key => $value) {
            if ($i == $count) {
                $fields     .= $key;
                $values     .= "'$value'";
            } else {
                $fields     .= "$key,";
                $values     .= "'$value',";
            }
            $i++;
        }
        $sql = "INSERT INTO $table($fields) VALUES($values)";
        // return $sql;
        $insert = $this->db->query($sql);
        if ($insert) {
            return 'success';
        } else {
            $err = $this->db->error;
            $this->db->close();
            return 'ERROR : ' . $err;
        }
    }

    public function update($table, $data, $where)
    {
        $set    = '';
        $where_sql  = '';

        $count = count($data);
        $count_where = count($where);
        $i = 1;
        $j = 1;
        foreach ($data as $key => $value) {
            if ($i == $count) {
                $set .= "$key = '$value'";
            } else {
                $set .= "$key = '$value',";
            }
            $i++;
        }
        foreach ($where as $key => $value) {
            if ($j == $count_where) {
                $where_sql .= "$key = '$value'";
            } else {
                $where_sql .= "$key = '$value' AND ";
            }
            $j++;
        }
        $sql = "UPDATE $table SET $set WHERE $where_sql";
        // return $sql;
        $insert = $this->db->query($sql);
        if ($insert) {
            return 'success';
        } else {
            $err = $this->db->error;
            $this->db->close();
            return 'ERROR : ' . $err;
        }
    }

    public function delete($table, $where = [])
    {
        $sql = "DELETE FROM $table";
        $count = count($where);
        if ($count > 0) {
            $sql .= ' WHERE ';
            $i = 1;
            foreach ($where as $key => $value) {
                if ($i == $count) {
                    $sql .= " $key = '$value'";
                } else {
                    $sql .= " $key = '$value' AND ";
                }
                $i++;
            }
        }
        $query = $this->db->query($sql);
        return $query;
    }

    public function message($success, $url, $is_save = true)
    {
        if ($success) {
            echo "<script>alert('Data " . ($is_save ? 'tersimpan' : 'terhapus') . "');
				document.location = '$url';
				</script>";
        } else {
            echo "<script>alert('Maaf sedang terjadi error'); 
            document.location = '$url';
            </script>";
        }
    }
}
