<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SESSION['loggedin'] != true) {
    header('Location: ..');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.png" type="image/png">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../utilities.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <title>SimpleStorage™ - Dashboard</title>
</head>

<body>
    <header>
        <nav class="deep-purple" style="position: fixed; top: 0; z-index: 100;">
            <div style="margin: 0 1rem; white-space: nowrap;" class="nav-wrapper">
                <a href="#" class="brand-logo">
                    <i style="font-size: 3rem" class="material-icons hide-on-small-only">cloud</i>SimpleStorage™
                </a>
                <ul id="nav-mobile" class="right">
                    <li>
                        <a href="#" data-target="slide-out" class="sidenav-trigger">
                            <i class="material-icons">menu</i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>


    <ul style="margin-top: -1px; overflow: auto;" id="slide-out" class="sidenav sidenav-fixed invisible-top">
        <li>
            <div class="user-view">
                <div class="background">
                    <img src="../assets/background.png">
                </div>
                <a><img id="sidenav-picture" class="circle" src=""></a>
                <a><span id="sidenav-username" class="white-text name truncate"></span></a>
                <a><span id="sidenav-email" class="white-text email truncate"></span></a>
            </div>
        </li>
        <li><a class="subheader">Area applicazione</a></li>
        <li id="switch"><a href="javascript:;"><i class="spinny material-icons">sync</i>Modalità inventario</a></li>
        <li><a href="#read"><i class="material-icons">preview</i>Visualizza dati</a></li>
        <li><a href="#create"><i class="material-icons">add_box</i>Aggiungi dati</a></li>
        <li><a href="#update"><i class="material-icons">app_registration</i>Modifica dati</a></li>
        <li><a href="#delete"><i class="material-icons">delete</i>Elimina dati</a></li>
        <li>
            <div class="divider"></div>
        </li>
        <li><a class="subheader">Trasferimento file</a></li>
        <li><a href="#export"><i class="material-icons">file_download</i>Esporta dati</a></li>
        <li><a href="#import"><i class="material-icons">file_upload</i>Importa dati</a></li>
        <li>
            <div class="divider"></div>
        </li>
        <li><a class="subheader">Area utente</a></li>
        <li><a href="#settings"><i class="material-icons">settings</i>Impostazioni</a></li>
        <li><a href="#logout"><i class="material-icons">exit_to_app</i>Logout</a></li>
    </ul>

    <main class="pt-5">
        <!-- Visualizza dati -->
        <div id="read-tab" class="main-tab container mt-5" hidden>
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="deep-purple-text">Visualizza dati</h3>
                            <p>
                                Questa è la pagina in cui puoi visualizzare i dati salvati.
                            </p>

                            <table class="my-5 highlight responsive-table">
                                <thead id="table_header">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome Prodotto</th>
                                        <th>Descrizione</th>
                                        <th>Categoria</th>
                                        <th>Quantità</th>
                                        <th>Prezzo</th>
                                    </tr>
                                </thead>

                                <tbody id="table_visualizza">

                                </tbody>
                            </table>

                            <ul id="pagination" class="pagination center" hidden>
                                <!-- <li class="disabled"><a href="javascript:;"><i class="material-icons">chevron_left</i></a></li> -->
                                <!-- <li class="active"><a href="javascript:;">1</a></li> -->
                                <!-- <li class="waves-effect"><a href="javascript:;">2</a></li> -->
                                <!-- <li class="waves-effect"><a href="javascript:;">3</a></li> -->
                                <!-- <li class="waves-effect"><a href="javascript:;">4</a></li> -->
                                <!-- <li class="waves-effect"><a href="javascript:;">5</a></li> -->
                                <!-- <li class="waves-effect"><a href="javascript:;"><i class="material-icons">chevron_right</i></a></li> -->
                            </ul>

                            <!-- Crea pulsante aggiorna dati largo quanto il suo contenitore -->
                            <div style="margin-bottom: 0;" class="row">
                                <div class="input-field col s12">
                                    <button id="update-button" class="deep-purple waves-effect waves-light btn-large col s12">
                                        Aggiorna dati
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inserisci dati -->
        <div id="create-tab" class="main-tab container mt-5" hidden>
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="deep-purple-text">Inserisci dati</h3>
                            <p>
                                Questa è la pagina in cui puoi inserire nuovi dati.
                            </p>

                            <form id="create-inventario" class="mt-5" hidden>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="inv_nome" type="text">
                                        <label for="inv_nome">Nome prodotto</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="inv_descrizione" type="text">
                                        <label for="inv_descrizione">Descrizione</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="inv_categoria" type="text">
                                        <label for="inv_categoria">Categoria</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="inv_quantita" type="text">
                                        <label for="inv_quantita">Quantità</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="inv_prezzo" type="number">
                                        <label for="inv_prezzo">Prezzo</label>
                                    </div>
                                </div>
                                <div style="margin-bottom: 0;" class="row">
                                    <div class="input-field col s12">
                                        <button onclick="create()" class="deep-purple waves-effect waves-light btn-large col s12">
                                            Inserisci prodotto
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form id="create-ordini" class="mt-5" hidden>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="ord_nome" type="text">
                                        <label for="ord_nome">Nome</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="ord_cognome" type="text">
                                        <label for="ord_cognome">Cognome</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="ord_prodotto" type="text">
                                        <label for="ord_prodotto">Prodotto</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="ord_quantita" type="text">
                                        <label for="ord_quantita">Quantità</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="ord_prezzo" type="number">
                                        <label for="ord_prezzo">Prezzo (opzionale)</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="ord_data" type="text" class="datepicker">
                                        <label for="ord_data">Data (aaaa-mm-gg)</label>
                                    </div>
                                </div>
                                <div style="margin-bottom: 0;" class="row">
                                    <div class="input-field col s12">
                                        <button onclick="create()" class="deep-purple waves-effect waves-light btn-large col s12">
                                            Inserisci ordine
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modifica dati -->
        <div id="update-tab" class="main-tab container mt-5" hidden>
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="deep-purple-text">Modifica dati</h3>
                            <p>
                                Questa è la pagina in cui puoi modificare i dati.
                            </p>

                            <form id="update-inventario" class="mt-5" hidden>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="mod_inv_id" type="number">
                                        <label for="mod_inv_id">ID prodotto da modificare (obbligatorio)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="mod_inv_nome" type="text">
                                        <label for="mod_inv_nome">Nome prodotto</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="mod_inv_descrizione" type="text">
                                        <label for="mod_inv_descrizione">Descrizione</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="mod_inv_categoria" type="text">
                                        <label for="mod_inv_categoria">Categoria</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="mod_inv_quantita" type="text">
                                        <label for="mod_inv_quantita">Quantità</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="mod_inv_prezzo" type="number">
                                        <label for="mod_inv_prezzo">Prezzo</label>
                                    </div>
                                </div>
                                <div style="margin-bottom: 0;" class="row">
                                    <div class="input-field col s12">
                                        <button onclick="update()" class="deep-purple waves-effect waves-light btn-large col s12">
                                            Modifica prodotto
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form id="update-ordini" class="mt-5" hidden>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="mod_ord_id" type="number">
                                        <label for="mod_ord_id">ID ordine da modificare (obbligatorio)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="mod_ord_nome" type="text">
                                        <label for="mod_ord_nome">Nome</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="mod_ord_cognome" type="text">
                                        <label for="mod_ord_cognome">Cognome</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="mod_ord_prodotto" type="text">
                                        <label for="mod_ord_prodotto">Prodotto</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="mod_ord_quantita" type="text">
                                        <label for="mod_ord_quantita">Quantità</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s6">
                                        <input id="mod_ord_prezzo" type="number">
                                        <label for="mod_ord_prezzo">Prezzo</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input id="mod_ord_data" type="text" class="datepicker">
                                        <label for="mod_ord_data">Data</label>
                                    </div>
                                </div>
                                <div style="margin-bottom: 0;" class="row">
                                    <div class="input-field col s12">
                                        <button onclick="update()" class="deep-purple waves-effect waves-light btn-large col s12">
                                            Modifica ordine
                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Elimina dati -->
        <div id="delete-tab" class="main-tab container mt-5" hidden>
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="deep-purple-text">Elimina dati</h3>
                            <p>
                                Questa è la pagina in cui puoi eliminare i dati.
                            </p>

                            <form id="delete-inventario" class="mt-5" hidden>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="del_inv_id" type="number">
                                        <label for="del_inv_id">ID prodotto da eliminare</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <button onclick="Delete()" class="tooltipped deep-purple waves-effect waves-light btn-large col s12" data-position="bottom" data-tooltip="Eliminare un prodotto comporta l'eliminazione di tutti i suoi ordini">
                                            Elimina prodotto
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form id="delete-ordini" class="mt-5" hidden>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="del_ord_id" type="number">
                                        <label for="del_ord_id">ID ordine da eliminare</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <button onclick="Delete()" class="deep-purple waves-effect waves-light btn-large col s12">
                                            Elimina ordine
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export dati -->
        <div id="export-tab" class="main-tab container mt-5" hidden>
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="deep-purple-text">Esporta dati</h3>
                            <p>
                                Questa è la pagina in cui puoi esportare i dati.
                            </p>

                            <form id="export-inventario" class="mt-5" hidden>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <button onclick="Export()" class="deep-purple waves-effect waves-light btn-large col s12">
                                            Esporta inventario
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <form id="export-ordini" class="mt-5" hidden>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <button onclick="Export()" class="deep-purple waves-effect waves-light btn-large col s12">
                                            Esporta ordini
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Import dati -->
        <div id="import-tab" class="main-tab container mt-5" hidden>
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="deep-purple-text">Importa dati</h3>
                            <p>
                                Questa è la pagina in cui puoi importare i dati.
                            </p>

                            <form id="import-general" class="mt-5">
                                <div class="row">

                                    <div class="file-field input-field s12 px-3">
                                        <div class="btn deep-purple">
                                            <span>File</span>
                                            <input id="imp_inv" type="file">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input id="imp_inv_text" class="file-path" type="text" placeholder="Seleziona file dati Inventario">
                                        </div>
                                    </div>

                                    <div class="file-field input-field s12 px-3">
                                        <div class="btn deep-purple">
                                            <span>File</span>
                                            <input id="imp_ord" type="file">
                                        </div>
                                        <div class="file-path-wrapper">
                                            <input id="imp_ord_text" class="file-path" type="text" placeholder="Seleziona file dati Ordini">
                                        </div>
                                    </div>

                                    <div class="input-field col s12">
                                        <button onclick="Import()" class="tooltipped deep-purple waves-effect waves-light btn-large col s12" data-position="bottom" data-tooltip="Questa operazione elimina tutti i dati attuali.">
                                            Importa dati
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impostazioni -->
        <div id="settings-tab" class="main-tab container mt-5" hidden>
            <div class="row">
                <div class="col s12">
                    <div class="card">
                        <div class="card-content">
                            <h3 class="deep-purple-text">Impostazioni</h3>
                            <p>
                                Questa è la pagina in cui puoi modificare le impostazioni.
                            </p>

                            <form id="settings-general" class="mt-5">
                                <!-- Form per modificare immagine, email, password -->
                                <!-- select per scegliere immagine -->
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select id="set_picture">
                                            <option value="" disabled selected>Scegli un'immagine</option>
                                            <option value="1">Immagine 1</option>
                                            <option value="2">Immagine 2</option>
                                            <option value="3">Immagine 3</option>
                                            <option value="4">Immagine 4</option>
                                            <option value="5">Immagine 5</option>
                                            <option value="6">Immagine 6</option>
                                        </select>
                                        <label>Scegli un'immagine</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="set_email" type="email">
                                        <label for="set_email">Email</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="set_old_password" type="password">
                                        <label for="set_old_password">Vecchia password</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="set_new_password" type="password">
                                        <label for="set_new_password">Nuova password (opzionale)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <input id="set_new_password_confirm" type="password">
                                        <label for="set_new_password_confirm">Conferma password (opzionale)</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <button onclick="UserSettings()" class="deep-purple waves-effect waves-light btn-large col s12">
                                            Salva impostazioni
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="script.js"></script>
    <script>
        function InitializeSidenav() {
            var elems = document.querySelectorAll('.sidenav');
            var instances = M.Sidenav.init(elems, {});
        }

        function InitializeDatepicker() {
            var elems = document.querySelectorAll('.datepicker');
            var instances = M.Datepicker.init(elems, {
                firstday: 1,
                format: 'yyyy-mm-dd',
                i18n: {
                    months: ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'],
                    monthsShort: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
                    weekdays: ['Domenica', 'Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato'],
                    weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'],
                    weekdaysAbbrev: ['D', 'L', 'M', 'M', 'G', 'V', 'S']
                }
            });
        }

        function InitializeTooltips() {
            var elems = document.querySelectorAll('.tooltipped');
            var instances = M.Tooltip.init(elems, {});
        }

        function InitializeCollapsibles() {
            var elems = document.querySelectorAll('.collapsible');
            var instances = M.Collapsible.init(elems, {});
        }

        function InitializeSelect() {
            var elems = document.querySelectorAll('select');
            var instances = M.FormSelect.init(elems, {});
        }

        document.addEventListener('DOMContentLoaded', function() {
            InitializeDatepicker();
            InitializeSidenav();
            InitializeTooltips();
            InitializeCollapsibles();
            InitializeSelect();

            localStorage.mode = !localStorage.mode ? 'inventario' : localStorage.mode;

            sortTabs(document.location.hash == "" ? "#read" : document.location.hash);
            modeManager();

            // fetch user data from /userinfo
            fetch('../userinfo.php')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('#sidenav-picture').src = "../" + data.picture;
                    document.querySelector('#sidenav-username').textContent = data.username;
                    document.querySelector('#sidenav-email').textContent = data.email;

                    document.querySelector('#set_picture').value = Number(data.picture.replace( /\D+/g, ''));
                    M.FormSelect.init(document.querySelector('#set_picture'));

                    document.querySelector('#set_email').value = data.email;
                    document.querySelector('label[for][for="set_email"]').classList.add("active");

                });

            document.querySelector('#update-button').click();
        });

        // quando viene cliccato il pulsante "Modalità inventario" cambia il valore di mode in ordini
        document.querySelector('#switch').addEventListener('click', function() {
            localStorage.mode = localStorage.mode == 'inventario' ? 'ordini' : 'inventario';
            modeManager();

            document.querySelector('.spinny').style.transform = 'rotate(0deg)';

            setTimeout(() => {
                var spinner = document.querySelector('.spinny');
                var rotate = Number(spinner.style.transform.match(/-\d+/)?. [0] || 0);

                if (rotate == 0) {
                    spinner.style.transform = "rotate(0deg)";
                }

                spinner.style.transform = 'rotate(' + (rotate + -360) + 'deg)';
            }, 1);

        });

        window.addEventListener('hashchange', hce => {
            var hash = new URL(hce.newURL).hash;

            // check if hash is logout
            if (hash == "#logout") {
                // redirect to logout.php
                window.location.href = "../logout.php";
            }


            sortTabs(hash);
        });

        // create function sortTabs that takes a hash and shows the correct tab
        function sortTabs(hash) {
            var tab = document.querySelector(hash + "-tab");
            if (tab)
                tab.hidden = false;

            // hide all other tabs 
            var tabs = document.querySelectorAll('.main-tab');
            for (var i = 0; i < tabs.length; i++) {
                if (tabs[i] != tab) {
                    tabs[i].hidden = true;
                }
            }
        }

        // disable page reload on form submit
        document.querySelectorAll('form').forEach(element => {
            element.addEventListener('submit', function(e) {
                e.preventDefault();
            });
        });

        // add click event listener to "update-button"
        document.querySelector('#update-button').addEventListener('click', function() {
            document.querySelector("#pagination").hidden = false;
            firstRead();
        });
    </script>
    <script>
        if (location.protocol !== 'https:') {
            location.replace(`https:${location.href.substring(location.protocol.length)}`);
        }
    </script>

</body>

</html>