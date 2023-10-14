const storeJWT = {};
const form = document.forms[0];

const getResBtn = document.querySelector('#getRes');
getResBtn.style.display = 'none';

/* IF SUBMIT BUTTON IS CLICKED */
form.onsubmit = async function(e) {
    e.preventDefault();

    const pwd = document.getElementById('pwd');
    const name = document.getElementById('name');
    const email = document.getElementById('email');

    const url = '/api/v1/account/register';

    /* SEND POST REQUEST TO API */
    const PostRequest = await axios({
        'url': url,
        'method': 'POST',

        'data': JSON.stringify({
            fullname: name.value,
            email: email.value,
            password: pwd.value
        }),
        'headers': {
            'Content-type': 'Application/json'
        },
        'baseURL': 'http://localhost/projects/php_router'
    });

    try {
        // to the storeJWT object
        storeJWT.setJWT = function(data) {
            this.JWT = data;
        }

        if (PostRequest.status >= 200 && PostRequest.status <= 299) {
            const jwt = await PostRequest.data;
            storeJWT.setJWT(jwt.data[1].access_token);

            window.location.href = '/projects/php_router/profile/' + jwt.data[0].user_id;
        }
    } catch (error) {
        console.log(error);
    }
}

function getCookie(cookieName) {
    const cookies = document.cookie.split('; ');

    for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i].split('=');
        const name = cookie[0];
        const value = decodeURIComponent(cookie[1]);

        if (name === cookieName) {
            return value;
        }
    }
    return false;
}

/*
getResBtn.addEventListener('click', async(e) => {
    const result = await axios.get('http://localhost/projects/php_router/Auth/resource.php', {
        'headers': {
            'Authorization': `Bearer ${storeJWT.JWT}`
        }
    });

    const timestamp = await result.data;
    console.log(timestamp;
)
});*/