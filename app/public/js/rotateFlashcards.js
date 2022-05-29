/**
 * Animates rotating flashcards.
 */
const card1 = document.querySelector('.flashcards-container__flashcards--first');
const card2 = document.querySelector('.flashcards-container__flashcards--second');
const card3 = document.querySelector('.flashcards-container__flashcards--third');

const cards = [card1, card2, card3];

cards.forEach(function (card) {
   card.addEventListener('click', (e) => {
       card.classList.toggle('flashcards-container__flashcard--active');
   })
});
