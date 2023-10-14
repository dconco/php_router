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


    const url = '/auth/jwt_auth.php';

    /* SEND POST REQUEST TO API */
    const PostRequest = await axios({
        'url': url,
        'method': 'POST',

        'data': JSON.stringify({
            pwd: pwd,
            name: name,
            email: email
        }),
        'headers': {
            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
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
            storeJWT.setJWT(jwt);

            form.style.display = 'none';
            getResBtn.style.display = 'block';
        }
    } catch (error) {
        console.log(error);
    }

    // Inserts the jwt
}

getResBtn.addEventListener('click', async(e) => {
    const result = await axios.get('http://localhost/projects/php_router/Auth/resource.php', {
        'headers': {
            'Authorization': `Bearer ${storeJWT.JWT}`
        }
    });

    const timestamp = await result.data;
    console.log(timestamp)
});