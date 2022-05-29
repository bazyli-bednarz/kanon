const infoBox = document.querySelector('#info-box-hideable');
const showHideButton = document.querySelector('#info-show-hide');

if (showHideButton) {
    showHideButton.addEventListener("click", function () {
        infoBox.classList.toggle('hidden');
    });
}

