import * as THREE from 'three';

/**
 * Globe 3D de l'accueil : sphère de points, arcs en pointillés depuis Paris vers les
 * destinations des prochains voyages, avion en papier en orbite.
 */

const RAYON = 1;
const DEPART = { nom: 'Paris', lat: 48.86, lon: 2.35 };

// Coordonnées des destinations reconnues (nom sans accents, en minuscules).
const VILLES = {
    amsterdam: [52.37, 4.9],
    athenes: [37.98, 23.73],
    barcelone: [41.39, 2.17],
    berlin: [52.52, 13.4],
    bordeaux: [44.84, -0.58],
    bruxelles: [50.85, 4.35],
    chamonix: [45.92, 6.87],
    dublin: [53.35, -6.26],
    edimbourg: [55.95, -3.19],
    florence: [43.77, 11.26],
    lisbonne: [38.72, -9.14],
    londres: [51.51, -0.13],
    lyon: [45.76, 4.84],
    madrid: [40.42, -3.7],
    marseille: [43.3, 5.37],
    munich: [48.14, 11.58],
    naples: [40.85, 14.27],
    nice: [43.7, 7.27],
    poitiers: [46.58, 0.34],
    prague: [50.08, 14.44],
    rome: [41.9, 12.5],
    seville: [37.39, -5.98],
    strasbourg: [48.58, 7.75],
    venise: [45.44, 12.32],
    vienne: [48.21, 16.37],
};

const DESTINATIONS_PAR_DEFAUT = ['rome', 'londres', 'chamonix', 'berlin', 'madrid'];

// Le globe ne dessine pas de continents : on écarte les destinations autour de Paris
// pour que les trajets européens restent lisibles (carte schématique).
const ECARTEMENT = 3;

function normaliser(destination) {
    return destination
        .split(',')[0]
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .trim()
        .toLowerCase();
}

function versVecteur(lat, lon, rayon = RAYON) {
    const phi = THREE.MathUtils.degToRad(90 - lat);
    const theta = THREE.MathUtils.degToRad(lon + 180);

    return new THREE.Vector3(
        -rayon * Math.sin(phi) * Math.cos(theta),
        rayon * Math.cos(phi),
        rayon * Math.sin(phi) * Math.sin(theta),
    );
}

function sphereDePoints(nombre) {
    // Répartition régulière des points (spirale de Fibonacci).
    const positions = new Float32Array(nombre * 3);
    const angleOr = Math.PI * (3 - Math.sqrt(5));

    for (let i = 0; i < nombre; i++) {
        const y = 1 - (i / (nombre - 1)) * 2;
        const r = Math.sqrt(1 - y * y);
        const angle = angleOr * i;
        positions.set([Math.cos(angle) * r * RAYON, y * RAYON, Math.sin(angle) * r * RAYON], i * 3);
    }

    const geometrie = new THREE.BufferGeometry();
    geometrie.setAttribute('position', new THREE.BufferAttribute(positions, 3));

    return new THREE.Points(geometrie, new THREE.PointsMaterial({
        color: 0xffffff,
        size: 0.024,
        transparent: true,
        opacity: 0.75,
        depthWrite: false,
    }));
}

function grille() {
    const groupe = new THREE.Group();
    const materiau = new THREE.LineBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.16 });
    const cercle = (rayon) => new THREE.BufferGeometry().setFromPoints(
        new THREE.EllipseCurve(0, 0, rayon, rayon).getPoints(96).map((p) => new THREE.Vector3(p.x, 0, p.y)),
    );

    for (const lat of [-60, -30, 0, 30, 60]) {
        const ligne = new THREE.LineLoop(cercle(Math.cos(THREE.MathUtils.degToRad(lat)) * RAYON * 1.001), materiau);
        ligne.position.y = Math.sin(THREE.MathUtils.degToRad(lat)) * RAYON;
        groupe.add(ligne);
    }

    for (let i = 0; i < 6; i++) {
        const meridien = new THREE.LineLoop(cercle(RAYON * 1.001), materiau);
        meridien.rotation.set(Math.PI / 2, (i / 6) * Math.PI, 0, 'YXZ');
        groupe.add(meridien);
    }

    return groupe;
}

function atmosphere() {
    return new THREE.Mesh(
        new THREE.SphereGeometry(RAYON * 1.06, 64, 64),
        new THREE.ShaderMaterial({
            transparent: true,
            blending: THREE.AdditiveBlending,
            depthWrite: false,
            vertexShader: `
                varying vec3 vNormal;
                void main() {
                    vNormal = normalize(normalMatrix * normal);
                    gl_Position = projectionMatrix * modelViewMatrix * vec4(position, 1.0);
                }
            `,
            fragmentShader: `
                varying vec3 vNormal;
                void main() {
                    // Lueur sur le contour seulement : nulle face à la caméra, forte sur les bords.
                    float intensite = pow(1.0 - abs(dot(vNormal, vec3(0.0, 0.0, 1.0))), 2.5);
                    gl_FragColor = vec4(0.75, 0.9, 1.0, 1.0) * intensite * 0.9;
                }
            `,
        }),
    );
}

function marqueur(position, principal = false) {
    const groupe = new THREE.Group();
    groupe.position.copy(position);
    groupe.lookAt(position.clone().multiplyScalar(2));

    const point = new THREE.Mesh(
        new THREE.CircleGeometry(principal ? 0.032 : 0.024, 24),
        new THREE.MeshBasicMaterial({ color: principal ? 0xffffff : 0xdbeafe, side: THREE.DoubleSide }),
    );
    const halo = new THREE.Mesh(
        new THREE.RingGeometry(0.04, 0.05, 32),
        new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0.6, side: THREE.DoubleSide }),
    );
    point.position.z = halo.position.z = 0.004;
    groupe.add(point, halo);

    return { groupe, halo };
}

function arc(depart, arrivee) {
    const distance = depart.distanceTo(arrivee);
    const milieu = depart.clone().add(arrivee).normalize().multiplyScalar(RAYON * (1.04 + distance * 0.6));
    const courbe = new THREE.QuadraticBezierCurve3(depart, milieu, arrivee);

    const ligne = new THREE.Line(
        new THREE.BufferGeometry().setFromPoints(courbe.getPoints(64)),
        new THREE.LineDashedMaterial({ color: 0xffffff, dashSize: 0.04, gapSize: 0.025, transparent: true, opacity: 1 }),
    );
    ligne.computeLineDistances();

    const comete = new THREE.Mesh(new THREE.SphereGeometry(0.018, 12, 12), new THREE.MeshBasicMaterial({ color: 0xffffff }));

    return { ligne, comete, courbe };
}

function avionEnPapier() {
    // Même silhouette que le logo : deux ailes blanches et une quille bleu clair, nez vers +z.
    const nez = [0, 0, 0.13];
    const aileGauche = [-0.08, 0, -0.08];
    const aileDroite = [0.08, 0, -0.08];
    const queue = [0, 0, -0.05];
    const quille = [0, -0.035, -0.06];

    const triangles = (sommets) => {
        const geometrie = new THREE.BufferGeometry();
        geometrie.setAttribute('position', new THREE.Float32BufferAttribute(sommets.flat(), 3));
        return geometrie;
    };

    const avion = new THREE.Group();
    avion.add(
        new THREE.Mesh(triangles([nez, aileGauche, queue, nez, queue, aileDroite]), new THREE.MeshBasicMaterial({ color: 0xffffff, side: THREE.DoubleSide })),
        new THREE.Mesh(triangles([nez, queue, quille]), new THREE.MeshBasicMaterial({ color: 0x93c5fd, side: THREE.DoubleSide })),
    );

    return avion;
}

export function creerGlobe(conteneur) {
    const canvas = document.createElement('canvas');
    let renderer;

    try {
        renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
    } catch {
        return; // Pas de WebGL : l'illustration SVG reste affichée.
    }

    const mouvementReduit = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    canvas.className = 'vs-globe-canvas';
    canvas.setAttribute('aria-hidden', 'true');
    conteneur.prepend(canvas);
    conteneur.classList.add('is-3d');

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(35, 1, 0.1, 100);
    camera.position.set(0, 0, 4.6);

    // L'Europe face à la caméra, légèrement au-dessus du centre ; rotation propre dans le groupe intérieur.
    const inclinaison = new THREE.Group();
    inclinaison.rotation.x = THREE.MathUtils.degToRad(30);
    const globe = new THREE.Group();
    const rotationEurope = -THREE.MathUtils.degToRad(90 + 12);
    globe.rotation.y = rotationEurope;
    inclinaison.add(globe);
    scene.add(inclinaison, atmosphere());

    globe.add(
        new THREE.Mesh(new THREE.SphereGeometry(RAYON * 0.995, 64, 64), new THREE.MeshBasicMaterial({ color: 0x1e3a8a, transparent: true, opacity: 0.35 })),
        sphereDePoints(1600),
        grille(),
    );

    // Destinations : celles des prochains voyages si on les connaît, sinon une sélection européenne.
    let villes = JSON.parse(conteneur.dataset.destinations || '[]').map(normaliser).filter((ville) => VILLES[ville] && ville !== 'paris');
    if (villes.length === 0) {
        villes = DESTINATIONS_PAR_DEFAUT;
    }
    villes = [...new Set(villes)];

    const pointDepart = versVecteur(DEPART.lat, DEPART.lon);
    const halos = [];
    const arcs = [];

    const depart = marqueur(pointDepart, true);
    globe.add(depart.groupe);
    halos.push(depart.halo);

    villes.forEach((ville) => {
        const [lat, lon] = VILLES[ville];
        const arrivee = versVecteur(
            DEPART.lat + (lat - DEPART.lat) * ECARTEMENT,
            DEPART.lon + (lon - DEPART.lon) * ECARTEMENT,
        );
        const destination = marqueur(arrivee);
        const trajet = arc(pointDepart, arrivee);
        globe.add(destination.groupe, trajet.ligne, trajet.comete);
        halos.push(destination.halo);
        arcs.push(trajet);
    });

    // Avion en orbite sur une trajectoire inclinée, avec son sillage en pointillés.
    const orbite = new THREE.Group();
    orbite.rotation.set(THREE.MathUtils.degToRad(-14), 0, THREE.MathUtils.degToRad(-18));
    const rayonOrbite = RAYON * 1.42;
    const sillage = new THREE.LineLoop(
        new THREE.BufferGeometry().setFromPoints(new THREE.EllipseCurve(0, 0, rayonOrbite, rayonOrbite).getPoints(128).map((p) => new THREE.Vector3(p.x, 0, p.y))),
        new THREE.LineDashedMaterial({ color: 0xffffff, dashSize: 0.02, gapSize: 0.07, transparent: true, opacity: 0.55 }),
    );
    sillage.computeLineDistances();
    const avion = avionEnPapier();
    orbite.add(sillage, avion);
    scene.add(orbite);

    // Glisser pour tourner le globe.
    let glisse = 0;
    let vitesse = 0;
    let dernierX = null;
    canvas.addEventListener('pointerdown', (e) => {
        dernierX = e.clientX;
        canvas.setPointerCapture(e.pointerId);
        canvas.classList.add('is-dragging');
    });
    canvas.addEventListener('pointermove', (e) => {
        if (dernierX === null) {
            return;
        }
        vitesse = (e.clientX - dernierX) * 0.006;
        glisse += vitesse;
        dernierX = e.clientX;
    });
    const relacher = () => {
        dernierX = null;
        canvas.classList.remove('is-dragging');
    };
    canvas.addEventListener('pointerup', relacher);
    canvas.addEventListener('pointercancel', relacher);

    function redimensionner() {
        const { width, height } = conteneur.getBoundingClientRect();
        renderer.setSize(width, height, false);
        camera.aspect = width / height;
        camera.updateProjectionMatrix();
    }
    new ResizeObserver(redimensionner).observe(conteneur);
    redimensionner();

    const horloge = new THREE.Clock();
    const position = new THREE.Vector3();
    const suivante = new THREE.Vector3();

    function dessiner() {
        const t = mouvementReduit ? 2 : horloge.getElapsedTime();

        if (dernierX === null) {
            vitesse *= 0.94;
            glisse += vitesse;
        }
        globe.rotation.y = rotationEurope + Math.sin(t * 0.18) * 0.3 + glisse;

        arcs.forEach(({ courbe, comete }, i) => {
            courbe.getPoint((t * 0.22 + i / arcs.length) % 1, comete.position);
        });

        halos.forEach((halo, i) => {
            const pulsation = (t * 0.8 + i * 0.3) % 1;
            halo.scale.setScalar(1 + pulsation * 1.4);
            halo.material.opacity = 0.7 * (1 - pulsation);
        });

        const angle = t * 0.35;
        position.set(Math.cos(angle) * rayonOrbite, 0, Math.sin(angle) * rayonOrbite);
        suivante.set(Math.cos(angle + 0.05) * rayonOrbite, 0, Math.sin(angle + 0.05) * rayonOrbite);
        avion.position.copy(position);
        avion.lookAt(orbite.localToWorld(suivante.clone()));

        renderer.render(scene, camera);
    }

    // N'anime que lorsque le globe est visible (avec un mouvement réduit, seule la souris le fait tourner).
    let visible = true;
    new IntersectionObserver(([entree]) => {
        visible = entree.isIntersecting;
    }).observe(conteneur);

    renderer.setAnimationLoop(() => {
        if (visible) {
            dessiner();
        }
    });
}
