function modeManager() {
    var mode = localStorage.mode;

    if (mode == "inventario")
        document.querySelector('#switch').innerHTML = '<a href="javascript:;"><i class="spinny material-icons">sync</i>Modalità inventario</a>';
    else
        document.querySelector('#switch').innerHTML = '<a href="javascript:;"><i class="spinny material-icons">sync</i>Modalità ordini</a>';

    // Visualizza
    // change table header "table_header" to match mode
    if (mode == "inventario")
        document.querySelector('#table_header').innerHTML = '<th>ID</th><th>Nome prodotto</th><th>Descrizione</th><th>Categoria</th><th>Quantità</th><th>Prezzo</th>';
    if (mode == "ordini")
        document.querySelector('#table_header').innerHTML = '<th>ID</th><th>Nome</th><th>Cognome</th><th>Nome prodotto</th><th>Quantità</th><th>Prezzo</th><th>Data</th>';

    // clear table data
    document.querySelector('#table_visualizza').innerHTML = "";

    // Inserisci
    if (mode == "inventario") {
        document.querySelector('#create-inventario').hidden = false;
        document.querySelector('#create-ordini').hidden = true;

        document.querySelector('#update-inventario').hidden = false;
        document.querySelector('#update-ordini').hidden = true;

        document.querySelector('#delete-inventario').hidden = false;
        document.querySelector('#delete-ordini').hidden = true;

        document.querySelector('#export-inventario').hidden = false;
        document.querySelector('#export-ordini').hidden = true;
    }

    if (mode == "ordini") {
        document.querySelector('#create-ordini').hidden = false;
        document.querySelector('#create-inventario').hidden = true;

        document.querySelector('#update-ordini').hidden = false;
        document.querySelector('#update-inventario').hidden = true;

        document.querySelector('#delete-ordini').hidden = false;
        document.querySelector('#delete-inventario').hidden = true;

        document.querySelector('#export-ordini').hidden = false;
        document.querySelector('#export-inventario').hidden = true;
    }

    firstRead();
    fillTable(0, document.querySelector('#table_visualizza'));
}

function firstRead() {
    fetch('../read.php?mode=' + localStorage.mode + '&limit=' + (10) + '&offset=' + (0))
        .then(response => response.json())
        .then(data => {
            createTable(data)
            fillTable(data.data.length);
            create_pagination(Math.ceil(data.length / 10), 1);
        });
}

function read(limit, offset) {
    // fetch data from /read
    fetch('../read.php?mode=' + localStorage.mode + '&limit=' + (limit || 10) + '&offset=' + (offset || 0))
        .then(response => response.json())
        .then(data => {
            createTable(data);
            fillTable(data.data.length);
        });
}

function create_pagination(pagination_length, current_number) {
    if (pagination_length == 0) {
        document.querySelector('#pagination').innerHTML = "";
        return;
    }

    var pagination = document.getElementById("pagination");

    // left arrow
    pagination.innerHTML = `<li class="${current_number > 1 ? 'waves_effect' : 'disabled'}"><a href="javascript:;"><i class="material-icons">chevron_left</i></a></li>\n                                `;

    if (pagination_length <= 9) {
        for (var i = 1; i <= pagination_length; i++) {
            var li = document.createElement("li");
            if (i == current_number)
                li.className = "active";
            else
                li.className = "waves-effect";
            
            li.innerHTML = `<a onclick="paginationClick(this, ${pagination_length})" href="javascript:;">${i}</a>`;
            pagination.appendChild(li);
            pagination.innerHTML += "\n                                ";


        }
    }

    if (pagination_length > 9) {
        // left size | right size
        // left size + 1 | right size
        var conto = pagination_length - current_number > 4 ? 4 : pagination_length - current_number;
        conto += current_number - 5 < 0 ? 5 - current_number : 0;


        // left size | right size
        for (let i = current_number - 4 - (4 - conto); i < current_number; i++) {
            var li = document.createElement("li");
            li.innerHTML = `<a onclick="paginationClick(this, ${pagination_length})" href="javascript:;">${i}</a>`;
            if (i != current_number - 1) li.classList.add('hide-on-small-only');
            pagination.appendChild(li);
            pagination.innerHTML += "\n                                ";
        }

        var li = document.createElement("li");
        li.innerHTML = `<a onclick="paginationClick(this, ${pagination_length})" href="javascript:;">${current_number}</a>`;
        li.className = "active";
        pagination.appendChild(li);
        pagination.innerHTML += "\n                                ";

        for (let i = current_number + 1; i <= current_number + conto; i++) {
            var li = document.createElement("li");
            li.innerHTML = `<a onclick="paginationClick(this, ${pagination_length})" href="javascript:;">${i}</a>`;
            if (i != current_number + 1) li.classList.add('hide-on-small-only');
            pagination.appendChild(li);
            pagination.innerHTML += "\n                                ";
        }

    }

    // right arrow
    pagination.innerHTML += `<li class="${current_number < pagination_length ? "waves_effect" : "disabled"}"><a href="javascript:;"><i class="material-icons">chevron_right</i></a></li>`;

    // add event listener to arrows
    // left
    document.querySelector("#pagination>li:first-child>a").onclick = function (el) {
        var current = document.querySelector("#pagination>li.active");

        if (current.textContent == 1)
            return;

        current.className = "waves-effect";
        document.querySelector(`#pagination>li:nth-child(${Array.from(current.parentNode.parentNode.children).indexOf(current.parentNode) - 1})`).classList.add("active");

        read(10, (current.textContent - 1) * 10 - 10);
        create_pagination(pagination_length, Number(current.textContent - 1));
    }

    // right
    document.querySelector("#pagination>li:last-child>a").onclick = function () {
        var current = document.querySelector("#pagination>li.active");

        if (current.textContent == pagination_length)
            return;

        current.className = "waves-effect";
        document.querySelector(`#pagination>li:nth-child(${Array.from(current.parentNode.parentNode.children).indexOf(current.parentNode) - -1})`).className = "active";

        read(10, (current.textContent - -1) * 10 - 10);
        create_pagination(pagination_length, Number(current.textContent - -1));
    }

}

function paginationClick(el, pagination_length) {
    var active = document.querySelector("#pagination>li.active");
    if (active) active.className = "waves-effect";
    el.parentElement.className = "active";

    create_pagination(pagination_length, Number(el.textContent));
    read(10, el.textContent * 10 - 10);
}

function create() {
    // check if mode is "inventario" or "ordini"
    var mode = localStorage.mode;
    if (mode == "inventario") {
        // get data from form
        var nome = document.querySelector('#inv_nome').value;
        var descrizione = document.querySelector('#inv_descrizione').value;
        var categoria = document.querySelector('#inv_categoria').value;
        var quantita = document.querySelector('#inv_quantita').value;
        var prezzo = document.querySelector('#inv_prezzo').value;

        // send data to /create
        fetch('../create.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                mode: mode,
                nome_prodotto: nome,
                descrizione: descrizione,
                categoria: categoria,
                quantita: Number(quantita),
                prezzo: Number(prezzo)
            })
        })
            .then(response => response.json())
            .then(data => {
                // clear form
                document.querySelector('#inv_nome').value = "";
                document.querySelector('#inv_descrizione').value = "";
                document.querySelector('#inv_categoria').value = "";
                document.querySelector('#inv_quantita').value = "";
                document.querySelector('#inv_prezzo').value = "";

                M.toast({ html: data })
            });
    }

    if (mode == "ordini") {
        // get data from form
        var nome = document.querySelector('#ord_nome').value;
        var cognome = document.querySelector('#ord_cognome').value;
        var nome_prodotto = document.querySelector('#ord_prodotto').value;
        var quantita = document.querySelector('#ord_quantita').value;
        var prezzo = document.querySelector('#ord_prezzo').value;
        var data_ordine = document.querySelector('#ord_data').value;
        var id_prodotto = document.querySelector('#ord_prodotto').value;


        // send data to /create
        fetch('../create.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                mode: mode,
                nome: nome,
                cognome: cognome,
                nome_prodotto: nome_prodotto,
                quantita: Number(quantita),
                prezzo: Number(prezzo),
                data_ordine: data_ordine,
                id_prodotto: id_prodotto
            })
        })
            .then(response => response.json())
            .then(data => {
                // clear form
                document.querySelector('#ord_nome').value = "";
                document.querySelector('#ord_cognome').value = "";
                document.querySelector('#ord_prodotto').value = "";
                document.querySelector('#ord_quantita').value = "";
                document.querySelector('#ord_prezzo').value = "";
                document.querySelector('#ord_data').value = "";

                M.toast({ html: data })
            });
    }
}

function update() {
    // check if mode is "inventario" or "ordini"
    var mode = localStorage.mode;
    if (mode == "inventario") {
        // get data from form
        var id = document.querySelector('#mod_inv_id').value;
        var nome = document.querySelector('#mod_inv_nome').value;
        var descrizione = document.querySelector('#mod_inv_descrizione').value;
        var categoria = document.querySelector('#mod_inv_categoria').value;
        var quantita = document.querySelector('#mod_inv_quantita').value;
        var prezzo = document.querySelector('#mod_inv_prezzo').value;

        // send data to /update
        fetch('../update.php', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                mode: mode,
                id: id,
                nome_prodotto: nome,
                descrizione: descrizione,
                categoria: categoria,
                quantita: Number(quantita),
                prezzo: Number(prezzo)
            })
        })
            .then(response => response.json())
            .then(data => {
                // clear form
                document.querySelector('#mod_inv_id').value = "";
                document.querySelector('#mod_inv_nome').value = "";
                document.querySelector('#mod_inv_descrizione').value = "";
                document.querySelector('#mod_inv_categoria').value = "";
                document.querySelector('#mod_inv_quantita').value = "";
                document.querySelector('#mod_inv_prezzo').value = "";

                // remove from all labels active class
                document.querySelector('label[for][for="mod_inv_id"]').classList.remove("active");
                document.querySelector('label[for][for="mod_inv_nome"]').classList.remove("active");
                document.querySelector('label[for][for="mod_inv_descrizione"]').classList.remove("active");
                document.querySelector('label[for][for="mod_inv_categoria"]').classList.remove("active");
                document.querySelector('label[for][for="mod_inv_quantita"]').classList.remove("active");
                document.querySelector('label[for][for="mod_inv_prezzo"]').classList.remove("active");

                M.toast({ html: data })
            });
    }

    if (mode == "ordini") {
        // get data from form
        var id = document.querySelector('#mod_ord_id').value;
        var nome = document.querySelector('#mod_ord_nome').value;
        var cognome = document.querySelector('#mod_ord_cognome').value;
        var id_prodotto = document.querySelector('#mod_ord_prodotto').value;
        var quantita = document.querySelector('#mod_ord_quantita').value;
        var prezzo = document.querySelector('#mod_ord_prezzo').value;
        var data_ordine = document.querySelector('#mod_ord_data').value;

        // send data to /update
        fetch('../update.php', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                mode: mode,
                id: id,
                nome: nome,
                cognome: cognome,
                id_prodotto: id_prodotto,
                quantita: Number(quantita),
                prezzo: Number(prezzo),
                data_ordine: data_ordine
            })
        })
            .then(response => response.json())
            .then(data => {
                // clear form
                document.querySelector('#mod_ord_id').value = "";
                document.querySelector('#mod_ord_nome').value = "";
                document.querySelector('#mod_ord_cognome').value = "";
                document.querySelector('#mod_ord_prodotto').value = "";
                document.querySelector('#mod_ord_quantita').value = "";
                document.querySelector('#mod_ord_prezzo').value = "";
                document.querySelector('#mod_ord_data').value = "";

                // remove from all labels active class
                document.querySelector('label[for][for="mod_ord_id"]').classList.remove("active");
                document.querySelector('label[for][for="mod_ord_nome"]').classList.remove("active");
                document.querySelector('label[for][for="mod_ord_cognome"]').classList.remove("active");
                document.querySelector('label[for][for="mod_ord_prodotto"]').classList.remove("active");
                document.querySelector('label[for][for="mod_ord_quantita"]').classList.remove("active");
                document.querySelector('label[for][for="mod_ord_prezzo"]').classList.remove("active");
                document.querySelector('label[for][for="mod_ord_data"]').classList.remove("active");

                M.toast({ html: data })
            });
    }
}

function Delete() {
    // check if mode is "inventario" or "ordini"
    var mode = localStorage.mode;
    if (mode == "inventario") {
        // get data from form
        var id = document.querySelector('#del_inv_id').value;

        // send data to /delete
        fetch('../delete.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                mode: mode,
                id: id
            })
        })
            .then(response => response.json())
            .then(data => {
                // clear form
                document.querySelector('#del_inv_id').value = "";

                M.toast({ html: data })
            });
    }

    if (mode == "ordini") {
        // get data from form
        var id = document.querySelector('#del_ord_id').value;

        // send data to /delete
        fetch('../delete.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                mode: mode,
                id: id
            })
        })
            .then(response => response.json())
            .then(data => {
                // clear form
                document.querySelector('#del_ord_id').value = "";

                M.toast({ html: data })
            });
    }
}

function Export() {
    // check if mode is "inventario" or "ordini"
    var mode = localStorage.mode;

    // open new tab with export.php
    window.open("../export.php?mode=" + mode, "_blank");
}

function Import()
{
    // check if mode is "inventario" or "ordini"
    var mode = localStorage.mode;

    // send both files from input "#imp_inv" and "#imp_ord" to "../import.php"
    var inv = document.querySelector('#imp_inv').files[0];
    var ord = document.querySelector('#imp_ord').files[0];

    // create formData
    var formData = new FormData();
    formData.append("mode", mode);
    formData.append("inventario", inv);
    formData.append("ordini", ord);

    // send formData to "../import.php"
    fetch('../import.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            // clear inputs
            document.querySelector('#imp_inv').value = "";
            document.querySelector('#imp_ord').value = "";

            document.querySelector('#imp_inv_text').value = "";
            document.querySelector('#imp_ord_text').value = "";

            M.toast({ html: data })
        });
}

function UserSettings()
{
    // get data from form, immagine, email, vecchia password, nuova password, conferma password
    var immagine = document.querySelector('#set_picture').value;
    var email = document.querySelector('#set_email').value;
    var vecchia_password = document.querySelector('#set_old_password').value;
    var nuova_password = document.querySelector('#set_new_password').value;
    var conferma_password = document.querySelector('#set_new_password_confirm').value;

    // check if nuova password and conferma password are equal
    if (nuova_password != conferma_password) {
        M.toast({ html: "Le password non coincidono" });
        return;
    }

    // send data to "../user_settings.php"
    fetch('../user_settings.php', {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            immagine: immagine,
            email: email,
            vecchia_password: vecchia_password,
            nuova_password: nuova_password
        })
    })
        .then(response => response.json())
        .then(data => {
            // clear form
            document.querySelector('#set_old_password').value = "";
            document.querySelector('#set_new_password').value = "";
            document.querySelector('#set_new_password_confirm').value = "";

            // remove from all labels active class
            document.querySelector('label[for][for="set_old_password"]').classList.remove("active");
            document.querySelector('label[for][for="set_new_password"]').classList.remove("active");
            document.querySelector('label[for][for="set_new_password_confirm"]').classList.remove("active");

            M.toast({ html: data })
        });
}

function fillTable(length, table) {
    var table = document.getElementById("table_visualizza");
    var th = document.getElementById("table_header");
    if (length < 10) {
        for (var i = 0; i < 10 - length; i++) {
            var row = table.insertRow(-1);
            row.insertCell(0).innerHTML = "-";
            for (var j = 1; j < th.rows[0].cells.length; j++)
                row.insertCell(j).innerHTML = "<wbr>";
        }
    }
}

function createTable(data) {
    var table = document.getElementById("table_visualizza");
    table.innerHTML = "";

    var mode = localStorage.mode;
    if (mode == "inventario") {
        data.data.forEach((item) => {
            var arr = new Array(item.id, item.nome_prodotto, item.descrizione, item.categoria, item.quantita, item.prezzo);
            var row = table.insertRow(-1);

            for (var i = 0; i < arr.length; i++)
                row.insertCell(i).innerHTML = arr[i];
        });
    }

    if (mode == "ordini") {
        data.data.forEach((item) => {
            var arr = new Array(item.id, item.nome, item.cognome, item.nome_prodotto, item.quantita, item.prezzo, item.data_ordine);
            var row = table.insertRow(-1);

            for (var i = 0; i < arr.length; i++)
                row.insertCell(i).innerHTML = arr[i];
        });
    }
}
