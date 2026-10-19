import 'bootstrap';

const mouvementReduit = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Globe 3D de l'accueil, chargé seulement sur les pages qui en ont un.
const conteneurGlobe = document.querySelector('[data-globe]');
if (conteneurGlobe) {
    import('./globe.js').then(({ creerGlobe }) => creerGlobe(conteneurGlobe));
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
