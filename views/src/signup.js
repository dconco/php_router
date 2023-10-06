let btn = document.querySelector('button');

/* IF SUBMIT BUTTON IS CLICKED */
btn.onclick = async function(e) {
    e.preventDefault();

    let form = document.querySelector('form');

    let pwd = document.getElementById('pwd');
    let name = document.getElementById('name');
    let email = document.getElementById('email');

    // if fields are empty
    if (pwd.value === "" || name.value === "" || email.value === "") {
        alert("All fields are required!");
        return;
    }

    let formData = new FormData(form);
    const url = '/api/v1/account/register';


    /* SEND POST REQUEST TO API */
    const PostRequest = await axios({
        'method': 'POST',
        'data': formData,
        'url': url,
        'baseURL': 'http://localhost/projects/php_router'
    });

    console.log(PostRequest.data);

    if (PostRequest.status == 200 && PostRequest.statusText == "OK") {
        const Inputs = document.querySelectorAll('input');
        Inputs.forEach(values => values.value = "");
    }
}