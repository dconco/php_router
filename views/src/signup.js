let btn = document.querySelector('button');

/* IF SUBMIT BUTTON IS CLICKED */
btn.onclick = function(e) {
    e.preventDefault();
    
    let form = document.querySelector('form');
    
    let pwd = document.getElementById('pwd');
    let name = document.getElementById('name');
    let email = document.getElementById('email');

    // if fields are empty
    if (pwd.value === "" || name.value === "" || email.value === "") 
    {
        alert("All fields are required!");
        return;
    }
    
    let formData = new FormData(form);
    
    /* SUBMIT FORM TO REGISTER API */
    axios({
        method: 'POST',
        url: '/api/new/user/register',
        data: formData
    })
    .then(function (res) {
        let data = JSON.stringify(res.data);
        alert(data);
    })
    .catch(function(err) {
        alert(err)
    });
}