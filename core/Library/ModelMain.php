<?php

namespace Core\Library;

/**
 * ModelMain — Classe base para todos os Models da aplicação.
 *
 * Encapsula as operações CRUD padrão (lista / getById / insert / insertGetId / update / delete)
 * usando a classe Database como query builder. As subclasses devem definir:
 *   - $table        : nome da tabela
 *   - $primaryKey   : coluna PK (padrão "id")
 *   - $validationRules : regras passadas ao Validator::make() antes de inserir/atualizar
 */
class ModelMain
{
    public $db;
    public $validationRules = [];
    public $primaryKey = "id";
    public $titulo = '';
    public $listaStatus = [];
    
    protected $table;

    public function __construct()
    {
        $this->db = new Database(
            $_ENV['DB_CONNECTION'],
            $_ENV['DB_HOST'],
            $_ENV['DB_PORT'],
            $_ENV['DB_DATABASE'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD']
        );

        // Setando a tabela do model
        $this->db->table($this->table);
    }

    /**
     * lista - Retorna todos os registros da tabela ordenados pelo campo informado.
     *
     * @param string $orderBy Campo de ordenação (padrão: "descricao")
     * @return array
     */
    public function lista($orderBy = "descricao")
    {
        return $this->db
            ->orderBy($orderBy)
            ->findAll();
    }

    /**
     * getById - Busca um registro pelo seu ID.
     * Retorna array vazio se $id for 0.
     *
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        if ($id == 0) {
            return [];
        } else {
            return $this->db->where($this->primaryKey, $id)->first();
        }
    }

    /**
     * insert - Valida os dados e insere um novo registro na tabela.
     * Retorna false se a validação falhar.
     *
     * @param array $dados
     * @return bool
     */
    public function insert($dados)
    {
        if (Validator::make($dados, $this->validationRules)) {
            return false;
        } else {
            unset($dados[$this->primaryKey]);        // excluir a key id do array

            if ($this->db->insert($dados) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Valida, insere e retorna o ID do novo registro.
     * Retorna 0 em caso de falha de validação ou erro de banco.
     */
    public function insertGetId(array $dados): int
    {
        if (Validator::make($dados, $this->validationRules)) {
            return 0;
        }

        unset($dados[$this->primaryKey]);
        return (int) $this->db->insert($dados);
    }

    /**
     * update - Valida os dados e atualiza o registro identificado pela chave primária.
     * Retorna false se a validação falhar.
     *
     * @param array $dados Deve conter a chave primária ($this->primaryKey)
     * @return bool
     */
    public function update($dados)
    {
        if (Validator::make($dados, $this->validationRules)) {
            return false;
        } else {
            if ($this->db
                ->where($this->primaryKey, $dados[$this->primaryKey])
                ->update($dados) >= 0
            ) {
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * delete - Remove o registro identificado pela chave primária presente em $dados.
     *
     * @param array $dados Deve conter a chave primária ($this->primaryKey)
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