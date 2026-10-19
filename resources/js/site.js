import 'bootstrap';

const mouvementReduit = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Globe 3D de l'accueil, chargé seulement sur les pages qui en ont un.
const conteneurGlobe = document.querySelector('[data-globe]');
if (conteneurGlobe) {
    import('./globe.js').then(({ creerGlobe }) => creerGlobe(conteneurGlobe));
}

// Barre de navigation : ombre dès qu'on a défilé.
const navbar = document.querySelector('.vs-navbar');
if (navbar) {
    const majNavbar = () => navbar.classList.toggle('is-scrolled', window.scrollY > 8);
    window.addEventListener('scroll', majNavbar, { passive: true });
    majNavbar();
}

// Compteurs : de 0 à leur valeur, avec un ralentissement en fin de course.
function animerCompteur(element) {
    const cible = Number(element.dataset.compteur);
    const duree = 1200;
    const debut = performance.now();

    const etape = (maintenant) => {
        const progression = Math.min((maintenant - debut) / duree, 1);
        const adoucie = 1 - Math.pow(1 - progression, 3);
        element.textContent = Math.round(cible * adoucie).toLocaleString('fr-FR');
        if (progression < 1) {
            requestAnimationFrame(etape);
        }
    };

    requestAnimationFrame(etape);
}

// Apparition au défilement : les éléments [data-reveal] d'un même parent apparaissent en cascade.
if (document.documentElement.classList.contains('vs-anim')) {
    const elements = document.querySelectorAll('[data-reveal]');

    elements.forEach((element) => {
        const freres = [...element.parentElement.children].filter((enfant) => enfant.hasAttribute('data-reveal'));
        element.style.setProperty('--vs-delay', `${Math.min(freres.indexOf(element), 6) * 90}ms`);
    });

    const observateur = new IntersectionObserver((entrees) => {
        entrees.forEach(({ isIntersecting, target }) => {
            if (! isIntersecting) {
                return;
            }

            target.classList.add('is-visible');
            target.querySelectorAll('[data-compteur]').forEach(animerCompteur);
            observateur.unobserve(target);
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    elements.forEach((element) => observateur.observe(element));
}

// Inclinaison 3D des cartes qui suivent le pointeur (souris uniquement).
if (! mouvementReduit && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    document.querySelectorAll('[data-tilt]').forEach((carte) => {
        carte.addEventListener('pointermove', (e) => {
            const { left, top, width, height } = carte.getBoundingClientRect();
            const x = (e.clientX - left) / width;
            const y = (e.clientY - top) / height;

            carte.style.setProperty('--vs-tilt-x', `${(0.5 - y) * 8}deg`);
            carte.style.setProperty('--vs-tilt-y', `${(x - 0.5) * 10}deg`);
            carte.style.setProperty('--vs-glare-x', `${x * 100}%`);
            carte.style.setProperty('--vs-glare-y', `${y * 100}%`);
        });

        carte.addEventListener('pointerleave', () => {
            carte.style.removeProperty('--vs-tilt-x');
            carte.style.removeProperty('--vs-tilt-y');
        });
    });
}
