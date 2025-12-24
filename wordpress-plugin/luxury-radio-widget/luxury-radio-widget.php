<?php
/*
Plugin Name: Luxury Radio Glass Widget
Description: Widget de Radio Premium con diseño Luxury Glass (V7). Usa el shortcode [luxury_radio] para mostrarlo.
Version: 7.0
Author: Antigravity
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function luxury_radio_shortcode() {
    // Generar ID único desde PHP para robustez total
    $widget_id = 'luxury-radio-' . uniqid();
    
    ob_start();
    ?>
    <!-- 💎 WIDGET DE RADIO PREMIUM LUXURY GLASS (V7 - ULTRA) -->
    <div id="<?php echo esc_attr($widget_id); ?>" class="wp-radio-widget-luxury">
        <!-- Fondo Animado -->
        <div class="luxury-bg-anim"></div>
        <div class="radio-glass-panel" id="mainPanel-<?php echo $widget_id; ?>">
            <button id="btn-<?php echo $widget_id; ?>" class="glass-btn-play" type="button">
                <svg class="play-icon" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                </svg>
                <svg class="pause-icon" viewBox="0 0 24 24">
                    <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                </svg>
                <div class="glass-btn-pulse"></div>
            </button>
            <div class="glass-info-panel">
                <div class="glass-station-title">
                    <div class="live-dot"></div>
                    RADIO EN VIVO
                </div>
                <div id="status-<?php echo $widget_id; ?>" class="glass-status-text">Click para conectar</div>
                <div class="glass-station-info">
                    <svg class="wave-icon" viewBox="0 0 24 24">
                        <path
                            d="M20 12c-1.5 0-2.5.5-3 1.5l-3 4.5c-.5 1-1.5 1.5-2.5 1.5s-2-.5-2.5-1.5L6 13.5C5.5 12.5 4.5 12 3 12" />
                    </svg>
                    <span>FM 99.3 • Sonido HD</span>
                </div>
            </div>
            <div class="glass-equalizer-viz">
                <div class="viz-bar"></div>
                <div class="viz-bar"></div>
                <div class="viz-bar"></div>
                <div class="viz-bar"></div>
                <div class="viz-bar"></div>
                <div class="viz-bar"></div>
            </div>
            <div class="glass-controls-panel">
                <div class="volume-control-wrapper">
                    <svg class="volume-icon" viewBox="0 0 24 24">
                        <path
                            d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z" />
                    </svg>
                    <input type="range" id="vol-<?php echo $widget_id; ?>" min="0" max="1" step="0.01" value="1.0">
                </div>
            </div>
            <!-- Mensaje de Error (Discreto y Elegante) -->
            <div id="err-<?php echo $widget_id; ?>" class="glass-error-message" style="display:none;">
                <div class="error-msg-text" style="font-size:12px; margin-bottom:10px; line-height:1.4;">
                    <span style="font-size: 18px;">🔒</span><br>
                    <strong>Acceso Bloqueado</strong><br>
                    <span style="opacity: 0.8;">Tu navegador bloqueó el audio no seguro (HTTP).</span>
                </div>
                <button id="retry-<?php echo $widget_id; ?>" class="retry-btn">
                    DESBLOQUEAR Y OIR
                </button>
            </div>
        </div>
        <audio id="audio-<?php echo $widget_id; ?>" preload="none" crossorigin="anonymous"></audio>
    </div>
    <style>
        /* FUENTE: Intentamos usar una fuente moderna si está disponible */
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap');
        /* ESTILOS LUXURY V7 */
        .wp-radio-widget-luxury {
            font-family: 'Outfit', system-ui, -apple-system, sans-serif;
            width: 340px;
            max-width: 100%;
            margin: 20px auto;
            border-radius: 28px;
            color: white;
            padding: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            position: relative;
            overflow: hidden;
            background: #0f0c29;
            /* Fallback */
            z-index: 100;
        }
        /* Fondo Animado "Aurora" */
        .luxury-bg-anim {
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 50% 50%, #240b36, #0f0c29 60%, #000000);
            animation: aurora 15s infinite linear;
            z-index: 1;
        }
        @keyframes aurora {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        .radio-glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px) saturate(120%);
            -webkit-backdrop-filter: blur(16px) saturate(120%);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            border-radius: 22px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        /* Botón Play Premium - Glossy */
        .glass-btn-play {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff0055 0%, #7000cc 100%);
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(255, 0, 85, 0.4), inset 0 2px 5px rgba(255, 255, 255, 0.4);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }
        .glass-btn-play:hover {
            transform: scale(1.08);
            box-shadow: 0 15px 40px rgba(255, 0, 85, 0.6), inset 0 2px 5px rgba(255, 255, 255, 0.4);
        }
        .glass-btn-play:active {
            transform: scale(0.95);
        }
        .glass-btn-play svg {
            width: 32px;
            height: 32px;
            fill: white;
            position: absolute;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
        }
        .pause-icon {
            opacity: 0;
            transition: 0.3s;
        }
        .playing .pause-icon {
            opacity: 1;
        }
        .playing .play-icon {
            opacity: 0;
        }
        .glass-btn-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            box-shadow: 0 0 0 0 rgba(255, 0, 85, 0.6);
        }
        .playing .glass-btn-pulse {
            animation: pulse-ring 2s infinite;
        }
        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 0, 85, 0.6);
            }
            70% {
                transform: scale(1.4);
                box-shadow: 0 0 0 20px rgba(255, 0, 85, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 0, 85, 0);
            }
        }
        /* Info */
        .glass-info-panel {
            text-align: center;
            width: 100%;
            margin-bottom: 20px;
        }
        .glass-station-title {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.9);
        }
        .live-dot {
            width: 8px;
            height: 8px;
            background: #ff2d55;
            border-radius: 50%;
            box-shadow: 0 0 10px #ff2d55;
        }
        .playing .live-dot {
            animation: blink 1.5s infinite;
            background: #00ff88;
            box-shadow: 0 0 10px #00ff88;
        }
        @keyframes blink {
            50% {
                opacity: 0.4;
            }
        }
        .glass-status-text {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            min-height: 20px;
            font-weight: 500;
        }
        .glass-station-info {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .wave-icon {
            width: 12px;
            height: 12px;
            fill: currentColor;
        }
        /* Ecualizador */
        .glass-equalizer-viz {
            display: flex;
            gap: 5px;
            height: 30px;
            align-items: flex-end;
            margin-bottom: 20px;
            opacity: 0.8;
        }
        .viz-bar {
            width: 6px;
            height: 4px;
            background: linear-gradient(to top, #ff0055, #bf00ff);
            border-radius: 3px;
            transition: height 0.1s ease;
        }
        /* Volumen */
        .glass-controls-panel {
            width: 100%;
            display: flex;
            justify-content: center;
        }
        .volume-control-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 90%;
            background: rgba(0, 0, 0, 0.3);
            padding: 10px 16px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: background 0.2s;
        }
        .volume-control-wrapper:hover {
            background: rgba(0, 0, 0, 0.4);
        }
        .volume-icon {
            width: 20px;
            height: 20px;
            fill: rgba(255, 255, 255, 0.8);
        }
        input[type=range] {
            flex-grow: 1;
            height: 5px;
            -webkit-appearance: none;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 3px;
            outline: none;
        }
        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            border: 2px solid rgba(0, 0, 0, 0.1);
            transition: transform 0.1s;
        }
        input[type=range]::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }
        /* Error Box */
        .glass-error-message {
            background: rgba(255, 59, 48, 0.15);
            border: 1px solid rgba(255, 59, 48, 0.4);
            border-radius: 16px;
            padding: 16px;
            margin-top: 15px;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
            animation: slideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            backdrop-filter: blur(5px);
        }
        .retry-btn {
            background: #ff3b30;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 30px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 8px;
            width: 100%;
            transition: transform 0.2s, background 0.2s;
            box-shadow: 0 4px 15px rgba(255, 59, 48, 0.3);
        }
        .retry-btn:hover {
            transform: translateY(-1px);
            background: #ff5e55;
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Loading */
        .loading-spinner {
            width: 26px;
            height: 26px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <script>
        (function (uid) {
            // Scope estrictamente al ID generado
            var container = document.getElementById(uid);
            if (!container) return; // Si no existe, salir sin error

            var audio = document.getElementById('audio-' + uid);
            var playBtn = document.getElementById('btn-' + uid);
            var status = document.getElementById('status-' + uid);
            var panel = document.getElementById('mainPanel-' + uid);
            var errorBox = document.getElementById('err-' + uid);
            var retryBtn = document.getElementById('retry-' + uid);
            var volControl = document.getElementById('vol-' + uid);
            var bars = container.querySelectorAll('.viz-bar');

            // Seguridad extra: verificar que todos los elementos existan
            if (!audio || !playBtn || !volControl) {
                console.error('Luxury Radio: Elementos del DOM no encontrados para ' + uid);
                return;
            }

            // Configuración
            var streamBase = 'https://tv.monagasvision.com/radio.aac';
            var isPlaying = false;
            var isLoading = false;
            var eqInterval;
            var connTimeout;
            var playLock = false; // Flag para prevenir race conditions

            // Volumen init
            audio.volume = 1.0;
            volControl.value = 1.0;

            volControl.addEventListener('input', function (e) {
                if(audio) audio.volume = e.target.value;
            });

            function setVisualState(state) {
                if (state === 'playing') {
                    panel.classList.add('playing');
                    status.textContent = 'EN VIVO • ON AIR';
                    status.style.color = '#00ff88';
                    status.style.fontWeight = '700';
                    status.style.textShadow = '0 0 10px rgba(0,255,136,0.3)';
                    playBtn.innerHTML = '<svg class="pause-icon" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg><div class="glass-btn-pulse"></div>';
                    startEq();
                } else if (state === 'loading') {
                    status.textContent = 'Conectando señal...';
                    status.style.color = '#ffd700';
                    status.style.textShadow = 'none';
                    playBtn.innerHTML = '<div class="loading-spinner"></div>';
                } else {
                    panel.classList.remove('playing');
                    status.textContent = 'Click para Escuchar';
                    status.style.color = 'rgba(255,255,255,0.6)';
                    status.style.textShadow = 'none';
                    playBtn.innerHTML = '<svg class="play-icon" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>';
                    stopEq();
                }
            }

            function playStream() {
                if (playLock) return; // Evitar clicks multiples
                
                if (isPlaying) { 
                    stopStream(); 
                    return; 
                }

                playLock = true;
                errorBox.style.display = 'none';
                setVisualState('loading');
                isLoading = true;

                // Cache busting para nueva conexión
                audio.src = streamBase + '?t=' + Date.now();
                audio.load(); // Forzar carga

                var playPromise = audio.play();

                if (playPromise !== undefined) {
                    playPromise.then(function() {
                       // Éxito
                       setTimeout(function(){ playLock = false; }, 200);
                    }).catch(function(error) {
                        playLock = false;
                        if (error.name === 'AbortError') {
                            // Ignorar aborts (usuario pausó rápido, o tab en background)
                            return; 
                        }
                        if (error.name === 'NotSupportedError' || error.name === 'NotAllowedError') {
                            // Bloqueo de Autoplay o Mixed Content
                            handleError();
                        } else {
                            handleError();
                        }
                    });
                } else {
                     playLock = false;
                }

                // Timeout de seguridad por si se queda "Conectando..." infinito
                clearTimeout(connTimeout);
                connTimeout = setTimeout(function() {
                    if(isLoading && !isPlaying) {
                        handleError();
                    }
                }, 10000);
            }

            function stopStream() {
                playLock = true; // Bloqueo temporal
                audio.pause();
                audio.src = ''; // Cortar conexión
                isPlaying = false;
                isLoading = false;
                clearTimeout(connTimeout);
                setVisualState('idle');
                // Liberar lock rápido
                setTimeout(function(){ playLock = false; }, 200);
            }

            function handleError() {
                // Limpieza total
                audio.pause();
                audio.src = ''; 
                isPlaying = false;
                isLoading = false;
                playLock = false;
                clearTimeout(connTimeout);
                setVisualState('idle');

                // Mostrar UI de ayuda
                errorBox.style.display = 'block';
                
                // El botón de retry simplemente vuelve a intentar playStream
                retryBtn.onclick = function() {
                    errorBox.style.display = 'none';
                    playStream();
                };
            }

            function startEq() {
                stopEq();
                eqInterval = setInterval(function () {
                    bars.forEach(function (bar) {
                        bar.style.height = (4 + Math.random() * 24) + 'px';
                    });
                }, 80);
            }

            function stopEq() {
                clearInterval(eqInterval);
                bars.forEach(function (bar) { bar.style.height = '6px'; });
            }

            // Event Listeners
            playBtn.addEventListener('click', playStream);
            
            audio.addEventListener('playing', function () {
                clearTimeout(connTimeout);
                isPlaying = true;
                isLoading = false;
                playLock = false;
                setVisualState('playing');
                errorBox.style.display = 'none';
            });
            
            audio.addEventListener('error', function (e) {
                // Solo manejar error si estábamos intentando cargar
                if (isLoading) {
                    handleError();
                }
            });

        })('<?php echo $widget_id; ?>');
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'luxury_radio', 'luxury_radio_shortcode' );
