<?php

namespace Core\Library;

class Validator
{
    /**
     * Validação pura: retorna array de erros ou null se tudo válido.
     * Não toca na sessão — pode ser usada por qualquer camada (web, API, CLI).
     *
     * @param array $data  Dados a validar
     * @param array $rules Regras no formato ['campo' => ['label' => '...', 'rules' => '...']]
     * @return array<string, string>|null
     */
    public static function check(array $data, array $rules): ?array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $label = $rule['label'] ?? $field;
            $itens = explode('|', $rule['rules'] ?? '');
            $value = $data[$field] ?? null;

            // Campo ausente ou vazio
            if ($value === null || $value === '') {
                if (in_array('required', $itens, true)) {
                    $errors[$field] = "O campo \"$label\" é obrigatório.";
                }
                continue; // Sem valor — não aplica demais regras
            }

            foreach ($itens as $item) {
                [$ruleName, $ruleParam] = array_pad(explode(':', $item, 2), 2, null);

                switch ($ruleName) {
                    case 'required':
                        if ((string)$value === '') {
                            $errors[$field] = "O campo \"$label\" deve ser preenchido.";
                        }
                        break;

                    case 'email':
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field] = "O campo \"$label\" não é um e-mail válido.";
                        }
                        break;

                    case 'float':
                        if (!filter_var(str_replace(',', '.', (string)$value), FILTER_VALIDATE_FLOAT)) {
                            $errors[$field] = "O campo \"$label\" deve conter um número decimal.";
                        }
                        break;

                    case 'int':
                        if (!filter_var($value, FILTER_VALIDATE_INT)) {
                            $errors[$field] = "O campo \"$label\" deve conter um número inteiro.";
                        }
                        break;

                    case 'min':
                        if (strlen(strip_tags((string)$value)) < (int)$ruleParam) {
                            $errors[$field] = "O campo \"$label\" deve ter no mínimo {$ruleParam} caracteres.";
                        }
                        break;

                    case 'max':
                        if (strlen(strip_tags((string)$value)) > (int)$ruleParam) {
                            $errors[$field] = "O campo \"$label\" deve ter no máximo {$ruleParam} caracteres.";
                        }
                        break;
                }

                // Para na primeira falha do campo para não acumular mensagens
                if (isset($errors[$field])) {
                    break;
                }
            }
        }

        return empty($errors) ? null : $errors;
    }

    /**
     * make
     *
     * @return bool true se houver erros, false se válido
     */
    public static function make(array $data, array $rules): bool
    {
        $errors = self::check($data, $rules);

        if ($errors) {
            // Converte aspas duplas em torno do label para <b> nas mensagens HTML
            $htmlErrors = array_map(
                fn($msg) => preg_replace('/"([^"]+)"/', '<b>$1</b>', $msg),
                $errors
            );

            Session::set('formErrors', $htmlErrors);
            Session::set('formInputs', $data);
            return true;
        }

        Session::destroy('formErrors');
        Session::destroy('formInputs');
        return false;
    }
}
