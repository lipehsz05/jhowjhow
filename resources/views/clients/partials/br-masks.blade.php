{{-- Máscaras BR + ViaCEP: expõe window.BrClienteMasks --}}
<script>
(function () {
    'use strict';

    function onlyDigits(s) {
        return String(s || '').replace(/\D/g, '');
    }

    function formatTelefoneBR(d) {
        d = onlyDigits(d);
        if (d.length > 11 && d.startsWith('55')) d = d.slice(2);
        if (d.length > 11) d = d.slice(0, 11);
        if (!d.length) return '';
        if (d.length <= 2) return '(' + d;
        var ddd = d.slice(0, 2);
        var rest = d.slice(2);
        if (rest.length <= 4) return '(' + ddd + ') ' + rest;
        if (rest.length === 8) return '(' + ddd + ') ' + rest.slice(0, 4) + '-' + rest.slice(4, 8);
        return '(' + ddd + ') ' + rest.slice(0, 5) + '-' + rest.slice(5, 9);
    }

    function formatCpf(d) {
        d = onlyDigits(d).slice(0, 11);
        if (d.length <= 3) return d;
        if (d.length <= 6) return d.slice(0, 3) + '.' + d.slice(3);
        if (d.length <= 9) return d.slice(0, 3) + '.' + d.slice(3, 6) + '.' + d.slice(6);
        return d.slice(0, 3) + '.' + d.slice(3, 6) + '.' + d.slice(6, 9) + '-' + d.slice(9, 11);
    }

    function formatCnpj(d) {
        d = onlyDigits(d).slice(0, 14);
        if (d.length <= 2) return d;
        if (d.length <= 5) return d.slice(0, 2) + '.' + d.slice(2);
        if (d.length <= 8) return d.slice(0, 2) + '.' + d.slice(2, 5) + '.' + d.slice(5);
        if (d.length <= 12) return d.slice(0, 2) + '.' + d.slice(2, 5) + '.' + d.slice(5, 8) + '/' + d.slice(8);
        return d.slice(0, 2) + '.' + d.slice(2, 5) + '.' + d.slice(5, 8) + '/' + d.slice(8, 12) + '-' + d.slice(12, 14);
    }

    function formatCpfCnpj(d) {
        var n = onlyDigits(d);
        if (n.length <= 11) return formatCpf(n);
        return formatCnpj(n);
    }

    function formatCep(d) {
        d = onlyDigits(d).slice(0, 8);
        if (d.length <= 5) return d;
        return d.slice(0, 5) + '-' + d.slice(5, 8);
    }

    function bindInput(el, formatter) {
        if (!el) return;
        el.addEventListener('input', function () {
            el.value = formatter(el.value);
        });
    }

    var cepTimer = null;

    function bindViaCep(cepEl, enderecoEl, cidadeEl, estadoEl) {
        if (!cepEl) return;
        function run() {
            var d = onlyDigits(cepEl.value);
            if (d.length !== 8) return;
            fetch('https://viacep.com.br/ws/' + d + '/json/')
                .then(function (r) { return r.json(); })
                .then(function (j) {
                    if (!j || j.erro) return;
                    if (enderecoEl) {
                        var parts = [];
                        if (j.logradouro) parts.push(j.logradouro);
                        if (j.complemento) parts.push(j.complemento);
                        if (j.bairro) parts.push(j.bairro);
                        if (parts.length) enderecoEl.value = parts.join(' — ');
                    }
                    if (cidadeEl && j.localidade) cidadeEl.value = j.localidade;
                    if (estadoEl && j.uf) estadoEl.value = String(j.uf).slice(0, 2).toUpperCase();
                })
                .catch(function () {});
        }
        cepEl.addEventListener('input', function () {
            clearTimeout(cepTimer);
            cepTimer = setTimeout(run, 400);
        });
        cepEl.addEventListener('blur', function () {
            clearTimeout(cepTimer);
            run();
        });
    }

    window.BrClienteMasks = {
        formatTelefoneBR: formatTelefoneBR,
        formatCpfCnpj: formatCpfCnpj,
        formatCep: formatCep,
        bindClienteForm: function (root) {
            root = root || document;
            bindInput(root.querySelector('#telefone'), formatTelefoneBR);
            bindInput(root.querySelector('#cpf_cnpj'), formatCpfCnpj);
            bindInput(root.querySelector('#cep'), formatCep);
            bindViaCep(
                root.querySelector('#cep'),
                root.querySelector('#endereco'),
                root.querySelector('#cidade'),
                root.querySelector('#estado')
            );
            var cb = root.querySelector('#sem_endereco');
            var addrFields = ['cep', 'endereco', 'cidade', 'estado'].map(function (id) {
                return root.querySelector('#' + id);
            });
            function applySemEndereco() {
                var off = cb && cb.checked;
                addrFields.forEach(function (el) {
                    if (!el) return;
                    el.disabled = !!off;
                    if (off) {
                        el.value = '';
                    }
                });
            }
            if (cb) {
                cb.addEventListener('change', applySemEndereco);
                applySemEndereco();
            }
        },
        bindTelefone: function (el) {
            if (!el) return;
            bindInput(el, formatTelefoneBR);
        }
    };
})();
</script>
