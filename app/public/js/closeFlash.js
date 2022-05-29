const flashes = [...document.querySelectorAll('.alert')];
flashes.forEach(flash => {
    flash.querySelector('.close-flash').addEventListener('click', function (e) {
        flash.classList.add('alert--closed');
    })
});