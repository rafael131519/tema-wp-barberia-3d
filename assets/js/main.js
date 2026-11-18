/**
 * Barbería Filo 3D — interacción general
 * 1. Menú móvil  2. Cabecera al hacer scroll  3. Aparición al deslizar  4. Tarjetas que se inclinan
 */
(function () {
  'use strict';

  // 1. Menú móvil
  var boton = document.querySelector('[data-menu-boton]');
  var menu = document.querySelector('[data-menu]');
  if (boton && menu) {
    var cerrar = function () {
      menu.classList.remove('menu--abierto');
      boton.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('sin-scroll');
    };
    boton.addEventListener('click', function () {
      var abierto = menu.classList.toggle('menu--abierto');
      boton.setAttribute('aria-expanded', abierto ? 'true' : 'false');
      document.body.classList.toggle('sin-scroll', abierto);
    });
    menu.querySelectorAll('a').forEach(function (a) { a.addEventListener('click', cerrar); });
  }

  // 2. Cabecera
  var cabecera = document.querySelector('[data-cabecera]');
  if (cabecera) {
    var actualizar = function () { cabecera.classList.toggle('cabecera--scroll', window.scrollY > 40); };
    actualizar();
    window.addEventListener('scroll', actualizar, { passive: true });
  }

  // 3. Aparición al deslizar (sin difuminado: máscara + subida)
  var reducirMov = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var selectores = [
    '.titulo-seccion', '.precios__nota', '.carta__fila', '.precios > .contenedor > .boton',
    '.estudio__punto', '.tarjeta', '.galeria__mas',
    '.visitanos__direccion', '.horario tr', '.visitanos .acciones', '.mapa', '.pie__fila > *'
  ];
  var aparecer = document.querySelectorAll(selectores.join(','));
  if (!reducirMov && aparecer.length) {
    document.documentElement.classList.add('js-aparecer');
    var pendientes = Array.prototype.slice.call(aparecer);
    pendientes.forEach(function (el) {
      el.classList.add('aparecer');
      // Escalonado: cada hermano sale 80 ms después del anterior (máximo 6 pasos)
      var indice = Array.prototype.indexOf.call(el.parentNode.children, el);
      el.style.setProperty('--retraso', Math.min(indice, 6) * 80 + 'ms');
    });
    // Se comprueba la posición en cada scroll (la máscara impide usar IntersectionObserver)
    var programado = false;
    // Al terminar de aparecer se quita la máscara: así nunca recorta letras,
    // sombras ni el giro 3D de las tarjetas.
    var terminar = function (e) {
      if (e.target === this && e.propertyName === 'clip-path') {
        this.classList.add('aparecer--fin');
        this.removeEventListener('transitionend', terminar);
      }
    };
    pendientes.forEach(function (el) { el.addEventListener('transitionend', terminar); });

    var revisar = function () {
      programado = false;
      var limite = window.innerHeight * 0.92;
      // Al llegar al final de la página aparece todo lo que quede (el pie)
      var alFinal = window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 4;
      pendientes = pendientes.filter(function (el) {
        if (alFinal || el.getBoundingClientRect().top < limite) {
          el.classList.add('aparecer--visto');
          return false;
        }
        return true;
      });
      if (!pendientes.length) window.removeEventListener('scroll', alScroll);
    };
    var alScroll = function () {
      if (!programado) { programado = true; requestAnimationFrame(revisar); }
    };
    window.addEventListener('scroll', alScroll, { passive: true });
    window.addEventListener('resize', alScroll);
    revisar();
  }

  // 4. Inclinación 3D de las tarjetas (solo con ratón y si no se pide menos movimiento)
  var conRaton = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  var reducir = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (conRaton && !reducir) {
    document.querySelectorAll('[data-inclinar]').forEach(function (tarjeta) {
      tarjeta.addEventListener('pointermove', function (e) {
        var r = tarjeta.getBoundingClientRect();
        var x = (e.clientX - r.left) / r.width - 0.5;
        var y = (e.clientY - r.top) / r.height - 0.5;
        tarjeta.style.setProperty('--ry', (x * 14).toFixed(2) + 'deg');
        tarjeta.style.setProperty('--rx', (-y * 14).toFixed(2) + 'deg');
      });
      tarjeta.addEventListener('pointerleave', function () {
        tarjeta.style.setProperty('--ry', '0deg');
        tarjeta.style.setProperty('--rx', '0deg');
      });
    });
  }
})();
