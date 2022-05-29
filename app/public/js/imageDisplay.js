/**
 * Modifies user profile picture form, so it displays images properly and also adds images that are yet to be unlocked.
 */
const imageInputs = document.querySelectorAll('.form-check label');
const imagesWrapper = document.querySelector('#profile_image_image');
let i=0;
for (i; i < imageInputs.length; i++) {
    imageInputs[i].innerHTML = `<img class="user-image user-image--medium" src="/images/avatars/${i+1}.jpg" alt="">`;
}

for (i; i < 20; i++) {
    const div = document.createElement('div');
    div.setAttribute('class', 'form-check form-check--locked');
    const image = document.createElement('img');
    image.setAttribute('src', `/images/avatars/${i+1}.jpg`);
    image.setAttribute('class', 'user-image user-image--medium user-image--locked');
    let $requiredLevel = 0;
    switch (i) {
        case 5:
        case 6:
        case 7:
            $requiredLevel = 2;
            break;
        case 8:
        case 9:
            $requiredLevel = 3;
            break;
        case 10:
        case 11:
            $requiredLevel = 4;
            break;
        case 12:
        case 13:
            $requiredLevel = 5;
            break;
        case 14:
        case 15:
            $requiredLevel = 6;
            break;
        case 16:
            $requiredLevel = 7;
            break;
        case 17:
            $requiredLevel = 8;
            break;
        case 18:
            $requiredLevel = 9;
            break;
        default:
            $requiredLevel = 10;
            break;
    }
    image.setAttribute('data-level', $requiredLevel.toString());
    const levelBox = document.createElement('div');
    levelBox.innerHTML = $requiredLevel + ' <i class="fa-solid fa-lock"></i>';
    levelBox.setAttribute('class', 'required-level');
    div.appendChild(image);
    div.appendChild(levelBox);
    imagesWrapper.appendChild(div);
}