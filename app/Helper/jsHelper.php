<?php

if (!function_exists('jsQuillEditor')) {
    function jsQuillEditor(string $editorId, string $fieldId, $initValue = null): string
    {
        static $cssIncluded = false;
        static $scriptIncluded = false;

        $html = '';

        if (!$cssIncluded) {
            $html .= '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.snow.css">' . "\n";
            $cssIncluded = true;
        }

        if (!$scriptIncluded) {
            $html .= '<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>' . "\n";
            $scriptIncluded = true;
        }

        $json = json_encode($initValue ?? '');
        $var  = '_quill_' . preg_replace('/\W/', '_', $fieldId);

        $html .= <<<JS
<script>
    document.addEventListener('DOMContentLoaded', function () {
        window._fasm = window._fasm || { quill: {}, cleave: {} };
        const {$var} = new Quill('#{$editorId}', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ script: 'super' }, { script: 'sub' }],
                    [{ color: [] }, { background: [] }],
                    [{ font: [] }],
                    [{ size: ['small', false, 'large', 'huge'] }],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ align: [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });
        const _init_{$var} = {$json};
        if (_init_{$var}) {
            {$var}.root.innerHTML = _init_{$var};
        }
        window._fasm.quill['{$fieldId}'] = {$var};
    });
</script>

JS;

        return $html;
    }
}

if (!function_exists('jsCleaveNumeral')) {
    /**
     * Máscara de entrada numérica com separador decimal vírgula.
     *
     * @param string $displayId  ID do input visível ao usuário
     * @param string $fieldId    ID do input hidden que recebe o valor para o POST
     * @param int    $decimals   Número de casas decimais (padrão: 2)
     * @param string $prefix     Prefixo opcional, ex: 'R$ '
     */
    function jsCleaveNumeral(string $displayId, string $fieldId, int $decimals = 2, string $prefix = ''): string
    {
        static $scriptIncluded = false;

        $html = '';

        if (!$scriptIncluded) {
            $html .= '<script src="https://cdn.jsdelivr.net/npm/cleave.js@1.6.0/dist/cleave.min.js"></script>' . "\n";
            $scriptIncluded = true;
        }

        $options = [
            'numeral'             => true,
            'numeralDecimalMark'  => ',',
            'delimiter'           => '.',
            'numeralDecimalScale' => $decimals,
            'numeralPositiveOnly' => true,
        ];

        if ($prefix !== '') {
            $options['prefix']            = $prefix;
            $options['noImmediatePrefix'] = true;
        }

        $jsonOptions = json_encode($options);
        $var         = '_cleave_' . preg_replace('/\W/', '_', $fieldId);

        $html .= <<<JS
<script>
    window._fasm = window._fasm || { quill: {}, cleave: {} };
    const {$var} = new Cleave('#{$displayId}', {$jsonOptions});
    const _initVal_{$var} = document.getElementById('{$fieldId}').value;
    if (_initVal_{$var} !== '') {
        {$var}.setRawValue(_initVal_{$var}.replace('.', ','));
    }
    window._fasm.cleave['{$fieldId}'] = {$var};
</script>

JS;

        return $html;
    }
}

if (!function_exists('jsFormHandler')) {
    /**
     * Handler genérico de submit: sincroniza os valores de editores Quill
     * e máscaras Cleave registrados em window._fasm para seus campos hidden.
     * Deve ser chamado ao final do formulário.
     */
    function jsFormHandler(): string
    {
        return <<<'JS'
<script>
    document.querySelector('form').addEventListener('submit', function (e) {
        window._fasm = window._fasm || { quill: {}, cleave: {} };

        for (const [fieldId, editor] of Object.entries(window._fasm.quill)) {
            const html = editor.root.innerHTML;
            if (html === '<p><br></p>' || html.trim() === '') {
                e.preventDefault();
                editor.root.focus();
                return;
            }
            document.getElementById(fieldId).value = html;
        }

        for (const [fieldId, cleave] of Object.entries(window._fasm.cleave)) {
            document.getElementById(fieldId).value = cleave.getRawValue().replace(',', '.');
        }
    });
</script>
JS;
    }
}
