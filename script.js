async function login(token)
{
    const username = document.querySelector('#login-user').value;
    const password = document.querySelector('#login-password').value;

    const error = document.querySelector('#login-error');
    const errorText = document.querySelector('#login-error-text');

    const risposta = await fetch('./login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            token: token,
            username: username,
            password: password
        })
    });

    const msg = await risposta.json();

    error.hidden = true;
    if (!risposta.ok)
    {
        errorText.innerText = msg;
        error.hidden = false;
        reset_animation(error);
    }

    if (risposta.ok)
    {
        // go to dashboard
        window.location.href = './dashboard';
    }
}

async function register(token)
{
    const username = document.querySelector('#reg-username').value;
    const email = document.querySelector('#reg-email').value;
    const password = document.querySelector('#reg-password').value;

    const error = document.querySelector('#reg-error');
    const errorText = document.querySelector('#reg-error-text');

    const success = document.querySelector('#reg-success');
    const successText = document.querySelector('#reg-success-text');

    const risposta = await fetch('./register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            token: token,
            username: username,
            email: email,
            password: password
        })
    });

    const msg = await risposta.json();

    error.hidden = true;
    if (!risposta.ok)
    {
        errorText.innerText = msg;
        error.hidden = false;
        reset_animation(error);
    }

    // if success go to login
    success.hidden = true;
    if (risposta.ok)
    {
        successText.innerText = msg;
        success.hidden = false;
        setTimeout(() => {
            window.location.replace('#tab-login');
        }, 1500);
    }

}

async function forgot_password(token)
{
    M.toast({html: 'Funzione al momento non disponibile...'})
}

function reset_animation(el) {
    el.style.animation = 'none';
    el.offsetHeight;
    el.style.animation = null; 
  }