const imageInputs = document.querySelectorAll('.form-check label');

for (let i=0; i < imageInputs.length; i++) {
    imageInputs[i].innerHTML = `<img class="user-image user-image--medium" src="/images/avatars/${i+1}.jpg" alt="">`;
}