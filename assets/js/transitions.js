(function () {
    const CSS = `
        html.xisbe-animating { overflow: hidden; }

        @keyframes xisbe-spin-in {
            from { transform: rotate(-540deg) scale(0.05); opacity: 0; }
            to   { transform: rotate(0deg) scale(1); opacity: 1; }
        }
        @keyframes xisbe-spin-out {
            from { transform: rotate(0deg) scale(1); opacity: 1; }
            to   { transform: rotate(540deg) scale(0.05); opacity: 0; }
        }

        @keyframes xisbe-flip-in {
            from { transform: perspective(1000px) rotateY(-180deg); opacity: 0; }
            to   { transform: perspective(1000px) rotateY(0deg); opacity: 1; }
        }
        @keyframes xisbe-flip-out {
            from { transform: perspective(1000px) rotateY(0deg); opacity: 1; }
            to   { transform: perspective(1000px) rotateY(180deg); opacity: 0; }
        }

        @keyframes xisbe-roll-in {
            from { transform: translateX(-110vw) rotate(-540deg); opacity: 0; }
            to   { transform: translateX(0) rotate(0deg); opacity: 1; }
        }
        @keyframes xisbe-roll-out {
            from { transform: translateX(0) rotate(0deg); opacity: 1; }
            to   { transform: translateX(110vw) rotate(540deg); opacity: 0; }
        }

        @keyframes xisbe-fold-in {
            from { transform: perspective(1000px) rotateX(90deg); transform-origin: top center; opacity: 0; }
            to   { transform: perspective(1000px) rotateX(0deg); transform-origin: top center; opacity: 1; }
        }
        @keyframes xisbe-fold-out {
            from { transform: perspective(1000px) rotateX(0deg); transform-origin: bottom center; opacity: 1; }
            to   { transform: perspective(1000px) rotateX(-90deg); transform-origin: bottom center; opacity: 0; }
        }

        body.xisbe-spin-in   { animation: xisbe-spin-in  0.7s cubic-bezier(0.34, 1.4, 0.64, 1) both; }
        body.xisbe-spin-out  { animation: xisbe-spin-out 0.42s ease-in both; }

        body.xisbe-flip-in   { animation: xisbe-flip-in  0.6s ease-out both; }
        body.xisbe-flip-out  { animation: xisbe-flip-out 0.42s ease-in both; }

        body.xisbe-roll-in   { animation: xisbe-roll-in  0.65s cubic-bezier(0.25, 0.46, 0.45, 0.94) both; }
        body.xisbe-roll-out  { animation: xisbe-roll-out 0.42s ease-in both; }

        body.xisbe-fold-in   { animation: xisbe-fold-in  0.6s ease-out both; }
        body.xisbe-fold-out  { animation: xisbe-fold-out 0.42s ease-in both; }
    `;

    const style = document.createElement('style');
    style.textContent = CSS;
    document.head.appendChild(style);

    const effects = ['spin', 'flip', 'roll', 'fold'];

    function randomEffect(exclude) {
        const pool = effects.filter(e => e !== exclude);
        return pool[Math.floor(Math.random() * pool.length)];
    }

    // Play enter animation using whatever effect the previous page exited with
    const enterEffect = sessionStorage.getItem('xisbe-enter') || randomEffect(null);

    document.documentElement.classList.add('xisbe-animating');
    document.body.classList.add('xisbe-' + enterEffect + '-in');

    setTimeout(() => {
        document.body.classList.remove('xisbe-' + enterEffect + '-in');
        document.documentElement.classList.remove('xisbe-animating');
    }, 750);

    // Intercept internal link clicks for exit animation
    document.addEventListener('click', function (e) {
        const link = e.target.closest('a[href]');
        if (!link) return;

        const href = link.getAttribute('href');
        if (!href) return;
        if (href.startsWith('http') || href.startsWith('//') || href.startsWith('mailto') || href.startsWith('#')) return;
        if (link.hasAttribute('download')) return;
        if (link.getAttribute('target') === '_blank') return;

        e.preventDefault();

        const exitEffect = randomEffect(enterEffect);
        sessionStorage.setItem('xisbe-enter', exitEffect);

        document.documentElement.classList.add('xisbe-animating');
        document.body.classList.add('xisbe-' + exitEffect + '-out');

        setTimeout(() => { window.location.href = href; }, 460);
    });
})();
