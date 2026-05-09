<?php

namespace Core\Library;

use Core\Library\Session;

class Files
{
    private string $pathFile;
    private array $allowedTypes;
    private int $maxSize;

    public function __construct(
        string $uploadPath = '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR,
        array $allowedTypes = FILE_ALLOWEDTYPES,
        int $maxSizeMB = FILE_MAXSIZE
    ) {
        $this->pathFile = $uploadPath;
        $this->allowedTypes = $allowedTypes;
        $this->maxSize = $maxSizeMB * 1024 * 1024;
    }

    /**
     * @return array Nomes dos arquivos enviados (vazio se todos falharam); false em erro fatal de diretório.
     */
    public function upload(array $arquivos, string $pasta, string $nomeArquivoAntigo = ''): array|false
    {
        $diretorioUpload = $this->pathFile . $pasta . DIRECTORY_SEPARATOR;
        $arquivosUploadSucesso = [];
        $erros = [];

        if (!is_dir($diretorioUpload) && !mkdir($diretorioUpload, 0777, true)) {
            Session::set('msgError', "Falha ao criar o diretório de upload: {$diretorioUpload}");
            return false;
        }

        if (!is_writable($diretorioUpload)) {
            Session::set('msgError', "O diretório de upload não tem permissões de escrita: {$diretorioUpload}");
            return false;
        }

        foreach ($arquivos as $arquivo) {
            if ($arquivo['error'] !== UPLOAD_ERR_OK) {
                $erros[] = "Erro no upload do arquivo: {$arquivo['name']}";
                continue;
            }

            if (!empty($this->allowedTypes)) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeReal = $finfo->file($arquivo['tmp_name']);
                if (!in_array($mimeReal, $this->allowedTypes)) {
                    $erros[] = "Tipo de arquivo inválido: {$arquivo['name']}";
                    continue;
                }
            }

            if ($arquivo['size'] > $this->maxSize) {
                $erros[] = "Tamanho do arquivo excedido: {$arquivo['name']}";
                continue;
            }

            $extensao  = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
            $nomeBase  = preg_replace('/[^a-zA-Z0-9_-]/', '_', pathinfo($arquivo['name'], PATHINFO_FILENAME));
            $nomeArquivo   = bin2hex(random_bytes(8)) . '_' . $nomeBase . '.' . $extensao;
            $caminhoCompleto = $diretorioUpload . $nomeArquivo;

            if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
                $erros[] = "Falha ao mover o arquivo: {$arquivo['name']}";
                continue;
            }

            // Arquivo antigo só é removido após o novo upload ser confirmado
            if (!empty($nomeArquivoAntigo)) {
                $caminhoArquivoAntigo = $diretorioUpload . $nomeArquivoAntigo;
                if (file_exists($caminhoArquivoAntigo)) {
                    unlink($caminhoArquivoAntigo);
                }
            }

            $arquivosUploadSucesso[] = $nomeArquivo;
        }

        if (!empty($erros)) {
            Session::set('msgError', implode('<br>', $erros));
        }

        return $arquivosUploadSucesso;
    }

    public function delete(string $nomeArquivo, string $pasta): bool
    {
        $caminhoCompleto = $this->pathFile . $pasta . DIRECTORY_SEPARATOR . $nomeArquivo;
        return file_exists($caminhoCompleto) && unlink($caminhoCompleto);
    }
}
