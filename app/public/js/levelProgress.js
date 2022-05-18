const levelProgressBox = document.querySelector('.level-progress');
const levelProgressBar = document.querySelector('.level-progress__meter');
const currentExp = document.querySelector('.level-progress__current-experience');
const requiredExp = document.querySelector('.level-progress__required-experience');

if (levelProgressBox) {
    const currentRequiredExpRatio = parseInt(currentExp.innerText)/parseInt(requiredExp.innerText);
    levelProgressBar.style.transform = `translateX(${currentRequiredExpRatio * 100 - 100}%)`;
}