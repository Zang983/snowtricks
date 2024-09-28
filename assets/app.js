import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

const showMoreBtn = document.querySelector('#showMoreBtn');
const showMoreLink = document.querySelector('#showMoreLink');
const tricksContainer = document.querySelector('#tricks')
if (showMoreBtn && showMoreLink) {
    let count=1;
    showMoreBtn.style.display = 'block';
    showMoreBtn.style.visibility = 'visible';
    showMoreLink.style.display = 'none';
    showMoreLink.style.visibility = 'hidden';
    showMoreBtn.addEventListener('click', () => {
        count++;
        fetch(`http://localhost:8000/${count}`)
            .then(res => res.text())
            .then(datas => {
                tricksContainer.innerHTML += datas;
            })
            .catch(err => console.log(err));
    });
}
