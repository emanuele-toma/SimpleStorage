<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Favicon -->
  <link rel="shortcut icon" href="favicon.png" type="image/png">
  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="utilities.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!-- Compiled and minified JavaScript -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <script src="https://js.hcaptcha.com/1/api.js?hl=it" async defer></script>
  <title>SimpleStorage™</title>
</head>

<body style="height: 100%;" class="deep-purple lighten-4">
  <header>
    <nav class="deep-purple">
      <div style="margin: 0 1rem; white-space: nowrap;" class="nav-wrapper">
        <a href="#" class="brand-logo" tabindex="-1"><i style="font-size: 3rem" class="material-icons hide-on-small-only">cloud</i>SimpleStorage™</a>
      </div>
    </nav>
  </header>
  <main>
    <form>
      <div class="white login-form">
        <div id="tab-login">
          <div class="row">
            <h4 class="flow-text">Effettua il login</h4>
            <div id="login-error" class="red-text mb-2 animate__animated animate__fadeIn animate__faster" hidden>
              Errore: <span id="login-error-text" class="black-text"></span>
            </div>
            <div class="input-field col s12">
              <input id="login-user" type="text">
              <label for="login-user">Nome utente o email</label>
            </div>
            <div class="input-field col s12">
              <input id="login-password" type="password">
              <label for="login-password">Password</label>
            </div>
            <div class="input-field col s12 no-padding">
              <button data-callback="login" data-sitekey="06101d06-e00d-4701-a3ef-45722eeafcfb" class="h-captcha light-rounded deep-purple text-bold waves-effect waves-light btn btn-large col s12">
                Accedi
              </button>
            </div>
            <div class="col s3 left-align">
              <a class="btn-tab" href="#tab-register">Registrati</a>
            </div>
            <div class="col s9 right-align">
              <a class="btn-tab" href="#tab-forgot-password">Recupera password</a>
            </div>
          </div>
        </div>
    </form>

    <form>
      <div id="tab-register" hidden>
        <div class="row">
          <h4 class="flow-text">Registrazione</h4>
          <div id="reg-error" class="red-text mb-2 animate__animated animate__fadeIn animate__faster" hidden>
            Errore: <span id="reg-error-text" class="black-text"></span>
          </div>
          <div id="reg-success" class="green-text mb-2 animate__animated animate__fadeIn animate__faster" hidden>
            <span id="reg-success-text" class="green-text"></span>
          </div>
          <div class="input-field col s12">
            <input id="reg-username" type="text">
            <label for="reg-username">Nome utente</label>
          </div>
          <div class="input-field col s12">
            <input id="reg-email" type="email">
            <label for="reg-email">Email</label>
          </div>
          <div class="input-field col s12">
            <input id="reg-password" type="password">
            <label for="reg-password">Password</label>
          </div>
          <div class="input-field col s12 no-padding">
            <button data-callback="register" data-sitekey="ce4d2aa3-57b2-4d90-9a4a-c4996c6584e4" class="h-captcha light-rounded deep-purple text-bold waves-effect waves-light btn btn-large col s12">
              Registrati
            </button>
          </div>
          <div class="col s3 left-align">
            <a class="btn-tab" href="#tab-login">Login</a>
          </div>
          <div class="col s9 right-align">
            <a class="btn-tab" href="#tab-forgot-password">Recupera password</a>
          </div>
        </div>
      </div>
    </form>

    <form>
      <div id="tab-forgot-password" hidden>
        <div class="row">
          <h4 class="flow-text">Recupera password</h4>
          <div class="input-field col s12">
            <input id="forgot-email" type="email">
            <label for="forgot-email">Email</label>
          </div>
          <div class="input-field col s12 no-padding">
            <button data-callback="forgot_password" data-sitekey="ce4d2aa3-57b2-4d90-9a4a-c4996c6584e4" class="h-captcha light-rounded deep-purple text-bold waves-effect waves-light btn btn-large col s12">
              Recupera password
            </button>
          </div>
          <div class="col s3 left-align">
            <a class="btn-tab" href="#tab-register">Registrati</a>
          </div>
          <div class="col s9 right-align">
            <a class="btn-tab" href="#tab-login">Login</a>
          </div>
        </div>
      </div>
    </form>
  </main>

  <footer class="page-footer deep-purple lighten-2">
    <div class="footer-copyright deep-purple">
      <div class="container">
        © 2022 SimpleStorage™
        <div class="right">
          Protetto da <a class="white-text" target="_blank" href="https://www.hcaptcha.com/terms">hCaptcha</a>
        </div>

      </div>
    </div>
  </footer>


  <script>
    sortTabs(document.location.hash);

    window.addEventListener('hashchange', hce => {
      sortTabs(new URL(hce.newURL).hash);
    });

    document.querySelectorAll(".btn-tab").forEach((el, key) => {
      el.addEventListener('click', () => {
        sortTabs(el.href)
      })
    });

    function sortTabs(id) {
      document.querySelector('#login-error').hidden = true;
      document.querySelector('#reg-error').hidden = true;

      if (id.endsWith('#tab-login')) {
        document.querySelector('#tab-login').hidden = false;
        document.querySelector('#tab-register').hidden = true;
        document.querySelector('#tab-forgot-password').hidden = true;
      } else if (id.endsWith('#tab-register')) {
        document.querySelector('#tab-login').hidden = true;
        document.querySelector('#tab-register').hidden = false;
        document.querySelector('#tab-forgot-password').hidden = true;
      } else if (id.endsWith('#tab-forgot-password')) {
        document.querySelector('#tab-login').hidden = true;
        document.querySelector('#tab-register').hidden = true;
        document.querySelector('#tab-forgot-password').hidden = false;
      }
    }
  </script>
  <script>
    if (location.protocol !== 'https:') {
      location.replace(`https:${location.href.substring(location.protocol.length)}`);
    }
  </script>
  <script src="script.js"></script>
</body>

</html>