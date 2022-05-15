const pieceLinkDiv = document.querySelector('#piece_link').parentElement;
let pieceLinkInput = document.querySelector('#piece_link');

if (pieceLinkDiv) {
    const embedVideo = document.createElement('iframe');
    embedVideo.setAttribute('id', 'ytplayer');
    embedVideo.setAttribute('class', 'ytplayer--form');
    embedVideo.setAttribute('type', 'text/html');
    extractYoutubeUrlAndTime(pieceLinkInput.value, embedVideo);
    embedVideo.setAttribute('frameborder', '0');
    embedVideo.setAttribute('allowfullscreen', '1');

    pieceLinkDiv.appendChild(embedVideo);

    pieceLinkInput.addEventListener('input', () => {
        extractYoutubeUrlAndTime(pieceLinkInput.value, embedVideo);
    });
}

function extractYoutubeUrlAndTime(url, embedVideo) {
    const idPattern = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
    const idMatch = url.match(idPattern);
    let id = '';
    if (idMatch && idMatch[2].length === 11) {
        id = idMatch[2];
    }

    let time = '0';
    const timePattern = /t=([0-9]+)/;
    const timeMatch = url.match(timePattern);
    if (timeMatch) {
        time = timeMatch[1];
    }

    embedVideo.setAttribute('src', 'https://www.youtube.com/embed/' + id + '?start=' + time);
}