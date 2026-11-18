/**
 * Barbería Filo 3D — escena del poste de barbero (Three.js r128)
 *
 * Qué hace:
 * - Dibuja un poste de barbero en 3D: cilindro con rayas, vidrio y tapas de cromo.
 * - Las rayas suben solas (como un poste real) y se aceleran con el scroll
 *   y al pasar el ratón por un servicio de la carta.
 * - El poste se inclina siguiendo el ratón y se desplaza a la derecha al bajar a los precios.
 * - Si el navegador no puede con 3D, se muestra un poste en CSS (clase .sin-3d).
 */
(function () {
  'use strict';

  var lienzo = document.querySelector('[data-escena]');
  var raiz = document.documentElement;
  if (!lienzo) return;
  if (typeof THREE === 'undefined') { raiz.classList.add('sin-3d'); return; }

  var renderer;
  try {
    renderer = new THREE.WebGLRenderer({ canvas: lienzo, antialias: true, alpha: true });
  } catch (e) {
    raiz.classList.add('sin-3d');
    return;
  }

  var reducir = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.outputEncoding = THREE.sRGBEncoding;

  var escena = new THREE.Scene();
  var camara = new THREE.PerspectiveCamera(35, 1, 0.1, 100);
  camara.position.set(0, 0, 10);

  /* ---------- Texturas generadas en el navegador (sin imágenes) ---------- */

  // Rayas diagonales rojo / blanco / azul que encajan sin cortes al repetirse.
  function texturaRayas() {
    var t = 512, periodo = 256, banda = periodo / 4;
    var c = document.createElement('canvas');
    c.width = c.height = t;
    var ctx = c.getContext('2d');
    var img = ctx.createImageData(t, t);
    var colores = [[214, 64, 47], [246, 244, 238], [23, 44, 75], [246, 244, 238]];
    for (var y = 0; y < t; y++) {
      for (var x = 0; x < t; x++) {
        var col = colores[Math.floor(((x + y) % periodo) / banda)];
        var i = (y * t + x) * 4;
        img.data[i] = col[0]; img.data[i + 1] = col[1]; img.data[i + 2] = col[2]; img.data[i + 3] = 255;
      }
    }
    ctx.putImageData(img, 0, 0);
    var tex = new THREE.CanvasTexture(c);
    tex.wrapS = tex.wrapT = THREE.RepeatWrapping;
    tex.repeat.set(1, 1.6);
    tex.encoding = THREE.sRGBEncoding;
    tex.anisotropy = Math.min(8, renderer.capabilities.getMaxAnisotropy());
    return tex;
  }

  // Entorno simple para que el cromo y el vidrio tengan reflejos.
  function entornoCromo() {
    function cara(arriba, abajo, acento) {
      var c = document.createElement('canvas');
      c.width = c.height = 64;
      var ctx = c.getContext('2d');
      var g = ctx.createLinearGradient(0, 0, 0, 64);
      g.addColorStop(0, arriba);
      g.addColorStop(0.55, '#7c889a');
      g.addColorStop(1, abajo);
      ctx.fillStyle = g;
      ctx.fillRect(0, 0, 64, 64);
      if (acento) { ctx.fillStyle = acento; ctx.fillRect(18, 22, 28, 12); }
      return c;
    }
    var claro = '#ffffff', oscuro = '#0a1526';
    var cubo = new THREE.CubeTexture([
      cara(claro, oscuro, 'rgba(214,64,47,.9)'), cara(claro, oscuro), // +x, -x
      cara(claro, claro), cara(oscuro, oscuro),                         // +y, -y
      cara(claro, oscuro, 'rgba(255,255,255,.9)'), cara(claro, oscuro)  // +z, -z
    ]);
    cubo.needsUpdate = true;
    return cubo;
  }

  var rayas = texturaRayas();
  var entorno = entornoCromo();

  /* ---------- El poste ---------- */

  var poste = new THREE.Group();

  var matRayas = new THREE.MeshStandardMaterial({ map: rayas, roughness: 0.38, metalness: 0 });
  var matCromo = new THREE.MeshStandardMaterial({ color: 0xffffff, metalness: 1, roughness: 0.16, envMap: entorno });
  var matVidrio = new THREE.MeshPhysicalMaterial({
    color: 0xffffff, metalness: 0, roughness: 0.04, transparent: true, opacity: 0.16,
    clearcoat: 1, envMap: entorno, envMapIntensity: 1.6, depthWrite: false
  });

  var alto = 4;
  poste.add(new THREE.Mesh(new THREE.CylinderGeometry(0.52, 0.52, alto, 96, 1, true), matRayas));
  poste.add(new THREE.Mesh(new THREE.CylinderGeometry(0.64, 0.64, alto + 0.04, 96, 1, true), matVidrio));

  // Tapa superior: aro + cúpula + bola
  var aroArriba = new THREE.Mesh(new THREE.CylinderGeometry(0.72, 0.72, 0.2, 96), matCromo);
  aroArriba.position.y = alto / 2 + 0.1;
  var cupula = new THREE.Mesh(new THREE.SphereGeometry(0.66, 64, 32, 0, Math.PI * 2, 0, Math.PI / 2), matCromo);
  cupula.position.y = alto / 2 + 0.2;
  var bola = new THREE.Mesh(new THREE.SphereGeometry(0.13, 32, 16), matCromo);
  bola.position.y = alto / 2 + 0.98;
  var cuello = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.08, 0.2, 24), matCromo);
  cuello.position.y = alto / 2 + 0.86;

  // Tapa inferior: aro + media esfera invertida + punta
  var aroAbajo = aroArriba.clone();
  aroAbajo.position.y = -alto / 2 - 0.1;
  var base = new THREE.Mesh(new THREE.SphereGeometry(0.66, 64, 32, 0, Math.PI * 2, Math.PI / 2, Math.PI / 2), matCromo);
  base.position.y = -alto / 2 - 0.2;
  var punta = bola.clone();
  punta.position.y = -alto / 2 - 0.98;

  poste.add(aroArriba, cupula, cuello, bola, aroAbajo, base, punta);
  escena.add(poste);

  /* ---------- Luces ---------- */
  escena.add(new THREE.AmbientLight(0xffffff, 0.45));
  var clave = new THREE.DirectionalLight(0xffffff, 1.15);
  clave.position.set(3, 5, 6);
  escena.add(clave);
  var contra = new THREE.DirectionalLight(0xff6a55, 0.7);
  contra.position.set(-5, -1, 2);
  escena.add(contra);
  var relleno = new THREE.DirectionalLight(0x9fb0c6, 0.4);
  relleno.position.set(4, -3, -4);
  escena.add(relleno);

  /* ---------- Estado: scroll, ratón, hover ---------- */
  var hero = document.querySelector('[data-tramo="hero"]');
  var precios = document.querySelector('[data-tramo="precios"]');

  var raton = { x: 0, y: 0 }, ratonSuave = { x: 0, y: 0 };
  var velocidadBase = reducir ? 0 : 0.12;
  var velocidad = velocidadBase, velocidadObjetivo = velocidadBase;
  var ultimoScroll = window.scrollY, impulsoScroll = 0;
  var progreso = 0, progresoSuave = 0;
  var entrada = reducir ? 1 : 0;
  var movil = false;

  window.addEventListener('pointermove', function (e) {
    raton.x = e.clientX / window.innerWidth - 0.5;
    raton.y = e.clientY / window.innerHeight - 0.5;
  }, { passive: true });

  document.querySelectorAll('[data-servicio]').forEach(function (fila) {
    fila.addEventListener('pointerenter', function () { if (!reducir) velocidadObjetivo = 1.1; });
    fila.addEventListener('pointerleave', function () { velocidadObjetivo = velocidadBase; });
  });

  function calcularProgreso() {
    var h = hero ? hero.offsetHeight : window.innerHeight;
    progreso = Math.max(0, Math.min(1, window.scrollY / (h * 0.9)));
    impulsoScroll += (window.scrollY - ultimoScroll) * 0.0009;
    ultimoScroll = window.scrollY;
  }
  window.addEventListener('scroll', function () { calcularProgreso(); if (reducir) dibujar(0); }, { passive: true });

  function ajustarTamano() {
    var w = window.innerWidth, h = window.innerHeight;
    renderer.setSize(w, h, false);
    camara.aspect = w / h;
    camara.updateProjectionMatrix();
    movil = w / h < 0.9;
  }
  window.addEventListener('resize', function () { ajustarTamano(); if (reducir) dibujar(0); });

  function mezcla(a, b, t) { return a + (b - a) * t; }

  /* ---------- Bucle ---------- */
  var reloj = new THREE.Clock();
  var visible = true;

  function dibujar(dt) {
    progresoSuave = mezcla(progresoSuave, progreso, reducir ? 1 : Math.min(1, dt * 5));
    ratonSuave.x = mezcla(ratonSuave.x, raton.x, Math.min(1, dt * 3));
    ratonSuave.y = mezcla(ratonSuave.y, raton.y, Math.min(1, dt * 3));
    velocidad = mezcla(velocidad, velocidadObjetivo, Math.min(1, dt * 4));
    if (!reducir) entrada = Math.min(1, entrada + dt * 0.9);
    var e = 1 - Math.pow(1 - entrada, 3);

    // Rayas que suben + impulso del scroll
    rayas.offset.y -= velocidad * dt + impulsoScroll;
    impulsoScroll *= 0.85;

    // Posición: portada -> precios
    var p = progresoSuave;
    var xPortada = movil ? 1.2 : 2.35, xPrecios = movil ? 1.75 : 3.15;
    var yPortada = movil ? 1.3 : 0;
    var escalaPortada = movil ? 0.5 : 0.9, escalaPrecios = movil ? 0.55 : 0.82;

    poste.position.x = mezcla(xPortada, xPrecios, p);
    poste.position.y = mezcla(yPortada, 0, p) - (1 - e) * 1.2;
    var s = mezcla(escalaPortada, escalaPrecios, p) * (0.75 + 0.25 * e);
    poste.scale.setScalar(s);

    poste.rotation.z = -0.12 + ratonSuave.x * -0.18 + p * 0.1;
    poste.rotation.x = ratonSuave.y * 0.2;
    poste.rotation.y = ratonSuave.x * 0.8 + p * 1.2;

    // En móvil, el poste se aparta visualmente detrás de los precios
    lienzo.style.opacity = movil ? String(mezcla(1, 0.28, p)) : '1';

    renderer.render(escena, camara);
  }

  function bucle() {
    var dt = Math.min(reloj.getDelta(), 0.05);
    if (visible) dibujar(dt);
    requestAnimationFrame(bucle);
  }

  // Deja de dibujar cuando el poste ya está tapado (ahorra batería)
  if (precios && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (entradas) {
      visible = entradas.some(function (x) { return x.isIntersecting; }) || window.scrollY < window.innerHeight;
    }).observe(precios);
  }
  document.addEventListener('visibilitychange', function () { reloj.getDelta(); });

  ajustarTamano();
  calcularProgreso();
  progresoSuave = progreso;

  if (reducir) {
    dibujar(0);
  } else {
    requestAnimationFrame(bucle);
  }
})();
