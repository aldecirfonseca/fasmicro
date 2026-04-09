<?php

namespace App\Model;

use Core\Library\ModelMain;

class CategoriaModel extends ModelMain
{
    protected $table = "categoria";

    public $listaStatus = [
        1 => "Ativo",
        2 => "Inativo"
    ];

    public function lista()
    {
        // $rs = $this->db->dbSelect("SELECT * FROM {$this->table} WHERE statusRegistro = 1");
        // return $this->db->dbBuscaArrayAll($rs);

        return $this->db
            ->orderBy("descricao")
            ->findAll();
    }

    /**
     * Undocumented function
     *
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        if ($id == 0) {
            return [];
        } else {
            return $this->db->where("id", $id)->first();
        }
    }

    /**
     * Undocumented function
     *
     * @param array $dados
     * @return bool
     */
    public function insert($dados)
    {
        unset($dados['id']);

        if ($this->db->insert($dados) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Undocumented function
     *
     * @param array $dados
     * @return bool
     */
    public function update($dados)
    {
        if ($this->db
            ->where($this->primaryKey, $dados[$this->primaryKey])
            ->update($dados) > 0
        ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Undocumented function
     *
     * @param array $dados
     * @return bool
     */
    public function delete($dados) 
    {
        if ($this->db
            ->where($this->primaryKey, $dados[$this->primaryKey])
            ->delete() > 0
        ) {
            return true;
        } else {
            return false;
        }
    }
}